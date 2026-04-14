<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class AuthController extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    // POST /api/register
    public function register()
    {
        $data = $this->request->getJSON(true);

        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        if (!$this->userModel->insert($data)) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST)->setJSON([
                'status'    => 'error',
                'errors'    => $this->userModel->errors(),
            ]);
        }

        $user = $this->userModel->find($this->userModel->getInsertID());
        unset($user['password']);

        return $this->response->setStatusCode(ResponseInterface::HTTP_CREATED)->setJSON([
            'status'    => 'success',
            'data'      => $user,
        ]);
    }

    // POST /api/login
    public function login()
    {
        $data = $this->request->getJSON(true);

        $user = $this->userModel->where('email', $data['email'])->first();

        if (!$user || !password_verify($data['password'], $user['password'])) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED)->setJSON([
                'status'    => 'error',
                'message'   => 'Invalid email or password.',
            ]);
        }

        $token = bin2hex(random_bytes(32));

        $this->userModel->update($user['id'], ['api_token' => $token]);

        unset($user['password']);
        $user['api_token'] = $token;

        return $this->response->setJSON([
            'status'    => 'success',
            'data'      => $user,
        ]);
    }

    public function index()
    {
        //
    }
}