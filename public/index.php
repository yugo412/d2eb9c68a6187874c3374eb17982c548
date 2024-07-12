<?php
declare(strict_types=1);

include __DIR__ . '/../vendor/autoload.php';

use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Yugo\Framework;
use Yugo\Handlers\Controllers\MailController;
use Yugo\Http\Method;
use Yugo\Middlewares\Auth;
use Yugo\Router;

$app = new Framework();

$app->route->add(Method::Any, '/', function (Request $request, Response $response) {
    $response->getBody()->write('Hello world!');

    return $response;
});

$app->route->get('/ping', fn(): string => 'Pong!');

$app->route->middleware([Auth::class], function (Router $app): void {
    $app->get('/mail', [MailController::class, 'index']);
    $app->get('/mail/send', [MailController::class, 'send']);
});

$app->run();