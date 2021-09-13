<?php

namespace App\HttpController\Component;

use App\Base\BaseController;
use App\HttpController\Router;
use App\Service\Component\RegionsService;
use EasySwoole\Component\Context\ContextManager;

class Regions extends BaseController
{
    /**
     * @return bool
     */
    public function getRegions()
    {
        return $this->responseJson(RegionsService::getInstance()->getRegions());
    }

    /**
     * 根据地区代码获取下辖数据
     * @return bool
     */
    public function getRegionChilds()
    {
        $routeParams = ContextManager::getInstance()->get(Router::PARSE_PARAMS_CONTEXT_KEY);
        $code        = $routeParams['code'] ?? '';
        return $this->responseJson(RegionsService::getInstance()->getRegionChildsByCode($code));
    }
}