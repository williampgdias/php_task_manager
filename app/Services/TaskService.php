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

    public function getAllTasks(int $userId): array
    {
        return $this->repository->findAll($userId);
    }

    public function getTask(int $id, int $userId): ?array
    {
        return $this->repository->findById($id, $userId);
    }

    public function createTask(array $data, int $userID): array
    {
        $data['user_id'] = $userID;

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