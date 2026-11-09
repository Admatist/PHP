<?php
declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use University\CourseCatalog\Model\Course;
use University\CourseCatalog\Repository\InMemoryCourseRepository;
use University\CourseCatalog\Service\CatalogService;

$service = new CatalogService(new InMemoryCourseRepository());

$service->register(new Course('PHP1101', 'Программирование на PHP', 5));
$service->register(new Course('DB1201', 'Базы данных', 5));
$service->register(new Course('WEB1301', 'Web-разработка', 4, false));

foreach ($service->activeCourses() as $course) {
    printf("%s | %s | %d кредитов\n", $course->code, $course->title, $course->credits);
}