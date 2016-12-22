<?php
use Cake\Core\Configure;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

$routeClass = Configure::read('PhotoPostType.Routes.routeClass');
$routeClass = $routeClass ?: DashedRoute::class;

$photoPostTypePrefix = Configure::read('PhotoPostType.Routes.prefix');
$photoPostTypePrefix = $photoPostTypePrefix ?: '/order';
$photoPostTypePrefix = '/' . trim($photoPostTypePrefix, "\t\n\r\0\x0B/");
Router::plugin('PhotoPostType', ['path' => $photoPostTypePrefix], function ($routes) use ($routeClass) {
    $routes->connect(
        '/',
        ['controller' => 'Orders', 'action' => 'order'],
        ['id' => '\d+', 'pass' => ['id'], 'routeClass' => $routeClass]
    );
});
