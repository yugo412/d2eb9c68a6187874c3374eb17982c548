<?php

namespace Yugo\Http\Middlewares;

use DI\Container;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface;
use Yugo\Services\Database;

class Auth
{
    private Database $db;

    public function __construct(Container $container)
    {
        $this->db = $container->get(Database::class);
    }

    public function handle(?ServerRequestInterface $request = null): Response|true
    {
        $auth = $request?->getHeaderLine('authorization');
        if (empty($auth) || !str_contains($auth, 'Bearer ')) {
            return $this->unauthenticated();
        }

        [$_, $token] = explode(' ', $auth);
        $query = $this->db->statement()->prepare('SELECT token FROM tokens WHERE token = :token LIMIT 1');
        $query->bindParam(':token', $token);
        $query->execute();
        $tokens = $query->fetchAll(\PDO::FETCH_OBJ);

        return !empty($tokens) ? true : $this->unauthenticated();
    }

    private function unauthenticated(): Response
    {
        return new JsonResponse(['message' => 'Unauthenticated.'], 401);
    }
}