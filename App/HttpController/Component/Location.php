<?php

namespace App\HttpController\Component;

use App\Base\BaseController;
use itbdw\Ip\IpLocation;

class Location extends BaseController
{
    /**
     * @return bool
     * @throws \EasySwoole\Validate\Exception\Runtime
     */
    public function getLocation()
    {
        // 验证规则
        $rules = [
            'ip' => 'required|notEmpty|isIp',
        ];
        // 组装快速验证
        CustomValidator($rules, $this->request()->getQueryParams());
        $ip            = $this->request()->getQueryParam('ip');
        $qqwryFilepath = EASYSWOOLE_ROOT . '/App/Base/qqwry.dat';
        return $this->responseJson(IpLocation::getLocation($ip, $qqwryFilepath));
    }
}