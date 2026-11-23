<?php

declare(strict_types=1);

use App\Support\Csrf;

/** @var string $mode */
/** @var array<string, mixed> $course */
/** @var array<string, string> $errors */

$isEdit = $mode === 'edit';
$action = $isEdit
    ? '/courses/' . (int) ($course['id'] ?? 0) . '/edit'
    : '/courses';
$heading = $isEdit ? 'Редактирование дисциплины' : 'Создание дисциплины';
$submitLabel = $isEdit ? 'Сохранить изменения' : 'Создать';
?>
<h1><?= e($heading) ?></h1>

<form method="post" action="<?= e($action) ?>">
    <input type="hidden" name="_csrf" value="<?= e(Csrf::token()) ?>">

    <div class="field">
        <label for="code">Код дисциплины</label>
        <input
            id="code"
            name="code"
            type="text"
            maxlength="20"
            value="<?= e((string) ($course['code'] ?? '')) ?>"
        >
        <?php if (isset($errors['code'])): ?>
            <div class="error-text"><?= e($errors['code']) ?></div>
        <?php endif; ?>
    </div>

    <div class="field">
        <label for="title">Название дисциплины</label>
        <input
            id="title"
            name="title"
            type="text"
            maxlength="180"
            value="<?= e((string) ($course['title'] ?? '')) ?>"
        >
        <?php if (isset($errors['title'])): ?>
            <div class="error-text"><?= e($errors['title']) ?></div>
        <?php endif; ?>
    </div>

    <div class="field">
        <label for="credits">Кредиты (1–30)</label>
        <input
            id="credits"
            name="credits"
            type="number"
            min="1"
            max="30"
            value="<?= e((string) ($course['credits'] ?? '')) ?>"
        >
        <?php if (isset($errors['credits'])): ?>
            <div class="error-text"><?= e($errors['credits']) ?></div>
        <?php endif; ?>
    </div>

    <div class="field">
        <label>
            <input
                type="checkbox"
                name="is_published"
                value="1"
                <?= !empty($course['is_published']) ? 'checked' : '' ?>
            >
            Опубликована
        </label>
    </div>

    <div class="actions">
        <button type="submit"><?= e($submitLabel) ?></button>
        <a href="/courses">Отмена</a>
    </div>
</form>