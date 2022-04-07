<?php

namespace Router;

use Closure;
use Exception;
use Router\Utils\HttpMethods;

class Router
{

    /** @var array */
    private array $routes = [];

    private ?string $actualGroup = null;

    private array $actualGroupMiddleware = [];

    public function enableCache(string $pathToCache): void
    {
    }

    public function add(string $method, string $endpoint, $handler, array $middleware = []): Router
    {

        $method = strtoupper($method);

        if (false === HttpMethods::isValid($method)) {
            throw new Exception("'$method' is not valid method");
        }

        $endpoint = $this->actualGroup . $endpoint;

        $this->routes[$method][$endpoint]['handler'] = $handler;
        $this->routes[$method][$endpoint]['middleware'] = array_merge($this->actualGroupMiddleware, $middleware);
        return $this;
    }

    public function group(string $groupEndpoint, Closure $callback, array $middleware = []): Router
    {
        $this->openGroup($groupEndpoint, $middleware);
        $callback($this);
        $this->resetGroup();
        return $this;
    }

    private function openGroup(string $groupEndpoint, array $middleware): void
    {
        $this->actualGroup = $groupEndpoint;
        $this->actualGroupMiddleware = $middleware;
    }

    private function resetGroup(): void
    {
        $this->actualGroup = null;
        $this->actualGroupMiddleware = [];
    }

    public function get(string $method, string $endpoint)
    {

        if (!isset($this->routes[$method][$endpoint])) {
            throw new Exception('Route not found');
        }

        return $this->routes[$method][$endpoint];
    }
}
