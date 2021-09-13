<?php


namespace App\HttpController;


use App\Base\BaseController;
use itbdw\Ip\IpLocation;

class Index extends BaseController
{

    public function index()
    {
        $ip            = '119.131.44.15';
        $qqwryFilepath = EASYSWOOLE_ROOT . '/App/Base/qqwry.dat';
        $ret           = IpLocation::getLocation($ip, $qqwryFilepath);
        return $this->responseJson($ret);
        $file = EASYSWOOLE_ROOT . '/vendor/easyswoole/easyswoole/src/Resource/Http/welcome.html';
        if (!is_file($file)) {
            $file = EASYSWOOLE_ROOT . '/src/Resource/Http/welcome.html';
        }
        $this->response()->write(file_get_contents($file));
    }

    function test()
    {
        $this->response()->write('this is test');
    }

    protected function actionNotFound(?string $action)
    {
        $this->response()->withStatus(404);
        $file = EASYSWOOLE_ROOT . '/vendor/easyswoole/easyswoole/src/Resource/Http/404.html';
        if (!is_file($file)) {
            $file = EASYSWOOLE_ROOT . '/src/Resource/Http/404.html';
        }
        $this->response()->write(file_get_contents($file));
    }
}