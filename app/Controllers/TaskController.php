<?php

namespace App\Controllers;

use App\Services\TaskService;
use App\Repositories\TaskRepository;
use CodeIgniter\HTTP\ResponseInterface;

class TaskController extends BaseController
{
    protected TaskService $taskService;

    public function __construct()
    {
        $this->taskService = new TaskService(new TaskRepository());
    }

    // GET /tasks
    public function index()
    {
        $userId = $this->request->user['id'];
        $tasks = $this->taskService->getAllTasks($userId);

        return $this->response->setJSON([
            'status'    => 'success',
            'data'      => $tasks,
        ]);
    }

    // GET /tasks/{id}
    public function show($id = null)
    {
        $userId = $this->request->user['id'];
        $task = $this->taskService->getTask($id, $userId);

        if (!$task) {
            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_NOT_FOUND)
                ->setJSON([
                    'status'    => 'error',
                    'message'   => 'Task not found.',
                ]);
        }

        return $this->response
            ->setJSON([
                'status'    => 'success',
                'data'      => $task,
            ]);
    }

    // POST /tasks
    public function create()
    {
        $userId = $this->request->user['id'];
        $data = $this->request->getJSON(true);
        $task = $this->taskService->createTask($data, $userId);

        return $this->response
            ->setStatusCode(ResponseInterface::HTTP_CREATED)
            ->setJSON([
                'status'    => 'success',
                'data'      => $task,
            ]);
    }

    // PUT /tasks/{id}
    public function update($id = null)
    {
        $userId = $this->request->user['id'];
        $task = $this->taskService->getTask($id, $userId);

        if (!$task) {
            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_NOT_FOUND)
                ->setJSON([
                    'status'    => 'error',
                    'message'   => 'Task not found.',
                ]);
        }

        $data = $this->request->getJSON(true);
        $task = $this->taskService->updateTask($id, $data);

        return $this->response
            ->setJSON([
                'status'    => 'success',
                'data'      => $task,
            ]);
    }

    // DELETE /tasks/{id}
    public function delete($id = null)
    {
        $userId = $this->request->user['id'];
        $task = $this->taskService->getTask($id, $userId);

        if (!$task) {
            return $this->response
                ->setStatusCode(ResponseInterface::HTTP_NOT_FOUND)
                ->setJSON([
                    'status'    => 'error',
                    'message'   => 'Task not found.',
                ]);
        }

        $this->taskService->deleteTask($id);

        return $this->response
            ->setJSON([
                'status'    => 'success',
                'message'   => 'Task deleted.',
            ]);
    }

    public function view()
    {
        return view('tasks/index');
    }
}