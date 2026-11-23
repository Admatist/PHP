<?php

declare(strict_types=1);

/** @var list<array<string, mixed>> $courses */
?>
<h1>Дисциплины</h1>

<p><a href="/courses/create">Добавить дисциплину</a></p>

<?php if ($courses === []): ?>
    <p>Список дисциплин пока пуст.</p>
<?php else: ?>
    <?php foreach ($courses as $course): ?>
        <article>
            <div>
                <a href="/courses/<?= (int) $course['id'] ?>">
                    <strong><?= e((string) $course['code']) ?></strong> — <?= e((string) $course['title']) ?>
                </a>
            </div>
            <div>
                <span>Кредиты: <?= (int) $course['credits'] ?></span>
                |
                <span>Статус: <?= !empty($course['is_published']) ? 'Опубликована' : 'Черновик' ?></span>
            </div>
        </article>
    <?php endforeach; ?>
<?php endif; ?>