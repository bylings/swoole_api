<?php

namespace App\Models;

use EasySwoole\ORM\AbstractModel;

class BaseModel extends AbstractModel
{
    protected $primaryKey;
    protected $autoTimeStamp = false;
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';

    /**
     * 获取主键字段
     * @return array|mixed|null
     * @throws \EasySwoole\ORM\Exception\Exception
     */
    public static function getPrimaryKey()
    {
        if ((new static())->primaryKey) {
            return (new static())->primaryKey;
        }
        return (new static())->schemaInfo()->getPkFiledName();
    }

    /**
     * 根据主键获取信息
     * @param $id
     * @param array $column
     * @return BaseModel|array|bool|AbstractModel|\EasySwoole\ORM\Db\Cursor|\EasySwoole\ORM\Db\CursorInterface|null
     * @throws \EasySwoole\Mysqli\Exception\Exception
     * @throws \EasySwoole\ORM\Exception\Exception
     * @throws \Throwable
     */
    public static function getInfoById($id, $column = [])
    {
        if (!$id) {
            return [];
        }
        return self::create()->where(self::getPrimaryKey(), $id)->field($column)->get();
    }

    /**
     * @param $ids
     * @param array $column
     * @return array
     * @throws \EasySwoole\Mysqli\Exception\Exception
     * @throws \EasySwoole\ORM\Exception\Exception
     * @throws \Throwable
     */
    public static function getInfoByIds($ids, $column = [])
    {
        $ids = array_values(array_filter(array_unique($ids), function ($id) {
            if ($id === '0' || !empty($id)) {
                return true;
            }
        }));
        return self::create()->where(self::getPrimaryKey(), $ids, 'in')->field($column)->all()->toArray();
    }

    /**
     * 根据主键更新数据
     * @param $id
     * @param array $updateData
     * @return bool
     * @throws \EasySwoole\Mysqli\Exception\Exception
     * @throws \EasySwoole\ORM\Exception\Exception
     * @throws \Throwable
     */
    public static function updateById($id, $updateData = [])
    {
        if (!$id || empty($updateData)) {
            return false;
        }
        $updateData = array_merge($updateData, [
            (new static())->getUpdateTime() => date('Y-m-d H:i:s')
        ]);
        return self::create()->where(self::getPrimaryKey(), $id)->update($updateData);
    }
}
