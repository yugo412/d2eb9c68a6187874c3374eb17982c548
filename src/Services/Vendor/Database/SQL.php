<?php

namespace Yugo\Services\Vendor\Database;

use PDO;
use Yugo\Services\Database;

class SQL implements Database
{

    private PDO $db;

    public function __construct()
    {
        $this->connect();
    }

    public function connect(): void
    {
        $this->db = new PDO(
            vsprintf(
                '%s:host=%s;port=%s;dbname=%s', [
                get_env('DB_DRIVER'),
                get_env('DB_HOST'),
                get_env('DB_PORT', 5432),
                get_env('DB_NAME'),
                ]
            ), get_env('DB_USERNAME'), get_env('DB_PASSWORD')
        );

        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function statement(): PDO
    {
        return $this->db;
    }
}