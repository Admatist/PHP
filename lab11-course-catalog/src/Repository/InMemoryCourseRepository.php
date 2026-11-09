<?php
declare(strict_types=1);

namespace University\CourseCatalog\Repository;

use University\CourseCatalog\Contract\CourseRepositoryInterface;
use University\CourseCatalog\Model\Course;

final class InMemoryCourseRepository implements CourseRepositoryInterface
{
    /** @var array<string, Course> */
    private array $items = [];

    public function add(Course $course): void
    {
        $this->items[$course->code] = $course;
    }

    /** @return list<Course> */
    public function all(): array
    {
        return array_values($this->items);
    }

    public function findByCode(string $code): ?Course
    {
        return $this->items[$code] ?? null;
    }
}