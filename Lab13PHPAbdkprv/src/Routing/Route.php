<?php

declare(strict_types=1);

namespace App\Routing;

use Closure;

final readonly class Route
{
    public function __construct(
        public string $method,
        public string $pattern,
        public Closure $handler,
    ) {}

    public function matchesPath(string $path): bool
    {
        $regex = preg_replace('/\{id\}/', '(?P<id>[1-9][0-9]*)', $this->pattern);

        return preg_match('#^' . $regex . '$#', $path) === 1;
    }

    /**
     * @return array<string, string>|null
     */
    public function parameters(string $method, string $path): ?array
    {
        if ($this->method !== strtoupper($method)) {
            return null;
        }

        $regex = preg_replace('/\{id\}/', '(?P<id>[1-9][0-9]*)', $this->pattern);

        if (preg_match('#^' . $regex . '$#', $path, $matches) !== 1) {
            return null;
        }

        return array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
    }
}