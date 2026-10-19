<?php
declare(strict_types=1);

/**
 * Загрузка объявлений из JSON-файла.
 * @return array<int, array>
 */
function get_announcements(): array {
    $path = __DIR__ . '/../var/data/announcements.json';
    if (!file_exists($path)) {
        return [];
    }
    $content = file_get_contents($path);
    if ($content === false) {
        return [];
    }
    $data = json_decode($content, true);
    return is_array($data) ? $data : [];
}

/**
 * Безопасное сохранение массива объявлений в JSON с блокировкой.
 */
function save_announcements(array $announcements): bool {
    $path = __DIR__ . '/../var/data/announcements.json';
    $json = json_encode($announcements, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    return file_put_contents($path, $json, LOCK_EX) !== false;
}

/**
 * Фильтрация объявлений по категории и статусу (активные / архивные).
 * @param array<int, array> $items
 * @return array<int, array>
 */
function filter_announcements(array $items, string $category, string $status): array {
    $now = date('Y-m-d');
    
    return array_filter($items, function(array $item) use ($category, $status, $now): bool {
        $matchesCategory = ($category === '' || $item['category'] === $category);
        $isExpired = $item['expires_at'] < $now;
        
        $matchesStatus = true;
        if ($status === 'active') {
            $matchesStatus = !$isExpired;
        } elseif ($status === 'archive') {
            $matchesStatus = $isExpired;
        }
        
        return $matchesCategory && $matchesStatus;
    });
}

/**
 * Генерация или получение CSRF-токена сессии.
 */
function get_csrf_token(): string {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Проверка CSRF-токена.
 */
function verify_csrf_token(?string $token): bool {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], (string)$token);
}