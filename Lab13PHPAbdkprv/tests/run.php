<?php

declare(strict_types=1);

use App\Controller\CourseController;
use App\Repository\CourseRepository;
use App\Routing\Router;
use App\Support\Csrf;
use App\View\Renderer;

require dirname(__DIR__) . '/vendor/autoload.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function assertTrue(bool $condition, string $message): void
{
    if (!$condition) {
        fwrite(STDERR, 'FAIL: ' . $message . PHP_EOL);
        exit(1);
    }
}

function assertSame(mixed $expected, mixed $actual, string $message): void
{
    if ($expected !== $actual) {
        fwrite(
            STDERR,
            sprintf('FAIL: %s (expected %s, got %s)%s', $message, var_export($expected, true), var_export($actual, true), PHP_EOL)
        );
        exit(1);
    }
}

$pdo = new PDO('sqlite::memory:', null, null, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

$pdo->exec(
    'CREATE TABLE courses (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        code VARCHAR(20) NOT NULL UNIQUE,
        title VARCHAR(180) NOT NULL,
        credits INTEGER NOT NULL,
        is_published INTEGER NOT NULL DEFAULT 0
    )'
);

$renderer = new Renderer(dirname(__DIR__) . '/templates');
$repository = new CourseRepository($pdo);
$controller = new CourseController($repository, $renderer);

$router = new Router($renderer);
require dirname(__DIR__) . '/config/routes.php';

// 1. Список пуст при старте
$res = $router->dispatch('GET', '/courses');
assertSame(200, $res->status, 'GET /courses returns 200');
assertTrue(str_contains($res->body, 'Список дисциплин пока пуст.'), 'Empty catalog message shown');

// 2. Ошибка CSRF при POST
$badCsrfRes = $router->dispatch('POST', '/courses');
assertSame(403, $badCsrfRes->status, 'POST without CSRF returns 403');

// 3. Создание валидной дисциплины (PRG -> 303)
$_SESSION = [];
$token = Csrf::token();
$_POST = [
    '_csrf' => $token,
    'code' => 'CS101',
    'title' => 'Алгоритмы и структуры данных',
    'credits' => '5',
    'is_published' => '1',
];
$createRes = $router->dispatch('POST', '/courses');
assertSame(303, $createRes->status, 'Valid POST /courses redirects with 303');
assertSame('/courses/1', $createRes->headers['Location'] ?? null, 'Redirects to /courses/1');

// 4. Просмотр созданной записи
$showRes = $router->dispatch('GET', '/courses/1');
assertSame(200, $showRes->status, 'GET /courses/1 returns 200');
assertTrue(str_contains($showRes->body, 'CS101'), 'Course code rendered');
assertTrue(str_contains($showRes->body, 'Алгоритмы и структуры данных'), 'Course title rendered');

// 5. Несуществующий ID -> 404
$notFoundRes = $router->dispatch('GET', '/courses/999');
assertSame(404, $notFoundRes->status, 'GET /courses/999 returns 404');

// 6. Неверный метод -> 405
$methodRes = $router->dispatch('PUT', '/courses');
assertSame(405, $methodRes->status, 'PUT /courses returns 405');

// 7. Обновление записи
$_POST = [
    '_csrf' => $token,
    'code' => 'CS101',
    'title' => 'Алгоритмы и структуры данных (обн.)',
    'credits' => '6',
];
$updateRes = $router->dispatch('POST', '/courses/1/edit');
assertSame(303, $updateRes->status, 'POST /courses/1/edit redirects with 303');

// 8. Удаление записи
$_POST = ['_csrf' => $token];
$deleteRes = $router->dispatch('POST', '/courses/1/delete');
assertSame(303, $deleteRes->status, 'POST /courses/1/delete redirects with 303');

$afterDelete = $router->dispatch('GET', '/courses');
assertTrue(str_contains($afterDelete->body, 'Список дисциплин пока пуст.'), 'Catalog is empty again after delete');

echo 'ALL TESTS PASSED' . PHP_EOL;