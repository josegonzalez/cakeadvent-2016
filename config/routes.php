<?php
use Cake\Core\Plugin;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

Router::defaultRouteClass(DashedRoute::class);

Router::scope('/', function (RouteBuilder $routes) {
    $routes->connect('/', ['controller' => 'Posts', 'action' => 'home']);

    $usersRoutes = [
        'login' => '/login',
        'logout' => '/logout',
        'forgotPassword' => '/forgot-password',
        'resetPassword' => '/reset-password/*',
    ];
    foreach ($usersRoutes as $action => $route) {
        $routes->connect($route, ['plugin' => 'Users', 'controller' => 'Users', 'action' => $action]);
    }
    $routes->connect(
        '/:url',
        ['controller' => 'Posts', 'action' => 'view'],
        ['routeClass' => 'PostRoute']
    );
});

Router::scope('/admin', function (RouteBuilder $routes) {
    $routes->redirect(
        '/',
        ['controller' => 'Posts', 'action' => 'index']
    );

    $routes->scope('/posts', ['controller' => 'Posts'], function (RouteBuilder $routes) {
        $routes->connect('/', ['action' => 'index']);
        $routes->fallbacks();
    });
    $routes->connect('/profile', ['controller' => 'Users', 'action' => 'edit']);
});

Plugin::routes();
