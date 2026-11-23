<?php

declare(strict_types=1);

use App\Support\Csrf;

/** @var array<string, mixed> $course */
$id = (int) $course['id'];
?>
<h1><?= e((string) $course['title']) ?></h1>

<dl>
    <dt>Код дисциплины</dt>
    <dd><?= e((string) $course['code']) ?></dd>

    <dt>Количество кредитов</dt>
    <dd><?= (int) $course['credits'] ?></dd>

    <dt>Публикация</dt>
    <dd><?= !empty($course['is_published']) ? 'Опубликована' : 'Черновик' ?></dd>
</dl>

<div class="actions">
    <a href="/courses/<?= $id ?>/edit">Редактировать</a>

    <form method="post" action="/courses/<?= $id ?>/delete" style="display:inline;">
        <input type="hidden" name="_csrf" value="<?= e(Csrf::token()) ?>">
        <button type="submit">Удалить</button>
    </form>

    <a href="/courses">Назад к списку</a>
</div>