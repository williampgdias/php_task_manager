<?php

namespace App\Models;

use CodeIgniter\Model;

class TaskModel extends Model
{
    protected $table            = 'tasks';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $returnType       = 'array';

    protected $allowedFields    = ['title', 'description', 'status', 'user_id'];

    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $validationRules  = [
        'title'     => 'required|min_length[3]|max_length[255]',
        'status'    => 'permit_empty|in_list[pending,in_progress,completed]',
    ];

    protected $validationMessages = [
        'title'     => [
            'required'      => 'Task title is required.',
            'min_length'    => 'Title must be at least 3 characters.',
        ],
    ];
}