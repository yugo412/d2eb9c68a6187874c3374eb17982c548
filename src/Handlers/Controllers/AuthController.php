<?php

namespace Yugo\Handlers\Controllers;

use DI\Container;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Yugo\Services\Database;

class AuthController
{
    private Database $db;

    public function __construct(Container $container)
    {
        $this->db = $container->get(Database::class);
    }

    public function register(): Response
    {
        $randomString = function (int $length = 10): string {
            return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)))), 1, $length);
        };

        $this->db->statement()->prepare('INSERT INTO tokens (token) VALUES (:token)')
            ->execute(['token' => $token = $randomString(60)]);

        return new JsonResponse(
            [
            'token' => $token,
            ], 201
        );
    }
}