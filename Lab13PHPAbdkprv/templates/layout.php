<?php

declare(strict_types=1);

use App\Support\Flash;

if (!function_exists('e')) {
    function e(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

$flashMessages = Flash::consume();
$pageTitle = isset($title) && is_string($title) ? $title : 'Каталог дисциплин';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?></title>
    <style>
        body { font-family: system-ui, -apple-system, sans-serif; max-width: 860px; margin: 2rem auto; padding: 0 1rem; line-height: 1.5; }
        nav { display: flex; gap: 1rem; margin-bottom: 1.5rem; padding-bottom: 0.75rem; border-bottom: 1px solid #ddd; }
        .flash-success { background: #e8f5e9; border: 1px solid #81c784; color: #1b5e20; padding: 0.75rem 1rem; margin-bottom: 1rem; border-radius: 4px; }
        .error-text { color: #b71c1c; font-size: 0.9rem; }
        .field { margin-bottom: 1rem; }
        label { display: block; font-weight: 600; margin-bottom: 0.25rem; }
        input[type="text"], input[type="number"] { width: 100%; max-width: 420px; padding: 0.45rem; }
        article { border: 1px solid #e0e0e0; padding: 0.75rem 1rem; margin-bottom: 0.75rem; border-radius: 4px; }
        .actions { display: flex; gap: 0.5rem; align-items: center; margin-top: 1rem; }
    </style>
</head>
<body>
    <header>
        <nav>
            <a href="/courses">Все дисциплины</a>
            <a href="/courses/create">Добавить дисциплину</a>
        </nav>
    </header>

    <main>
        <?php foreach (($flashMessages['success'] ?? []) as $msg): ?>
            <div class="flash-success"><?= e($msg) ?></div>
        <?php endforeach; ?>

        <?= $content ?? '' ?>
    </main>
</body>
</html>