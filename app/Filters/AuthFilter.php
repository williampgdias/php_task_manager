<?php

namespace App\Filters;

use App\Models\UserModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $token = $request->getHeaderLine('Authorization');

        if (!$token) {
            return service('response')->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED)->setJSON([
                'status'    => 'error',
                'message'   => 'Token required.',
            ]);
        }

        $token = str_replace('Bearer ', '', $token);

        $userModel = new UserModel();
        $user = $userModel->where('api_token', $token)->first();

        if (!$user) {
            return service('response')->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED)->setJSON([
                'status'    => 'error',
                'message'   => 'Invalid token.',
            ]);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}