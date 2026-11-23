<?php

declare(strict_types=1);

namespace App\Validation;

final class CourseValidator
{
    /**
     * @param array<string, mixed> $input
     * @return array{0: array<string, string>, 1: array{code: string, title: string, credits: int, is_published: int}}
     */
    public static function validate(array $input): array
    {
        $code = trim((string) ($input['code'] ?? ''));
        $title = trim((string) ($input['title'] ?? ''));
        $creditsRaw = trim((string) ($input['credits'] ?? ''));
        $isPublished = isset($input['is_published']) && (string) $input['is_published'] === '1' ? 1 : 0;

        $errors = [];

        if ($code === '') {
            $errors['code'] = 'Укажите код дисциплины.';
        } elseif (mb_strlen($code) > 20) {
            $errors['code'] = 'Код дисциплины не должен превышать 20 символов.';
        }

        if ($title === '') {
            $errors['title'] = 'Укажите название дисциплины.';
        } elseif (mb_strlen($title) > 180) {
            $errors['title'] = 'Название дисциплины не должно превышать 180 символов.';
        }

        $credits = 0;
        if ($creditsRaw === '' || preg_match('/^[0-9]+$/', $creditsRaw) !== 1) {
            $errors['credits'] = 'Количество кредитов должно быть целым числом от 1 до 30.';
        } else {
            $credits = (int) $creditsRaw;
            if ($credits < 1 || $credits > 30) {
                $errors['credits'] = 'Количество кредитов должно быть в диапазоне от 1 до 30.';
            }
        }

        $normalized = [
            'code' => $code,
            'title' => $title,
            'credits' => $credits,
            'is_published' => $isPublished,
        ];

        return [$errors, $normalized];
    }
}