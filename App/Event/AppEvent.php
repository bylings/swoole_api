<?php


namespace App\Event;

use EasySwoole\EasySwoole\AbstractInterface\Event;
use EasySwoole\EasySwoole\Config;
use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\ORM\Db\Connection;
use EasySwoole\ORM\DbManager;
use EasySwoole\Utility\File;

class AppEvent implements Event
{
    /**
     * @var array
     */
    private static $instance = [];

    /**
     * @return $this
     */
    public static function getInstance()
    {
        $className = get_called_class();
        if (!isset(self::$instance[$className]) || !self::$instance[$className] instanceof self) {
            self::$instance[$className] = new static;
        }
        return self::$instance[$className];
    }

    public static function initialize()
    {
        self::loadEnvConf(EASYSWOOLE_ROOT . '/.env.yml');
        self::loadConf(EASYSWOOLE_ROOT . '/App/Config');
        self::loadDB();
        self::loadRedis();
    }

    public static function loadEnvConf($envFile)
    {
        if (!is_file($envFile)) {
            exit('env file is not find');
        }
        Config::getInstance()->setConf('env', load($envFile));
    }

    public static function loadConf($ConfPath)
    {
        $Conf  = Config::getInstance();
        $files = File::scanDirectory($ConfPath);
        if (!is_array($files)) {
            return;
        }
        foreach (($files['files'] ?? []) as $file) {
            $data = include $file;

            $Conf->setConf(strtolower(basename($file, '.php')), (array)$data);
        }
    }

    public static function loadDB()
    {
        // 获得数据库配置
        $dbConf = Config::getInstance()->getConf('env')['database'] ?? [];

        foreach ((array)$dbConf as $connectionName => $connectionConfig) {
            $config = new \EasySwoole\ORM\Db\Config($connectionConfig);
            DbManager::getInstance()->addConnection(new Connection($config), $connectionName);
        }
    }

    /**
     * 加载redis配置
     */
    public static function loadRedis()
    {
        $redisConfig = env()['redis'] ?? [];
        $config      = [
            'host'           => $redisConfig['host'] ?? '127.0.0.1',
            'port'           => $redisConfig['port'] ?? '6379',
            'auth'           => $redisConfig['auth'] ?? 'easyswoole',
            'db'             => $redisConfig['database'] ?? null,
            'timeout'        => $redisConfig['timeout'] ?? 3.0,
            'reconnectTimes' => $redisConfig['reconnectTimes'] ?? 3,
            'serialize'      => \EasySwoole\Redis\Config\RedisConfig::SERIALIZE_NONE
        ];

//        $redis = new \EasySwoole\Redis\Redis(new \EasySwoole\Redis\Config\RedisConfig($config));

        // redis连接池注册
        $redisPoolConfig = \EasySwoole\RedisPool\RedisPool::getInstance()->register(
            new \EasySwoole\Redis\Config\RedisConfig($config),
            'redis'
        );
        //配置连接池连接数

        $redisPoolMinObjectNum = $redisConfig['redisPoolMinObjectNum'] ?? 5;
        $redisPoolMaxObjectNum = $redisConfig['redisPoolMaxObjectNum'] ?? 10;
        $redisPoolConfig->setMinObjectNum($redisPoolMinObjectNum);
        $redisPoolConfig->setMaxObjectNum($redisPoolMaxObjectNum);

        //defer方式获取连接
//        $redis        = \EasySwoole\RedisPool\RedisPool::defer();
//        $redisCluster = \EasySwoole\RedisPool\RedisPool::defer();

//        Di::getInstance()->set('redis', $redis);
    }

    public static function mainServerCreate(EventRegister $register)
    {

    }
}