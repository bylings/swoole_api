<?php


namespace EasySwoole\EasySwoole;


use App\Event\AppEvent;
use EasySwoole\EasySwoole\AbstractInterface\Event;
use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;

class EasySwooleEvent implements Event
{
    public static function initialize()
    {
        date_default_timezone_set('Asia/Shanghai');
        AppEvent::getInstance()->initialize();
    }

    public static function mainServerCreate(EventRegister $register)
    {

    }

    public static function afterAction(Request $request, Response $response)
    {
        unset($GLOBALS);
        unset($_GET);
        unset($_POST);
        unset($_SESSION);
        unset($_COOKIE);
    }
}