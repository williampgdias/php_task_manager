<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table                = 'users';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;

    protected $returnType           = 'array';

    protected $allowedFields        = ['name', 'email', 'password', 'api_token'];

    protected $useTimestamps        = true;
    protected $createdField         = 'created_at';
    protected $updatedField         = 'updated_at';

    protected $validationRules      = [
        'name'      => 'required|min_length[3]|max_length[255]',
        'email'     => 'required|valid_email|is_unique[users.email]',
        'password'  => 'required|min_length[6]',
    ];

    protected $validationMessages   = [
        'email'     => [
            'is_unique' => 'This email is already registered.',
        ],
    ];
}