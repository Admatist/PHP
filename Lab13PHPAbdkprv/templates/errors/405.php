<?php

declare(strict_types=1);

/** @var string|null $allowedMethods */
?>
<h1>405 — Метод не разрешён</h1>
<p>HTTP-метод не поддерживается для данного URI.</p>
<?php if (!empty($allowedMethods)): ?>
    <p>Разрешённые методы: <strong><?= e((string) $allowedMethods) ?></strong></p>
<?php endif; ?>
<p><a href="/courses">Вернуться к списку дисциплин</a></p>