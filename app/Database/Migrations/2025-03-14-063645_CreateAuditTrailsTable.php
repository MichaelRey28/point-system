<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAuditTrailsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true, // Nullable (in case user is not logged in)
            ],
            'action' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'table_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'record_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'old_value' => [
                'type' => 'TEXT',
                'null' => true, // Nullable (in case of insert action)
            ],
            'new_value' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => false, 
            ]
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('audit_trails');
    }

    public function down()
    {
        $this->forge->dropTable('audit_trails');
    }
}
