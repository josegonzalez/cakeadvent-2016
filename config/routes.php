<?php
use Cake\Core\Plugin;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

Router::defaultRouteClass(DashedRoute::class);

Router::scope('/', function (RouteBuilder $routes) {
    $routes->connect('/', ['controller' => 'Posts', 'action' => 'home']);
    $routes->connect('/login', ['controller' => 'Users', 'action' => 'login']);
    $routes->connect('/logout', ['controller' => 'Users', 'action' => 'logout']);
    $routes->connect('/forgot-password', ['controller' => 'Users', 'action' => 'forgot-password']);
    $routes->connect('/reset-password/*', ['controller' => 'Users', 'action' => 'forgot-password']);
});

Router::scope('/admin', function (RouteBuilder $routes) {
    $routes->scope('/posts', ['controller' => 'Posts'], function (RouteBuilder $routes) {
        $routes->connect('/', ['action' => 'index']);
        $routes->fallbacks();
    });
    $routes->connect('/profile', ['controller' => 'Users', 'action' => 'edit']);
});

Plugin::routes();
