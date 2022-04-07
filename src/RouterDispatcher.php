<?php

declare(strict_types=1);

namespace Router;

use Closure;
use Exception;

class RouterDispatcher
{

    public static function run(Router $router): void
    {

        $req = self::getRequest();
        $httpMethod = $req['server']['REQUEST_METHOD'];
        $uri = $req['server']['REQUEST_URI'];
        $uriPosParam = strpos($uri, '?');
        $params = [];

        if ($uriPosParam) {
            $uri = substr($uri, 0, $uriPosParam);
            parse_str($req['server']['QUERY_STRING'], $params);
        }

        $params = array_merge($params, $req['post'], $req['files']);

        $route = self::findRoute($router, $httpMethod, $uri);

        if ($route['handler'] instanceof Closure) {
            // call_user_func($route['handler'], $params);
            call_user_func($route['handler']);
        }

        list($class, $method) = explode('::', $route['handler']);

        if (!class_exists($class)) {
            throw new Exception("$class not exists");
        }

        $instanceClass = new $class();

        if (!method_exists($instanceClass, $method)) {
            throw new Exception("$class::$method not exists");
        }

        // $reflectionClass = new ReflectionClass($instanceClass);
        // $reflectionMethod = $reflectionClass->getMethod($method);
        // $reflectionMethodParameters = $reflectionMethod->getParameters();

        // var_dump($reflectionClass, $reflectionMethod, $reflectionMethodParameters); die;

        $instanceClass->$method();
    }

    public static function getRoute(Router $router): array
    {

        $req = self::getRequest();
        $httpMethod = $req['server']['REQUEST_METHOD'];
        $uri = $req['server']['REQUEST_URI'];
        $uriPosParam = strpos($uri, '?');
        $params = [];
        if ($uriPosParam) {
            $uri = substr($uri, 0, $uriPosParam);
            parse_str($req['server']['QUERY_STRING'], $params);
        }

        $route = self::findRoute($router, $httpMethod, $uri);
        $route['params'] = $params;

        return $route;
    }

    private static function findRoute(Router $router, string $httpMethod, string $uri)
    {
        $httpMethod = strtoupper($httpMethod);
        return $router->get($httpMethod, $uri);
    }

    private static function getRequest(): array
    {
        return [
            'get' => $_GET,
            'post' => $_POST,
            'server' => $_SERVER,
            'files' => $_FILES
        ];
    }
}
