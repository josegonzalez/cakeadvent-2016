<?php
use Cake\Core\Configure;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

$routeClass = Configure::read('PhotoPostType.Routes.routeClass');
$routeClass = $routeClass ?: DashedRoute::class;

Router::plugin('PhotoPostType', ['path' => '/'], function ($routes) use ($routeClass) {
    $photoPostTypePrefix = Configure::read('PhotoPostType.Routes.prefix');
    $photoPostTypePrefix = $photoPostTypePrefix ?: '/order';
    $photoPostTypePrefix = '/' . trim($photoPostTypePrefix, "\t\n\r\0\x0B/");

    $routes->connect(
        $photoPostTypePrefix,
        ['controller' => 'Orders', 'action' => 'order'],
        ['id' => '\d+', 'pass' => ['id'], 'routeClass' => $routeClass]
    );
    $routes->scope('/admin/orders', ['controller' => 'Orders'], function (RouteBuilder $routes) {
        $routes->connect('/', ['action' => 'index']);
        $routes->fallbacks();
    });
});
