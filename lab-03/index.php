<?php
declare(strict_types=1);

// Исходные данные по Варианту 1 (Оценки студентов)
// Значение -1 завершает обработку, null пропускается, допустимый диапазон: 0-100
$grades = [85, 78, -1, 90, 95];

$total = 0;
$count = 0;
$minimum = 100;
$maximum = 0;

try {
    // Проверка на пустой массив
    if ($grades === []) {
        throw new RuntimeException("Массив оценок пуст");
    }

    foreach ($grades as $index => $grade) {
        // Пропуск элементов со значением null
        if ($grade === null) {
            continue;
        }

        // Прекращение обработки при получении маркера -1
        if ($grade === -1) {
            break;
        }

        // Проверка корректности данных (строгое сравнение и типы)
        if (!is_int($grade) || $grade < 0 || $grade > 100) {
            throw new InvalidArgumentException(
                "Некорректная оценка в позиции $index"
            );
        }

        $total += $grade;
        $count++;

        // Расчет минимума и максимума
        $minimum = min($minimum, $grade);
        $maximum = max($maximum, $grade);
    }

    // Проверка, остались ли данные для расчета после пропусков/маркеров
    if ($count === 0) {
        throw new RuntimeException("Нет данных для расчета");
    }

    // Вычисление среднего значения
    $average = round($total / $count, 2);

    // Классификация результата с помощью match
    $level = match (true) {
        $average >= 90 => "высокий",
        $average >= 75 => "хороший",
        $average >= 50 => "достаточный",
        default => "низкий",
    };

    // HTML-вывод результатов
    echo "<h2>Результат анализа</h2>";
    echo "<p>Количество учтенных оценок: $count</p>";
    echo "<p>Средний балл: $average</p>";
    echo "<p>Минимальный балл: $minimum</p>";
    echo "<p>Максимальный балл: $maximum</p>";
    echo "<p>Уровень успеваемости: $level</p>";

} catch (InvalidArgumentException | RuntimeException $e) {
    // Безопасный вывод сообщения об ошибке
    echo "<p style='color: red;'>Ошибка: " . htmlspecialchars($e->getMessage()) . "</p>";
} finally {
    // Блок, выполняющийся в любом случае
    echo "<p><em>Обработка данных завершена.</em></p>";
}
?>