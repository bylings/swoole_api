<?php

use EasySwoole\EasySwoole\Config;
use EasySwoole\Http\Response;
use Symfony\Component\Yaml\Yaml;

if (!function_exists('isDev')) {
    /**
     * @return bool
     */
    function isDev()
    {
        return !(\EasySwoole\EasySwoole\Core::getInstance()->runMode() === 'produce');
    }
}

if (!function_exists('load')) {
    /**
     * 加载读取文件
     * @param $file
     * @return array
     */
    function load($file)
    {
        $extension = pathinfo($file, PATHINFO_EXTENSION);
        switch ($extension) {
            case 'ini':
                $config = parse_ini_file($file, true);
                break;
            case 'yml':
                $config = Yaml::parse(file_get_contents($file));
                break;
            case 'json':
                $config = json_decode(file_get_contents($file), true);
                break;
            case 'php':
            default:
                $config = include $file;
        }
        return $config;
    }
}

if (!function_exists('config')) {
    function config()
    {
        return Config::getInstance()->getConf('config');
    }
}

if (!function_exists('env')) {
    function env()
    {
        return Config::getInstance()->getConf('env');
    }
}

if (!function_exists('logger')) {
    function logger()
    {
        return \EasySwoole\EasySwoole\Logger::getInstance();
    }
}

if (!function_exists('redis')) {
    /**
     * @return callable|\EasySwoole\Redis\Redis|null
     * @throws Throwable
     */
    function redis()
    {
        return \EasySwoole\RedisPool\RedisPool::defer('redis');
    }
}

if (!function_exists('CustomValidator')) {
    /**
     * @param $rules
     * @param array $params
     * @return callable|\EasySwoole\Redis\Redis|null
     * @throws \EasySwoole\Validate\Exception\Runtime
     */
    function CustomValidator($rules, $params = [])
    {
        // 组装快速验证
        $validate = \EasySwoole\Validate\Validate::make($rules);
        // 验证结果
        if (!$validate->validate($params)) {
            throw new \Swoole\Exception($validate->getError()->__toString(), 400);
        }
    }
}
