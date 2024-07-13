<?php

namespace Yugo;

use DI\Container;
use DI\ContainerBuilder;
use Exception;
use Whoops\Handler\JsonResponseHandler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;
use Yugo\Exceptions\RouteException;

final class Framework
{
    public Router $route;

    private Container $container;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->env();

        $this->exceptionPage();

        $this->route = new Router;

        $container = new ContainerBuilder();
        $container->addDefinitions(realpath('../config/binding.php'));
        $this->container = $container->build();
    }

    /**
     * @throws Exception
     */
    public function run(): void
    {
        ini_set('display_errors', getenv('APP_DEBUG'));
        if (getenv('APP_DEBUG')) {
            error_reporting(E_ALL);
        }

        $route = $this->route->find();
        if (!empty($route)) {
            $route->handle($this->container);
        } else {
            throw new RouteException('Route not found.');
        }

    }

    private function env(): void
    {
        $path = realpath('../.env');
        $env = parse_ini_file($path);

        foreach ($env as $key => $value) {
            set_env($key, $value);
        }
    }

    private function exceptionPage(): void
    {
        $accept = getallheaders()['Accept'] ?? null;
        $whoops = new Run();
        if (str_contains($accept, 'json')) {
            $whoops->pushHandler(new JsonResponseHandler());
        } else {
            $whoops->pushHandler(new PrettyPageHandler());
        }

        $whoops->register();
    }
}