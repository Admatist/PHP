<?php

declare(strict_types=1);

namespace App\Controller;

use App\Http\Response;
use App\Repository\CourseRepository;
use App\Support\Csrf;
use App\Support\Flash;
use App\Validation\CourseValidator;
use App\View\Renderer;

final readonly class CourseController
{
    public function __construct(
        private CourseRepository $courses,
        private Renderer $views,
    ) {}

    public function index(): Response
    {
        $items = $this->courses->all();

        return Response::html(
            $this->views->render('courses/index', [
                'title' => 'Каталог дисциплин',
                'courses' => $items,
            ])
        );
    }

    public function show(string $id): Response
    {
        $course = $this->courses->find((int) $id);
        if ($course === null) {
            return Response::html(
                $this->views->render('errors/404', ['title' => 'Дисциплина не найдена']),
                404
            );
        }

        return Response::html(
            $this->views->render('courses/show', [
                'title' => (string) $course['title'],
                'course' => $course,
            ])
        );
    }

    public function create(): Response
    {
        return Response::html(
            $this->views->render('courses/form', [
                'title' => 'Новая дисциплина',
                'mode' => 'create',
                'course' => [
                    'code' => '',
                    'title' => '',
                    'credits' => 5,
                    'is_published' => 0,
                ],
                'errors' => [],
            ])
        );
    }

    /**
     * @param array<string, mixed> $input
     */
    public function store(array $input): Response
    {
        $token = isset($input['_csrf']) && is_string($input['_csrf']) ? $input['_csrf'] : null;
        if (!Csrf::isValid($token)) {
            return Response::html(
                $this->views->render('errors/403', ['title' => 'Ошибка CSRF']),
                403
            );
        }

        [$errors, $data] = CourseValidator::validate($input);
        if ($errors !== []) {
            return Response::html(
                $this->views->render('courses/form', [
                    'title' => 'Новая дисциплина',
                    'mode' => 'create',
                    'course' => $data,
                    'errors' => $errors,
                ]),
                422
            );
        }

        $id = $this->courses->create($data);
        Flash::success('Дисциплина добавлена.');

        return Response::redirect('/courses/' . $id);
    }

    public function edit(string $id): Response
    {
        $course = $this->courses->find((int) $id);
        if ($course === null) {
            return Response::html(
                $this->views->render('errors/404', ['title' => 'Дисциплина не найдена']),
                404
            );
        }

        return Response::html(
            $this->views->render('courses/form', [
                'title' => 'Редактирование дисциплины',
                'mode' => 'edit',
                'course' => $course,
                'errors' => [],
            ])
        );
    }

    /**
     * @param array<string, mixed> $input
     */
    public function update(string $id, array $input): Response
    {
        $token = isset($input['_csrf']) && is_string($input['_csrf']) ? $input['_csrf'] : null;
        if (!Csrf::isValid($token)) {
            return Response::html(
                $this->views->render('errors/403', ['title' => 'Ошибка CSRF']),
                403
            );
        }

        $course = $this->courses->find((int) $id);
        if ($course === null) {
            return Response::html(
                $this->views->render('errors/404', ['title' => 'Дисциплина не найдена']),
                404
            );
        }

        [$errors, $data] = CourseValidator::validate($input);
        if ($errors !== []) {
            $data['id'] = (int) $id;

            return Response::html(
                $this->views->render('courses/form', [
                    'title' => 'Редактирование дисциплины',
                    'mode' => 'edit',
                    'course' => $data,
                    'errors' => $errors,
                ]),
                422
            );
        }

        $this->courses->update((int) $id, $data);
        Flash::success('Дисциплина обновлена.');

        return Response::redirect('/courses/' . (int) $id);
    }

    /**
     * @param array<string, mixed> $input
     */
    public function destroy(string $id, array $input): Response
    {
        $token = isset($input['_csrf']) && is_string($input['_csrf']) ? $input['_csrf'] : null;
        if (!Csrf::isValid($token)) {
            return Response::html(
                $this->views->render('errors/403', ['title' => 'Ошибка CSRF']),
                403
            );
        }

        $course = $this->courses->find((int) $id);
        if ($course === null) {
            return Response::html(
                $this->views->render('errors/404', ['title' => 'Дисциплина не найдена']),
                404
            );
        }

        $this->courses->delete((int) $id);
        Flash::success('Дисциплина удалена.');

        return Response::redirect('/courses');
    }
}