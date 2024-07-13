<?php

namespace Yugo\Http;

use Laminas\Diactoros\ServerRequest as LaminasServerRequest;
use Psr\Http\Message\ServerRequestInterface;

class ServerRequest extends LaminasServerRequest implements ServerRequestInterface
{

}