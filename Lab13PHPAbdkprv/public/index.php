<?php

declare(strict_types=1);

use App\Controller\CourseController;
use App\Database\ConnectionFactory;
use App\Http\Response;
use App\Repository\CourseRepository;
use App\Routing\Router;
use App\View\Renderer;

require dirname(__DIR__) . '/vendor/autoload.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$templatesPath = dirname(__DIR__) . '/templates';
$renderer = new Renderer($templatesPath);

try {
    $pdo = ConnectionFactory::create();
    $repository = new CourseRepository($pdo);
    $controller = new CourseController($repository, $renderer);

    $router = new Router($renderer);
    require dirname(__DIR__) . '/config/routes.php';

    $requestUri = $_SERVER['REQUEST_URI'] ?? '/';
    $path = parse_url((string) $requestUri, PHP_URL_PATH);
    $normalizedPath = is_string($path) && $path !== '' ? $path : '/';

    if ($normalizedPath !== '/') {
        $normalizedPath = rtrim($normalizedPath, '/');
    }

    $method = (string) ($_SERVER['REQUEST_METHOD'] ?? 'GET');
    $response = $router->dispatch($method, $normalizedPath);
} catch (Throwable $error) {
    error_log($error->__toString());
    $response = Response::html('Внутренняя ошибка сервера', 500);
}

$response->send();