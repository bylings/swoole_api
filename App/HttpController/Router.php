<?php


namespace App\HttpController;


use EasySwoole\Http\AbstractInterface\AbstractRouter;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use FastRoute\RouteCollector;

class Router extends AbstractRouter
{
    function initialize(RouteCollector $routeCollector)
    {
        /*
          * eg path : /router/index.html  ; /router/ ;  /router
         */
        $routeCollector->get('/router', '/test');
        /*
         * eg path : /closure/index.html  ; /closure/ ;  /closure
         */
        $routeCollector->get('/closure', function (Request $request, Response $response) {
            $response->write('this is closure router');
            //不再进入控制器解析
            return false;
        });

        $routeCollector->addGroup('/component', function (\FastRoute\RouteCollector $collector) {
            // ################### Region ###############
            $collector->get('/regions', '/Component/Regions/getRegions');
            $collector->get('/region/{code:\d+}/childs', '/Component/Regions/getRegionChilds');

            // ################### Redis ###############
            $collector->addGroup('/redis', function (\FastRoute\RouteCollector $collector) {
                $collector->get('/lock', '/Component/Redis/lock');
            });
            // ################### Location ###############
            $collector->addGroup('/location', function (\FastRoute\RouteCollector $collector) {
                $collector->get('', '/Component/Location/getLocation');
            });
        });
    }
}