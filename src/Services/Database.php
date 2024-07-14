<?php

namespace Yugo\Services;

use PDO;

interface Database
{
    public function connect(): void;

    public function statement(): PDO;
}