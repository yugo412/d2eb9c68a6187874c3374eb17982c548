<?php

namespace Yugo;

use Closure;
use Exception;
use InvalidArgumentException;
use Yugo\Http\Method;
use Yugo\Http\Route;

/**
 * @method self get(string $class, Closure|array $param) Alias for add(...$args)
 * @method self post(string $class, Closure|array $param) Alias for add(...$args)
 */
class Router
{
    private array $routes = [];

    private array $middlewares = [];

    public function __call(string $name, array $arguments)
    {
        if (!empty($method = Method::tryFrom($name))) {
            $this->add($method, ...$arguments);
        }
    }

    public function middleware(array|string $middleware, Closure $callback): void
    {
        $this->setMiddleware(is_string($middleware) ? [$middleware] : $middleware);

        $callback($this);
    }

    public function add(Method|string $method, string $path, Closure|array $handler): self
    {
        if (is_string($method)) {
            $name = $method;

            $method = Method::tryFrom($method);
            if (empty($method)) {
                throw new InvalidArgumentException(sprintf('Method %s is not supported', $name));
            }
        }

        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler,
            'middleware' => $this->middlewares,
        ];

        return $this;
    }

    /**
     * @throws Exception
     */
    public function match(): ?Route
    {
        foreach ($this->routes as $route) {
            $requestPath = $this->path();
            if ($route['path'] !== '/') {
                $requestPath = rtrim($this->path(), '/');
            }

            if ($route['path'] === $requestPath) {
                if ($route['method'] === Method::Any || $this->method() === $route['method']->value) {
                    return new Route(...$route);
                } else {
                    throw new Exception(vsprintf('Method %s not supported for route "%s"', [
                        strtoupper($this->method()),
                        $route['path'],
                    ]));
                }
            }
        }

        return null;
    }

    private function setMiddleware(array $classes): void
    {
        $this->middlewares = array_merge($this->middlewares, $classes);
    }

    private function method(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    private function path(): string
    {
        return strtolower($_SERVER['REQUEST_URI']);
    }
}