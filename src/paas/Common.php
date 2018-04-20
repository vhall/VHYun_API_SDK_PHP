<?php

namespace paas;

use Exception;

abstract class Common {

	private $appId;
	private $secretKey;

	private $requestDomain;
	private $showRequestUrl; // 是否显示构建请求信息 js console.log
	private $showRequestData; // 是否显示接口返回数据 js console.dir
	static $checkConfig;

	const HTTP_PROTOCOL = 'http';

	/**
	 * Common constructor.
	 * @param $config
	 * @throws Exception
	 */
	public function __construct($config)
	{
		if (!is_array($config)) {
			throw new Exception('配置文件类型不正确');
		}
		if (!isset($config['appId'])) {
			throw new Exception('appId 为必选项');
		}
		if (!isset($config['secretKey'])) {
			throw new Exception('secretKey 为必选项');
		}

		$this->appId          = $config['appId'];
		$this->secretKey       = $config['secretKey'];
		$this->requestDomain   = isset($config['request_domain']) ? $config['request_domain'] : 'api.vhallyun.com';
		$this->showRequestUrl  = isset($config['show_request_url']) ? $config['show_request_url'] : false;
		$this->showRequestData = isset($config['show_request_data']) ? $config['show_request_data'] : false;
	}

	/**
	 * get request mod class
	 *
	 * @return mixed
	 */
	protected function getModel()
	{
		$localModelMsg = explode('\\', static::class);

		return strtolower($localModelMsg[count($localModelMsg) - 1]);
	}

	/**
	 * according to function rename get real name
	 *
	 * @param $func
	 * @return mixed
	 */
	protected function getFunc($func)
	{
		return str_replace('_', '-', $func);
	}

	/**
	 * @param $url
	 * @param $param
	 * @return bool|mixed
	 * @throws Exception
	 */
	protected function requestData( $url, $param ) {

		$url = $this->requestDomain . '/api/v1/' . $url;	
		$data = $this->createRealParam($param);

		$response = $this->CurlRequest($url, $data);

		// 构建请求地址
		if ($this->showRequestUrl) {
			$this->consoleLog(self::HTTP_PROTOCOL.'://'.$url.'?'.http_build_query($param),'log');
		}

		if( $response ) {
			$response = json_decode($response, true);
			if (!is_array($response) || empty($response)) {
				throw new Exception('接口请求数据类型不对');
			}
			if (!isset($response['code'])) {
				throw new Exception('接口请求数据错误码非正常');
			}
			if (array_search($response['code'], [200, 10019]) === false) {
				throw new Exception($response['msg']);
			}
			// 显示返回数据
			if ($this->showRequestData) {
				$this->consoleLog($response);
			}
			return $response;
		}
		else {
			throw new Exception('接口请求失败');
		}
	}

	/**
	 * 直接打印到console
	 * @param $data
	 * @param bool $log
	 */
	protected function consoleLog($data, $log = false)
	{
		// 数据预处理json
		if (is_string($data) && $preJsonMsg = json_decode($data, true)) {
			if (count($preJsonMsg) > 1) {
				$data = $preJsonMsg;
			}
		}

		$logFunc = $log ? 'console.log' : 'console.dir';

		if (is_array($data) || is_object($data)) {
			echo("<script>".$logFunc."(".json_encode($data).");</script>");
		} else {
			echo("<script>".$logFunc."('".$data."');</script>");
		}
	}

    /**
     * 生成数字签名
     * @param $requestData
     * @return string
     * @throws Exception
     */
	protected function sign($requestData) {
        $str = '';

        if (!is_array($requestData)) {
            throw new Exception('签名数据类型不正确');
        }

        ksort($requestData);

        // 签名排除字段
        $signFilter = [
            'document'
        ];

        foreach ($requestData as $k => $v) {
            if (array_search($k, $signFilter) !== false) {
                continue;
            }
            $str .= $k . $v;
        }

        $str = $this->secretKey . $str . $this->secretKey;

        return md5($str);
	}

	/**
	 * 生成最终请求字段
	 * @param $data
	 * @return mixed
	 */
	public function createRealParam($data) {
		if (isset($data['signed_at'])) return $data;
		$data['signed_at'] = time();
		$data['app_id'] = $this->appId;
		$data['sign'] = $this->sign($data);
		return $data;
	}


	/**
	 * @param $url
	 * @param string $data
	 * @return bool|mixed
	 */
	protected function CurlRequest($url, $data = '')
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		curl_setopt($curl, CURLOPT_TIMEOUT, 60);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		$resultData = curl_exec($curl);

		if (curl_errno($curl)) {
			curl_close($curl);
			return false;
		} else {
			curl_close($curl);

			return $resultData;
		}
	}

	/**
	 * @param $name
	 * @param array $arguments
	 * @return bool|mixed
	 */
	public function __call($name, array $arguments) {
		$method = $this->getModel() .'/'. $this->getFunc($name);

		// 零参处理
		$arguments = isset($arguments[0]) && $arguments[0] ? $arguments[0] : [];

		return $this->requestData( $method, $arguments );
	}

}
