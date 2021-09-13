<?php

namespace App\Base;


use EasySwoole\Http\Message\Status;

class BaseController extends \EasySwoole\Http\AbstractInterface\Controller
{
    public function responseJson($result = null, $statusCode = 200, $msg = 'Success')
    {
        return $this->writeJson($statusCode, $result, $msg);
    }

    protected function onException(\Throwable $throwable): void
    {
        $this->responseJson(null, Status::CODE_INTERNAL_SERVER_ERROR, $throwable->getMessage());
    }
}