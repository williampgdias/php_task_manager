<?php

namespace App\Interfaces;

interface TaskRepositoryInterface
{
    public function findAll(int|null $userId = null): array;
    public function findById(int $id, int|null $userId = null): ?array;
    public function create(array $data): array;
    public function update(int $id, array $data): array;
    public function delete(int $id): bool;
}