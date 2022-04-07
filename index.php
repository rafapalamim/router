<?php

use Router\Router;
use Router\RouterDispatcher;
use Test\ClassTest;

require __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

$router = new Router;
// $router->enableCache('');

$router->group('/', function (Router $r) {
    $r->add('GET', 'teste', ClassTest::class . '::index');
});

// $router->add('GET', '/teste', function($params){
//     var_dump($params);
// });

// RouterDispatcher::run($router);
$route = RouterDispatcher::getRoute($router);
var_dump($route);
