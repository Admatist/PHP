<?php
declare(strict_types=1);

namespace University\CourseCatalog\Contract;

use University\CourseCatalog\Model\Course;

interface CourseRepositoryInterface
{
    public function add(Course $course): void;

    /** @return list<Course> */
    public function all(): array;

    public function findByCode(string $code): ?Course;
}