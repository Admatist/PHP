<?php
declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use University\CourseCatalog\Exception\InvalidCourseException;
use University\CourseCatalog\Model\Course;
use University\CourseCatalog\Repository\InMemoryCourseRepository;
use University\CourseCatalog\Service\CatalogService;

function expectException(callable $action, string $expected): void
{
    try {
        $action();
        throw new RuntimeException("Ожидалось исключение {$expected}");
    } catch (Throwable $error) {
        if (! $error instanceof $expected) {
            throw $error;
        }
    }
}

$repository = new InMemoryCourseRepository();
$service = new CatalogService($repository);

$service->register(new Course('PHP1101', 'PHP', 5));

if (count($service->activeCourses()) !== 1) {
    throw new RuntimeException('Неверное количество активных курсов.');
}

if ($repository->findByCode('PHP1101')?->title !== 'PHP') {
    throw new RuntimeException('Курс не найден по коду.');
}

expectException(
    fn () => new Course('bad', 'Курс', 5),
    InvalidCourseException::class,
);

expectException(
    fn () => $service->register(new Course('PHP1101', 'Дубликат', 4)),
    LogicException::class,
);

echo "Все проверки пройдены.\n";