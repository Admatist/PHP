<section class="feed-section">
    <div class="filter-card">
        <form method="GET" action="index.php" class="filter-form">
            <input type="hidden" name="action" value="feed">
            <div class="form-group">
                <label for="category">Категория:</label>
                <select name="category" id="category">
                    <option value="">Все категории</option>
                    <option value="Учеба" <?= (($_GET['category'] ?? '') === 'Учеба') ? 'selected' : '' ?>>Учеба</option>
                    <option value="Мероприятия" <?= (($_GET['category'] ?? '') === 'Мероприятия') ? 'selected' : '' ?>>Мероприятия</option>
                    <option value="Объявления" <?= (($_GET['category'] ?? '') === 'Объявления') ? 'selected' : '' ?>>Объявления</option>
                </select>
            </div>
            <div class="form-group">
                <label for="status">Статус:</label>
                <select name="status" id="status">
                    <option value="active" <?= (($_GET['status'] ?? 'active') === 'active') ? 'selected' : '' ?>>Активные</option>
                    <option value="archive" <?= (($_GET['status'] ?? '') === 'archive') ? 'selected' : '' ?>>Архивные</option>
                    <option value="all" <?= (($_GET['status'] ?? '') === 'all') ? 'selected' : '' ?>>Все</option>
                </select>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn primary">Фильтровать</button>
                <a href="index.php" class="btn secondary">Сбросить</a>
            </div>
        </form>
    </div>

    <div class="posts-grid">
        <?php if (empty($filtered)): ?>
            <p class="no-results">Объявлений по заданным критериям не найдено.</p>
        <?php else: ?>
            <?php foreach ($filtered as $item): ?>
                <div class="post-card">
                    <div class="post-header">
                        <span class="badge"><?= htmlspecialchars($item['category'], ENT_QUOTES, 'UTF-8') ?></span>
                        <span class="date">До: <?= htmlspecialchars($item['expires_at'], ENT_QUOTES, 'UTF-8') ?></span>
                    </div>
                    <h3><?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                    <p class="content"><?= nl2br(htmlspecialchars($item['content'], ENT_QUOTES, 'UTF-8')) ?></p>
                    <div class="post-footer">
                        <small>Автор: <?= htmlspecialchars($item['author'], ENT_QUOTES, 'UTF-8') ?></small>
                        <small>Опубликовано: <?= htmlspecialchars($item['created_at'], ENT_QUOTES, 'UTF-8') ?></small>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>