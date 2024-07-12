<?php

namespace Yugo\Http;

use Laminas\Diactoros\Request as LaminasRequest;
use Psr\Http\Message\RequestInterface;

class Request extends LaminasRequest implements RequestInterface
{

}