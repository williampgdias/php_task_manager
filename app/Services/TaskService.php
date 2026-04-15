<?php

namespace App\Services;

use App\Interfaces\TaskRepositoryInterface;

class TaskService
{
    protected TaskRepositoryInterface $repository;

    public function __construct(TaskRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getAllTasks(): array
    {
        return $this->repository->findAll();
    }

    public function getTask(int $id): ?array
    {
        return $this->repository->findById($id);
    }

    public function createTask(array $data): array
    {
        if (empty($data['status'])) {
            $data['status'] = 'pending';
        }

        return $this->repository->create($data);
    }

    public function updateTask(int $id, array $data): array
    {
        return $this->repository->update($id, $data);
    }

    public function deleteTask(int $id): bool
    {
        return $this->repository->delete($id);
    }
}