<?php
declare(strict_types=1);

session_start();
require_once __DIR__ . '/../src/functions.php';

// Симуляция авторизованного пользователя для темы 11
if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = [
        'id' => 101,
        'name' => 'Абдыкапаров Даниял (Студент)',
        'role' => 'student'
    ];
}

$action = $_GET['action'] ?? 'feed';
$announcements = get_announcements();
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

// Обработка POST-запросов (создание, редактирование)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postAction = $_POST['post_action'] ?? '';
    
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
        http_response_code(403);
        exit('Ошибка безопасности: неверный или отсутствующий CSRF-токен.');
    }

    if ($postAction === 'create') {
        $title = trim((string)($_POST['title'] ?? ''));
        $category = trim((string)($_POST['category'] ?? 'Учеба'));
        $content = trim((string)($_POST['content'] ?? ''));
        $expiresAt = trim((string)($_POST['expires_at'] ?? ''));

        if ($title !== '' && $content !== '' && $expiresAt !== '') {
            $newId = count($announcements) > 0 ? max(array_column($announcements, 'id')) + 1 : 1;
            
            $newItem = [
                'id' => $newId,
                'title' => $title,
                'category' => $category,
                'content' => $content,
                'author' => $_SESSION['user']['name'],
                'author_id' => $_SESSION['user']['id'],
                'created_at' => date('Y-m-d'),
                'expires_at' => $expiresAt
            ];
            
            $announcements[] = $newItem;
            save_announcements($announcements);
            
            $_SESSION['flash'] = 'Объявление успешно опубликовано!';
            header('Location: index.php');
            exit;
        } else {
            $_SESSION['flash'] = 'Ошибка: все поля формы обязательны для заполнения.';
        }
    } elseif ($postAction === 'edit') {
        $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
        
        foreach ($announcements as &$item) {
            // Проверка: редактирование доступно только автору записи
            if ($item['id'] === $id && $item['author_id'] === $_SESSION['user']['id']) {
                $item['title'] = trim((string)($_POST['title'] ?? $item['title']));
                $item['category'] = trim((string)($_POST['category'] ?? $item['category']));
                $item['content'] = trim((string)($_POST['content'] ?? $item['content']));
                $item['expires_at'] = trim((string)($_POST['expires_at'] ?? $item['expires_at']));
                
                save_announcements($announcements);
                $_SESSION['flash'] = 'Объявление успешно обновлено.';
                header('Location: index.php?action=my');
                exit;
            }
        }
        unset($item);
        http_response_code(403);
        exit('Доступ запрещен: вы не являетесь автором этого объявления.');
    }
}

// Маршрутизация страниц
$pageTitle = 'Доска объявлений факультета';
$template = '';

switch ($action) {
    case 'feed':
        $category = trim((string)($_GET['category'] ?? ''));
        $status = trim((string)($_GET['status'] ?? 'active'));
        $filtered = filter_announcements($announcements, $category, $status);
        $template = __DIR__ . '/../templates/feed.php';
        break;

    case 'create':
        $pageTitle = 'Создать объявление';
        $template = __DIR__ . '/../templates/create.php';
        break;

    case 'my':
        $pageTitle = 'Мои объявления';
        $myAnnouncements = array_filter($announcements, fn($i) => $i['author_id'] === $_SESSION['user']['id']);
        $template = __DIR__ . '/../templates/my_posts.php';
        break;

    case 'edit':
        $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
        $editItem = null;
        foreach ($announcements as $i) {
            if ($i['id'] === $id && $i['author_id'] === $_SESSION['user']['id']) {
                $editItem = $i;
                break;
            }
        }
        if (!$editItem) {
            http_response_code(403);
            exit('Редактирование недоступно: запись не найдена или чужой автор.');
        }
        $pageTitle = 'Редактировать объявление';
        $template = __DIR__ . '/../templates/edit.php';
        break;

    default:
        http_response_code(404);
        $pageTitle = 'Страница не найдена';
        $template = __DIR__ . '/../templates/404.php';
        break;
}

require_once __DIR__ . '/../templates/layout.php';