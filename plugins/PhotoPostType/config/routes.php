<?php
use ADmad\Glide\Middleware\GlideMiddleware;
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

Router::scope('/images', function ($routes) {
    $routes->registerMiddleware('glide', new GlideMiddleware([
        // Run this filter only for URLs starting with specified string. Default null.
        // Setting this option is required only if you want to setup the middleware
        // in Application::middleware() instead of using router's scoped middleware.
        // It would normally be set to same value as that of server.base_url below.
        'path' => '/images/',

        // Either a callable which returns an instance of League\Glide\Server
        // or config array to be used to create server instance.
        // http://glide.thephpleague.com/1.0/config/setup/
        'server' => [
            // Path or League\Flysystem adapter instance to read images from.
            // http://glide.thephpleague.com/1.0/config/source-and-cache/
            'source' => WWW_ROOT . 'files' . DS . 'Posts',

            // Path or League\Flysystem adapter instance to write cached images to.
            'cache' => WWW_ROOT . 'cache_img' . DS . 'eli',

            // URL part to be omitted from source path. Defaults to "/images/"
            // http://glide.thephpleague.com/1.0/config/source-and-cache/#set-a-base-url
            'base_url' => '/images/',

            // Response class for serving images. If unset (default) an instance of
            // \ADmad\Glide\Responses\PsrResponseFactory() will be used.
            // http://glide.thephpleague.com/1.0/config/responses/
            'response' => null,
        ],

        // http://glide.thephpleague.com/1.0/config/security/
        'security' => [
            // Boolean indicating whether secure URLs should be used to prevent URL
            // parameter manipulation. Default false.
            'secureUrls' => (bool)Configure::read('EliTheme.secureUrls', true),

            // Signing key used to generate / validate URLs if `secureUrls` is `true`.
            // If unset value of Cake\Utility\Security::salt() will be used.
            'signKey' => Configure::read('EliTheme.signKey', '1234'),
        ],

        // Cache duration. Default '+1 days'.
        'cacheTime' => '+1 days',

        // Any response headers you may want to set. Default null.
        'headers' => [
            'X-Custom' => 'some-value',
        ]
    ]));

    $routes->applyMiddleware('glide');

    $routes->connect('/*');
});
