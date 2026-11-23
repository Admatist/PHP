<?php

declare(strict_types=1);

namespace App\View;

use RuntimeException;

final readonly class Renderer
{
    public function __construct(private string $basePath) {}

    /**
     * @param array<string, mixed> $data
     */
    public function render(string $template, array $data = []): string
    {
        if (preg_match('/^[a-z0-9_-]+(?:\/[a-z0-9_-]+)*$/', $template) !== 1) {
            throw new RuntimeException('Invalid template name');
        }

        $file = rtrim($this->basePath, '/') . '/' . $template . '.php';
        $layout = rtrim($this->basePath, '/') . '/layout.php';

        if (!is_file($file)) {
            throw new RuntimeException('Template not found: ' . $template);
        }

        if (!is_file($layout)) {
            throw new RuntimeException('Layout not found');
        }

        extract($data, EXTR_SKIP);

        ob_start();
        require $file;
        $content = (string) ob_get_clean();

        ob_start();
        require $layout;

        return (string) ob_get_clean();
    }
}