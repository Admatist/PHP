<?php

declare(strict_types=1);

namespace App\Routing;

use App\Http\Response;
use App\View\Renderer;
use Closure;

final class Router
{
    /** @var list<Route> */
    private array $routes = [];

    public function __construct(
        private readonly ?Renderer $renderer = null,
    ) {}

    public function add(string $method, string $pattern, Closure $handler): void
    {
        $this->routes[] = new Route(strtoupper($method), $pattern, $handler);
    }

    public function get(string $pattern, Closure $handler): void
    {
        $this->add('GET', $pattern, $handler);
    }

    public function post(string $pattern, Closure $handler): void
    {
        $this->add('POST', $pattern, $handler);
    }

    public function dispatch(string $method, string $path): Response
    {
        $normalizedMethod = strtoupper($method);
        $allowedMethods = [];

        foreach ($this->routes as $route) {
            $parameters = $route->parameters($normalizedMethod, $path);
            if ($parameters !== null) {
                return ($route->handler)(...$parameters);
            }

            if ($route->matchesPath($path)) {
                $allowedMethods[] = $route->method;
            }
        }

        if ($allowedMethods !== []) {
            $allowedMethods = array_values(array_unique($allowedMethods));
            $allowHeader = implode(', ', $allowedMethods);

            $body = $this->renderer !== null
                ? $this->renderer->render('errors/405', ['allowedMethods' => $allowHeader])
                : 'Метод не разрешён (405)';

            return Response::html($body, 405, ['Allow' => $allowHeader]);
        }

        $body = $this->renderer !== null
            ? $this->renderer->render('errors/404')
            : 'Страница не найдена (404)';

        return Response::html($body, 404);
    }
}