<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUserIdToTasks extends Migration
{
    public function up()
    {
        $this->forge->addColumn('tasks', [
            'user_id' => [
                'type'          => 'INT',
                'constraint'    => 11,
                'unsigned'      => true,
                'after'         => 'id',
            ],
        ]);

        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE', 'tasks');
    }

    public function down()
    {
        $this->forge->dropPrimaryKey('task', 'tasks_user_id_foreign');
        $this->forge->dropColumn('tasks', ['user_id']);
    }
}