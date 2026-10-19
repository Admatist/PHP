<div class="form-card">
    <h2>Создать новое учебное объявление</h2>
    <form method="POST" action="index.php">
        <input type="hidden" name="post_action" value="create">
        <input type="hidden" name="csrf_token" value="<?= get_csrf_token() ?>">
        
        <div class="form-group">
            <label for="title">Заголовок объявления:</label>
            <input type="text" id="title" name="title" required placeholder="Например: Пересдача лекций">
        </div>
        
        <div class="form-group">
            <label for="category">Категория:</label>
            <select id="category" name="category">
                <option value="Учеба">Учеба</option>
                <option value="Мероприятия">Мероприятия</option>
                <option value="Объявления">Объявления</option>
            </select>
        </div>

        <div class="form-group">
            <label for="expires_at">Срок действия (дата истечения):</label>
            <input type="date" id="expires_at" name="expires_at" required value="<?= date('Y-m-d', strtotime('+7 days')) ?>">
        </div>
        
        <div class="form-group">
            <label for="content">Текст объявления:</label>
            <textarea id="content" name="content" rows="6" required placeholder="Введите подробный текст..."></textarea>
        </div>
        
        <button type="submit" class="btn primary">Опубликовать</button>
    </form>
</div>