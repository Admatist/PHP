<div class="form-card">
    <h2>Редактировать объявление</h2>
    <form method="POST" action="index.php">
        <input type="hidden" name="post_action" value="edit">
        <input type="hidden" name="id" value="<?= $editItem['id'] ?>">
        <input type="hidden" name="csrf_token" value="<?= get_csrf_token() ?>">
        
        <div class="form-group">
            <label for="title">Заголовок:</label>
            <input type="text" id="title" name="title" required value="<?= htmlspecialchars($editItem['title'], ENT_QUOTES, 'UTF-8') ?>">
        </div>
        
        <div class="form-group">
            <label for="category">Категория:</label>
            <select id="category" name="category">
                <option value="Учеба" <?= $editItem['category'] === 'Учеба' ? 'selected' : '' ?>>Учеба</option>
                <option value="Мероприятия" <?= $editItem['category'] === 'Мероприятия' ? 'selected' : '' ?>>Мероприятия</option>
                <option value="Объявления" <?= $editItem['category'] === 'Объявления' ? 'selected' : '' ?>>Объявления</option>
            </select>
        </div>

        <div class="form-group">
            <label for="expires_at">Срок действия:</label>
            <input type="date" id="expires_at" name="expires_at" required value="<?= htmlspecialchars($editItem['expires_at'], ENT_QUOTES, 'UTF-8') ?>">
        </div>
        
        <div class="form-group">
            <label for="content">Текст:</label>
            <textarea id="content" name="content" rows="6" required><?= htmlspecialchars($editItem['content'], ENT_QUOTES, 'UTF-8') ?></textarea>
        </div>
        
        <button type="submit" class="btn primary">Сохранить изменения</button>
    </form>
</div>