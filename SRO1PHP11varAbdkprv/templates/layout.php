<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <header class="site-header">
        <div class="container header-inner">
            <h1 class="logo"><a href="index.php">📌 Доска объявлений ИТ</a></h1>
            <nav class="main-nav">
                <a href="index.php" class="<?= ($action === 'feed') ? 'active' : '' ?>">Лента</a>
                <a href="index.php?action=create" class="<?= ($action === 'create') ? 'active' : '' ?>">Добавить</a>
                <a href="index.php?action=my" class="<?= ($action === 'my') ? 'active' : '' ?>">Мои записи</a>
            </nav>
        </div>
    </header>

    <main class="container main-content">
        <?php if (!empty($flash)): ?>
            <div class="alert"><?= htmlspecialchars($flash, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>

        <?php 
        if (file_exists($template)) {
            require $template;
        } else {
            echo '<p>Шаблон не найден.</p>';
        } 
        ?>
    </main>

    <footer class="site-footer">
        <div class="container">
            <p>&copy; 2026 СРО по PHP. Вариант 11: Доска объявлений факультета.</p>
        </div>
    </footer>
</body>
</html>