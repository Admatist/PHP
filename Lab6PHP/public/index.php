<?php
declare(strict_types=1);

/**
 * Функция безопасного экранирования спецсимволов для вывода в HTML.
 */
function h(string $value): string
{
    return htmlspecialchars(
        $value,
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );
}

/**
 * Безопасное извлечение строкового поля из $_POST.
 * Возвращает string если передана строка, иначе null (если поле отсутствует или передано массивом).
 */
function postString(string $key): ?string
{
    $value = $_POST[$key] ?? null;
    return is_string($value) ? $value : null;
}

// Разрешённые списки (Allow-lists)
$allowedFaculties = [
    'Факультет информационных технологий',
    'Инженерно-технический факультет',
    'Экономический факультет',
    'Гуманитарный факультет'
];

$allowedCategories = [
    'student'   => 'Студент',
    'magistrand'=> 'Магистрант',
    'teacher'   => 'Преподаватель',
    'researcher'=> 'Исследователь'
];

// Начальные значения и массив ошибок
$values = [
    'full_name' => '',
    'email'     => '',
    'faculty'   => '',
    'category'  => '',
];

$errors = [];
$success = false;

// Проверяем метод HTTP-запроса
if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {

    // 1. Извлечение строк из $_POST
    $fullNameRaw = postString('full_name');
    $emailRaw    = postString('email');
    $facultyRaw  = postString('faculty');
    $categoryRaw = postString('category');

    // 2. Нормализация данных
    $values['full_name'] = $fullNameRaw === null ? '' : trim($fullNameRaw);
    $values['email']     = $emailRaw === null ? '' : trim(mb_strtolower($emailRaw));
    $values['faculty']   = $facultyRaw ?? '';
    $values['category']  = $categoryRaw ?? '';

    // 3. Серверная валидация

    // Проверка ФИО (от 3 до 100 символов)
    if ($fullNameRaw === null || $values['full_name'] === '') {
        $errors['full_name'] = 'Укажите ФИО.';
    } elseif (mb_strlen($values['full_name']) < 3 || mb_strlen($values['full_name']) > 100) {
        $errors['full_name'] = 'ФИО должно содержать от 3 до 100 символов.';
    }

    // Проверка E-mail
    if ($emailRaw === null || $values['email'] === '') {
        $errors['email'] = 'Укажите электронную почту.';
    } elseif (filter_var($values['email'], FILTER_VALIDATE_EMAIL) === false) {
        $errors['email'] = 'Введите корректный адрес электронной почты.';
    }

    // Проверка Факультета (Allow-list)
    if (!in_array($values['faculty'], $allowedFaculties, true)) {
        $errors['faculty'] = 'Выберите факультет из списка.';
    }

    // Проверка Категории (Allow-list)
    if (!array_key_exists($values['category'], $allowedCategories)) {
        $errors['category'] = 'Выберите категорию читателя.';
    }

    // Проверка Согласия с правилами (Checkbox)
    if (!isset($_POST['agreement']) || $_POST['agreement'] !== '1') {
        $errors['agreement'] = 'Подтвердите согласие с правилами библиотеки.';
    }

    // Если массив ошибок пуст — форма обработана успешно
    $success = ($errors === []);
}
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Заявка на читательский билет</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 2rem; background-color: #f4f6f9; color: #333; }
        main { max-width: 550px; background: #fff; padding: 25px 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); margin: 0 auto; }
        h1 { font-size: 1.5rem; margin-bottom: 1.5rem; color: #1a252f; text-align: center; }
        .form-group { margin-bottom: 1.2rem; }
        label { display: block; margin-bottom: .4rem; font-weight: 600; }
        input[type="text"], input[type="email"], select { width: 100%; padding: 9px 12px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .error-message { color: #d32f2f; font-size: 0.85rem; margin-top: 0.3rem; }
        .success-message { color: #155724; background-color: #d4edda; border: 1px solid #c3e6cb; padding: 15px; border-radius: 4px; }
        fieldset { border: 1px solid #ccc; border-radius: 4px; padding: 10px 15px; margin-bottom: 1.2rem; }
        button { width: 100%; background-color: #0066cc; color: #fff; border: none; padding: 11px; border-radius: 4px; font-size: 1rem; font-weight: bold; cursor: pointer; }
        button:hover { background-color: #0052a3; }
    </style>
</head>
<body>
<main>
    <h1>Заявка на читательский билет</h1>

    <?php if ($success): ?>
        <div class="success-message">
            <h2>Заявка успешно оформлена!</h2>
            <p><strong>ФИО:</strong> <?= h($values['full_name']) ?></p>
            <p><strong>E-mail:</strong> <?= h($values['email']) ?></p>
            <p><strong>Факультет:</strong> <?= h($values['faculty']) ?></p>
            <p><strong>Категория:</strong> <?= h($allowedCategories[$values['category']]) ?></p>
        </div>
    <?php else: ?>
        <form method="post" action="" novalidate>
            
            <div class="form-group">
                <label for="full_name">ФИО</label>
                <input type="text" id="full_name" name="full_name" value="<?= h($values['full_name']) ?>" required>
                <?php if (isset($errors['full_name'])): ?>
                    <div class="error-message"><?= h($errors['full_name']) ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" value="<?= h($values['email']) ?>" required>
                <?php if (isset($errors['email'])): ?>
                    <div class="error-message"><?= h($errors['email']) ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="faculty">Факультет</label>
                <select id="faculty" name="faculty" required>
                    <option value="">-- Выберите факультет --</option>
                    <?php foreach ($allowedFaculties as $item): ?>
                        <option value="<?= h($item) ?>" <?= $values['faculty'] === $item ? 'selected' : '' ?>>
                            <?= h($item) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['faculty'])): ?>
                    <div class="error-message"><?= h($errors['faculty']) ?></div>
                <?php endif; ?>
            </div>

            <fieldset>
                <legend><st rong>Категория читателя</strong></legend>
                <?php forea ch ($allowedCategories as $key => $label): ?>
                    <label style="font-weight: normal; margin-bottom: 0.3rem;">
                        <input type="radio" name="category" value="<?= h($key) ?>" <?= $values['category'] === $key ? 'checked' : '' ?>>
                        <?= h($label) ?>
                    </label>
                <?php endforeach; ?>
                <?php if (isset($errors['category'])): ?>
                    <div class="error-message"><?= h($errors['category']) ?></div>
                <?php endif; ?>
            </fieldset>

            <div class="form-group">
                <label style="font-weight: normal;">
                    <input type="checkbox" name="agreement" value="1">
                    Я согласен с правилами пользования библиотекой
                </label>
                <?php if (isset($errors['agreement'])): ?>
                    <div class="error-message"><?= h($errors['agreement']) ?></div>
                <?php endif; ?>
            </div>

            <button type="submit">Отправить заявку</button>
        </form>
    <?php endif; ?>
</main>
</body>
</html>