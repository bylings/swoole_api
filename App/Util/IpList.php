<?php

namespace App\Util;

use EasySwoole\Component\Singleton;
use EasySwoole\Component\TableManager;
use Swoole\Table;

class IpList
{
    use Singleton;

    /** @var Table */
    protected $table;

    public function __construct()
    {
        TableManager::getInstance()->add('ipList', [
            'ip'             => [
                'type' => Table::TYPE_STRING,
                'size' => 16
            ],
            'count'          => [
                'type' => Table::TYPE_INT,
                'size' => 8
            ],
            'lastAccessTime' => [
                'type' => Table::TYPE_INT,
                'size' => 8
            ]
        ], 1024 * 128);
        $this->table = TableManager::getInstance()->get('ipList');
    }

    public function access(string $ip): int
    {
        $key       = substr(md5($ip), 8, 16);
        $cacheData = $this->table->get($key);

        $count = $cacheData ? ($cacheData['count'] + 1) : 1;

        $data = [
            'lastAccessTime' => time(),
            'count'          => $count,
        ];
        if ($cacheData) {
            $data = array_merge($data, ['ip' => $ip]);
        }
        $this->table->set($key, $data);
        return $count;
    }

    public function clear()
    {
        foreach ($this->table as $key => $item) {
            $this->table->del($key);
        }
    }

    public function accessList($count = 10): array
    {
        $ret = [];
        foreach ($this->table as $key => $item) {
            if ($item['count'] >= $count) {
                $ret[] = $item;
            }
        }
        return $ret;
    }
}
