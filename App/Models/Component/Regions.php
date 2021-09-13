<?php

namespace App\Models\Component;

use App\Models\BaseModel;

class Regions extends BaseModel
{
    protected $tableName = 'regions';
    protected $connectionName = 'component';

    /**
     * @param int $level
     * @param array $column
     * @return array
     * @throws \EasySwoole\ORM\Exception\Exception
     * @throws \Throwable
     */
    public static function getRegions($level = 3, $column = [])
    {
        return self::create()
            ->where('level', $level, '<=')
            ->order(['sort' => 'asc'])
            ->field($column)
            ->all()
            ->toArray();
    }

    /**
     * 根据地区代码获取下辖数据
     * @param $code
     * @param array $column
     * @return array
     * @throws \EasySwoole\ORM\Exception\Exception
     * @throws \Throwable
     */
    public static function getChildByCode($code, $column = [])
    {
        if (empty($code)) {
            return [];
        }
        return self::create()
            ->where('parent_code', $code)
            ->order(['sort' => 'asc'])
            ->field($column)
            ->all()
            ->toArray();
    }
}
