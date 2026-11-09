<?php
declare(strict_types=1);

namespace University\CourseCatalog\Model;

use University\CourseCatalog\Exception\InvalidCourseException;

final readonly class Course
{
    public function __construct(
        public string $code,
        public string $title,
        public int $credits,
        public bool $active = true,
    ) {
        if (preg_match('/^[A-Z]{2,5}[0-9]{3,4}$/', $this->code) !== 1) {
            throw new InvalidCourseException('Неверный код курса.');
        }
        if (trim($this->title) === '') {
            throw new InvalidCourseException('Название обязательно.');
        }
        if ($this->credits < 1 || $this->credits > 10) {
            throw new InvalidCourseException('Кредиты должны быть от 1 до 10.');
        }
    }
}