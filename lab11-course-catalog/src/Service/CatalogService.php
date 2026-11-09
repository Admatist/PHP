<?php
declare(strict_types=1);

namespace University\CourseCatalog\Service;

use University\CourseCatalog\Contract\CourseRepositoryInterface;
use University\CourseCatalog\Model\Course;

final readonly class CatalogService
{
    public function __construct(
        private CourseRepositoryInterface $repository
    ) {}

    public function register(Course $course): void
    {
        if ($this->repository->findByCode($course->code) !== null) {
            throw new \LogicException('Курс с таким кодом уже существует.');
        }
        $this->repository->add($course);
    }

    /** @return list<Course> */
    public function activeCourses(): array
    {
        return array_values(array_filter(
            $this->repository->all(),
            static fn (Course $course): bool => $course->active,
        ));
    }
}