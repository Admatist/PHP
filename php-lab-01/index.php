<?php
const UNIVERSITY = "Алматинский технологический университет";
const DISCIPLINE = "Программирование на РНР";

$studentName = "Абдыкапаров Даниял";
$group = "ИС-23-22";
$course = 3;

// Исходные данные (вариант 1: три оценки)
$grade1 = 85;
$grade2 = 90;
$grade3 = 78;

// Вычисления
$result = ($grade1 + $grade2 + $grade3) / 3;

// Определение статуса с помощью if...else
if ($result >= 50) {
    $status = "Успешный результат";
    $statusClass = "success";
} else {
    $status = "Необходимо улучшить результат";
    $statusClass = "warning";
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Лабораторная №1 - Карточка студента</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
            background: #f4f6f8;
        }
        .card {
            padding: 25px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .success { color: green; font-weight: bold; }
        .warning { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <div class="card">
        <h1><?= UNIVERSITY ?></h1>
        <h2><?= DISCIPLINE ?></h2>
        <hr>
        <p><strong>Студент:</strong> <?= $studentName ?></p>
        <p><strong>Группа:</strong> <?= $group ?> | <strong>Курс:</strong> <?= $course ?></p>
        
        <h3>Результаты оценивания:</h3>
        <p>Оценка 1: <?= $grade1 ?></p>
        <p>Оценка 2: <?= $grade2 ?></p>
        <p>Оценка 3: <?= $grade3 ?></p>
        <p><strong>Средний балл:</strong> <?= round($result, 2) ?></p>
        
        <p><strong>Статус:</strong> <span class="<?= $statusClass ?>"><?= $status ?></span></p>
        <p><em>Дата формирования:</em> <?= date("d.m.Y") ?></p>
    </div>
</body>
</html>