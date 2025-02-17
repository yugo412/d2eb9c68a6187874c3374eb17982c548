<?php

namespace Yugo\Http;

use Closure;
use DI\Container;
use DI\DependencyException;
use DI\NotFoundException;
use Exception;
use Laminas\Diactoros\Response\TextResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ReflectionFunction;
use ReflectionMethod;

class Route
{
    public function __construct(
        private readonly Method        $method,
        private readonly string        $path,
        private readonly Closure|array $handler,
        private array                  $middleware,
    )
    {
    }

    /**
     * @throws Exception
     */
    public function handle(Container $container): void
    {
        if (is_callable($this->handler)) {
            $response = ($this->handler)(
                ...$this->bind(new ReflectionFunction($this->handler), $container),
            );
        } else {
            $controller = new $this->handler[0]($container);
            if (!empty($controller->middlewares)) {
                $this->middleware = array_merge($this->middleware, $controller->middlewares);
            }

            $arguments = $this->bind(new ReflectionMethod($this->handler[0], $this->handler[1]), $container);
            foreach ($arguments as $argument) {
                if ($argument instanceof ServerRequestInterface) {
                    $request = $argument;

                    break;
                }
            }

            foreach (array_unique($this->middleware) as $middleware) {
                if (!method_exists($middleware, 'handle')) {
                    throw new Exception(sprintf('Method handle() in %s does not exists', $middleware));
                }

                $middlewareResponse = (new $middleware($container))->handle($request ?? null);
                if ($middlewareResponse instanceof ResponseInterface) {
                    $this->sendResponse($middlewareResponse);
                }
            }

            $response = $controller->{$this->handler[1]}(...$arguments);
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
                throw new Exception(sprintf('Class %s is not registered.', $class));
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
        if (is_string($response)) {
            $response = new TextResponse($response);
        }

        http_response_code($response->getStatusCode());

        $response = $response->withHeader('Cache-Control', 'max-age=3600');
        $response = $response->withHeader('ETag', md5($response->getBody()));

        $this->writeHeader($response->getHeaders());

        echo $response->getBody();
        exit;
    }
}