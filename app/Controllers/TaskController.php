<?php

namespace App\Controllers;

use App\Models\TaskModel;
use CodeIgniter\HTTP\ResponseInterface;

class TaskController extends BaseController
{
    protected TaskModel $taskModel;

    public function __construct()
    {
        $this->taskModel = new TaskModel();
    }

    // GET /tasks
    public function index()
    {
        $tasks = $this->taskModel->findAll();

        return $this->response->setJSON([
            'status'    => 'success',
            'data'      => $tasks,
        ]);
    }

    // GET /tasks/{id}
    public function show($id = null)
    {
        $task = $this->taskModel->find($id);

        if (!$task) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_NOT_FOUND)->setJSON([
                'status'    => 'error',
                'message'   => 'Task not found.',
            ]);
        }

        return $this->response->setJSON([
            'status'    => 'success',
            'data'      => $task,
        ]);
    }

    // POST /tasks
    public function create()
    {
        $data = $this->request->getJSON(true);

        if (!$this->taskModel->insert($data)) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)->setJSON([
                'status'    => 'error',
                'errors'    => $this->taskModel->errors(),
            ]);
        }

        $task = $this->taskModel->find($this->taskModel->getInsertID());

        return $this->response->setStatusCode(ResponseInterface::HTTP_CREATED)->setJSON([
            'status'    => 'success',
            'data'      => $task,
        ]);
    }

    // PUT /tasks/{id}
    public function update($id = null)
    {
        $task = $this->taskModel->find($id);

        if (!$task) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_NOT_FOUND)->setJSON([
                'status'    => 'error',
                'message'   => 'Task not found.',
            ]);
        }

        $data = $this->request->getJSON(true);

        if (!$this->taskModel->update($id, $data)) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)->setJSON([
                'status'    => 'error',
                'errors'    => $this->taskModel->errors(),
            ]);
        }

        $task = $this->taskModel->find($id);

        return $this->response->setJSON([
            'status'    => 'success',
            'data'      => $task,
        ]);
    }

    // DELETE /tasks/{id}
    public function delete($id = null)
    {
        $task = $this->taskModel->find($id);

        if (!$task) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_NOT_FOUND)->setJSON([
                'status'    => 'error',
                'message'   => 'Task not found.',
            ]);
        }

        $this->taskModel->delete($id);

        return $this->response->setJSON([
            'status'    => 'success',
            'message'   => 'Task deleted.',
        ]);
    }
}