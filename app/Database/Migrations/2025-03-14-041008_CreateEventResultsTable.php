<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEventResultsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'event_id' => [
                'type'       => 'INT',
                'constraint' => 5,
                'unsigned'   => true,
            ],
            'participant_id' => [
                'type'       => 'INT',
                'constraint' => 5,
                'unsigned'   => true,
            ],
            'rank' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
            ],
            'points' => [
                'type'       => 'INT',
                'constraint' => 5,
                'unsigned'   => true,
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
                'null' => true, // For Soft Delete
            ],
        ]);

        // Set Primary Key
        $this->forge->addKey('id', true);

        // Define Foreign Keys
        $this->forge->addForeignKey('event_id', 'events', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('participant_id', 'participants', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('event_results');
    }

    public function down()
    {
        $this->forge->dropTable('event_results');
    }
}
