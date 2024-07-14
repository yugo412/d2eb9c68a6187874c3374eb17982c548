<?php

namespace Yugo\Logger;

use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Stringable;

class Log implements LoggerInterface
{

    private Logger $log;

    public function __construct()
    {
        $logLevel = Level::fromName(get_env('LOG_LEVEL', 'debug'));

        $this->log = new Logger('log');
        $this->log->pushHandler(new StreamHandler(realpath('../storage/logs/app.log'), $logLevel));
    }

    public function emergency(Stringable|string $message, array $context = []): void
    {
        $this->log->emergency($message, $context);
    }

    public function alert(Stringable|string $message, array $context = []): void
    {
        $this->log->alert($message, $context);
    }

    public function critical(Stringable|string $message, array $context = []): void
    {
        $this->log->critical($message, $context);
    }

    public function error(Stringable|string $message, array $context = []): void
    {
        $this->log->error($message, $context);
    }

    public function warning(Stringable|string $message, array $context = []): void
    {
        $this->log->warning($message, $context);
    }

    public function notice(Stringable|string $message, array $context = []): void
    {
        $this->log->notice($message, $context);
    }

    public function info(Stringable|string $message, array $context = []): void
    {
        $this->log->info($message, $context);
    }

    public function debug(Stringable|string $message, array $context = []): void
    {
        $this->log->debug($message, $context);
    }

    public function log($level, Stringable|string $message, array $context = []): void
    {
        $this->log->log($level, $message, $context);
    }
}