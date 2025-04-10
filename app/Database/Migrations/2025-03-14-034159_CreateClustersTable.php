<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateClustersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true, // For soft delete
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('clusters');
    }

    public function down()
    {
        $this->forge->dropTable('clusters');
    }
}
