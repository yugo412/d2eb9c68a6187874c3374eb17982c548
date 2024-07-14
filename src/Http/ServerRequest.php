<?php

namespace Yugo\Http;

use Laminas\Diactoros\ServerRequest as LaminasServerRequest;
use Psr\Http\Message\ServerRequestInterface;

class ServerRequest extends LaminasServerRequest implements ServerRequestInterface
{
    private array $normalizedHeaders = [];

    public function getHeaders(): array
    {
        $this->headers = getallheaders();

        foreach ($this->headers as $name => $value) {
            $this->normalizedHeaders[strtolower($name)] = $value;
        }

        return $this->headers;
    }

    public function getHeaderLine(string $name): string
    {
        $this->getHeaders();

        return $this->normalizedHeaders[strtolower($name)] ?? '';
    }
}