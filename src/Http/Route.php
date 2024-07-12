<?php

namespace Yugo\Http;

use Closure;
use DI\Container;
use DI\DependencyException;
use DI\NotFoundException;
use Exception;
use Psr\Http\Message\ResponseInterface;
use ReflectionFunction;
use ReflectionMethod;

readonly class Route
{
    public function __construct(
        private readonly Method        $method,
        private readonly string        $path,
        private readonly Closure|array $handler,
        private readonly array        $middleware,
    )
    {
    }

    /**
     * @throws Exception
     */
    public function handle(Container $container): void
    {
        $classes = [];

        if (is_callable($this->handler)) {
            $response = ($this->handler)(
                ...$this->bind(new ReflectionFunction($this->handler), $container),
            );
        } else {
            $response = (new $this->handler[0])->{$this->handler[1]}(
                ...$this->bind(new ReflectionMethod($this->handler[0], $this->handler[1]), $container)
            );
        }

        if ($response instanceof ResponseInterface) {
            foreach ($this->middleware as $middleware) {
                if (!method_exists($middleware, 'handle')) {
                    throw new Exception(sprintf('Method handle() in %s does not exists', $middleware));
                }

                $response = (new $middleware)->handle(clone $response);
            }
        }

        $this->sendResponse($response);
    }

    private function method(): string
    {
        return $this->method->value;
    }

    private function path(): string
    {
        return $this->path;
    }

    /**
     * @throws DependencyException
     * @throws NotFoundException
     */
    private function bind(ReflectionFunction|ReflectionMethod $reflection, Container $container): array
    {
        $classes = [];
        /**
         * @throws Exception
         */
        $hasClass = function (string $class) use ($container): void {
            if (!$container->has($class)) {
                throw new Exception(sprintf('Class %s is not registered', $class));
            }
        };

        foreach ($reflection->getParameters() as $parameter) {
            $class = $parameter->getType()->getName();
            $hasClass($class);

            $classes[] = $container->get($class);
        }

        return $classes;
    }

    private function writeHeader(array $headers): void
    {
        foreach ($headers as $name => $value) {
            header(sprintf('%s: %s', $name, implode(',', $value)));
        }
    }

    private function sendResponse(mixed $response): void
    {
        if ($response instanceof ResponseInterface) {
            http_response_code($response->getStatusCode());

            $response = $response->withHeader('Cache-Control', 'max-age=3600');
            $response = $response->withHeader('ETag', md5($response->getBody()));

            $this->writeHeader($response->getHeaders());

            echo $response->getBody();
        } elseif (is_string($response)) {
            echo $response;
        }
    }
}