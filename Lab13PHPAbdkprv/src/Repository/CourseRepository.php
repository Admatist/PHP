<?php

declare(strict_types=1);

namespace App\Repository;

use PDO;

final readonly class CourseRepository
{
    public function __construct(private PDO $pdo) {}

    /**
     * @return list<array<string, mixed>>
     */
    public function all(): array
    {
        $stmt = $this->pdo->query(
            'SELECT id, code, title, credits, is_published
             FROM courses
             ORDER BY title ASC, id ASC'
        );

        /** @var list<array<string, mixed>> */
        return $stmt->fetchAll();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, code, title, credits, is_published
             FROM courses
             WHERE id = :id'
        );
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row === false ? null : $row;
    }

    /**
     * @param array{code: string, title: string, credits: int, is_published: int} $data
     */
    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO courses (code, title, credits, is_published)
             VALUES (:code, :title, :credits, :is_published)'
        );
        $stmt->execute([
            'code' => $data['code'],
            'title' => $data['title'],
            'credits' => $data['credits'],
            'is_published' => $data['is_published'],
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * @param array{code: string, title: string, credits: int, is_published: int} $data
     */
    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare(
            'UPDATE courses
             SET code = :code,
                 title = :title,
                 credits = :credits,
                 is_published = :is_published
             WHERE id = :id'
        );

        return $stmt->execute([
            'id' => $id,
            'code' => $data['code'],
            'title' => $data['title'],
            'credits' => $data['credits'],
            'is_published' => $data['is_published'],
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM courses WHERE id = :id');

        return $stmt->execute(['id' => $id]);
    }
}