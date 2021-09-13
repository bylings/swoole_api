<?php

namespace App\Service\Component;

use App\Constants\CacheKey;
use App\Models\Component\Regions;
use App\Service\BaseService;

class RegionsService extends BaseService
{
    public function getRegions($level = 3)
    {
        $cacheKey = CacheKey::COMPONENT_REGIONS . '_' . $level;
        if (redis()->exists($cacheKey)) {
            return json_decode(redis()->get($cacheKey), true);
        }
        $column  = ['code', 'name', 'parent_code', 'format'];
        $regions = Regions::getRegions($level, $column);

        $regions = $this->buildTrees($regions, 'code', 'parent_code');
        redis()->set($cacheKey, json_encode($regions, JSON_UNESCAPED_UNICODE));
        redis()->expire($cacheKey, 3600 * 24 * 7);
        return $regions;
    }

    public function getRegionChildsByCode($code)
    {
        $column = ['code', 'name', 'format'];
        return Regions::getChildByCode($code, $column);
    }

    /**
     * 构建树
     * @param $list
     * @param string $pk
     * @param string $pid
     * @param string $childIdentification
     * @param int $level
     * @return array
     */
    private function buildTrees($list, $pk = 'code', $pid = 'parent_code', $childIdentification = 'child', $level = 0)
    {
        $tree = [];
        foreach ($list as $key => $val) {
            if ($val[$pid] == $level) {
                $val[$childIdentification] = $this->buildTrees($list, $pk, $pid, $childIdentification, $val[$pk]);
                unset($val[$pid]);
                $tree[] = $val;
                $num++;
                unset($list[$key]);
            }
        }
        return $tree;
    }
}