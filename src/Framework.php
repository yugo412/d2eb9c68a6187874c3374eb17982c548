<?php

namespace Yugo;

use DI\Container;
use DI\ContainerBuilder;
use Exception;

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

        $this->route = new Router;

        $container = new ContainerBuilder();
        $container->addDefinitions(realpath('../config/binding.php'));
        $this->container = $container->build();
    }

    private function env(): void
    {
        $path = realpath('../.env');
        $env = parse_ini_file($path);

        foreach ($env as $key => $value) {
            putenv("{$key}={$value}");
        }
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

        $route = $this->route->match();
        if (!empty($route)) {
            $route->handle($this->container);
        } else {
            throw new Exception('Route not found');
        }

    }
}