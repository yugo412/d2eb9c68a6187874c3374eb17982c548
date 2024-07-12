<?php

namespace Yugo\Http;

enum Method: string
{
    case Get = 'get';

    case Post = 'post';

    case Put = 'put';

    case Delete = 'delete';

    case Any = '*';
}