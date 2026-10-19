<section class="my-posts">
    <h2>Мои объявления</h2>
    <?php if (empty($myAnnouncements)): ?>
        <p class="no-results">Вы еще не опубликовали ни одного объявления.</p>
    <?php else: ?>
        <div class="posts-grid">
            <?php foreach ($myAnnouncements as $item): ?>
                <div class="post-card">
                    <div class="post-header">
                        <span class="badge"><?= htmlspecialchars($item['category'], ENT_QUOTES, 'UTF-8') ?></span>
                        <span class="date">До: <?= htmlspecialchars($item['expires_at'], ENT_QUOTES, 'UTF-8') ?></span>
                    </div>
                    <h3><?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                    <p class="content"><?= nl2br(htmlspecialchars($item['content'], ENT_QUOTES, 'UTF-8')) ?></p>
                    <div class="post-footer-actions" style="margin-top: 15px;">
                        <a href="index.php?action=edit&id=<?= $item['id'] ?>" class="btn secondary">Редактировать</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>