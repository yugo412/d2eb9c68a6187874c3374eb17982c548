<?php
declare(strict_types=1);

include __DIR__ . '/../vendor/autoload.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Yugo\Framework;
use Yugo\Handlers\Controllers\AuthController;
use Yugo\Handlers\Controllers\MailController;
use Yugo\Http\Method;
use Yugo\Http\Middlewares\Auth;
use Yugo\Router;

$app = new Framework();

$app->route->add(Method::Any, '/', function (Request $request, Response $response) {
    $response->getBody()->write('Hello world!');

    return $response;
});

$app->route->get('/ping', fn(): string => 'Pong!');

$app->route->post('/auth', [AuthController::class, 'register']);

$app->route->middleware([Auth::class], function (Router $app): void {
    $app->match([Method::Post, Method::Get], '/mail', [MailController::class, 'index']);
    $app->post('/mail/send', [MailController::class, 'send']);
});

$app->run();