<?php

namespace App\Models;

use CodeIgniter\Model;

class ParticipantModel extends Model
{
    protected $table = 'participants';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'cluster_id', 'event_id', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
    protected $useSoftDeletes = true; 

    //getter function for participants (SQL Query)
    public function getParticipants()
    {
        return $this->select('participants.*, clusters.name AS cluster_name, events.name AS event_name')
                    ->join('clusters', 'clusters.id = participants.cluster_id')
                    ->join('events', 'events.id = participants.event_id')
                    ->findAll();
    }
}
