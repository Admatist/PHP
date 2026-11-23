<?php

declare(strict_types=1);

/** @var \App\Routing\Router $router */
/** @var \App\Controller\CourseController $controller */

$router->get('/courses', fn () => $controller->index());
$router->get('/courses/create', fn () => $controller->create());
$router->post('/courses', fn () => $controller->store($_POST));
$router->get('/courses/{id}', fn (string $id) => $controller->show($id));
$router->get('/courses/{id}/edit', fn (string $id) => $controller->edit($id));
$router->post('/courses/{id}/edit', fn (string $id) => $controller->update($id, $_POST));
$router->post('/courses/{id}/delete', fn (string $id) => $controller->destroy($id, $_POST));