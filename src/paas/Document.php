<?php
namespace paas;

use Exception;

/**
 * @file Document.php
 * @author Yan.Gao
 * @version 0.0.1
 * @date 2018-04-20
 */

class Document extends Common
{
    // 创建文档
    public function create(array $param)
    {
        $model = $this->getModel(__CLASS__);
        $func  = $this->getFunc(__FUNCTION__);

        $address = $model . "/" . $func;

        //  文件类型特殊处理
        $requestData = $this->createRealParam($param);
        $documentUrl = $param['document'];

        if (!file_exists($documentUrl)) {
            throw new Exception('文件不存在');
        }
        $requestData['document'] = new \CURLFile($documentUrl);

        return $this->requestData($address, $requestData);
    }
}
