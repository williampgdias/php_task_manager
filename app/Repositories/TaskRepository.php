<?php

namespace App\Repositories;

use App\Interfaces\TaskRepositoryInterface;
use App\Models\TaskModel;

class TaskRepository implements TaskRepositoryInterface
{
    protected TaskModel $model;

    public function __construct()
    {
        $this->model = new TaskModel();
    }

    public function findAll(): array
    {
        return $this->model->findAll();
    }

    public function findById(int $id): ?array
    {
        return $this->model->find($id);
    }

    public function create(array $data): array
    {
        $this->model->insert($data);

        return $this->model->find($this->model->getInsertID());
    }

    public function update(int $id, array $data): array
    {
        $this->model->update($id, $data);

        return $this->model->find($id);
    }

    public function delete(int $id): bool
    {
        return $this->model->delete($id);
    }
}