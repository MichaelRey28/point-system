<?php

namespace App\Models;

use CodeIgniter\Model;

class EventResultModel extends Model
{
    protected $table = 'event_results';
    protected $primaryKey = 'id';
    protected $allowedFields = ['event_id', 'participant_id', 'rank', 'points', 'created_at', 'updated_at'];
    protected $useTimestamps = true;
    protected $useSoftDeletes = true; 

// Ito yung function na nagcacalculate ng most points then magiging basis ng ranking system
public function getResults()
{
    return $this->select([
                    'participants.id AS participant_id', 
                    'participants.name AS participant_name', 
                    'clusters.name AS cluster_name', 
                    'events.name AS event_name', 
                    'COALESCE(SUM(event_results.points), 0) AS total_points',
                    'RANK() OVER (ORDER BY SUM(event_results.points) DESC) AS rank'
                ])
                ->join('events', 'events.id = event_results.event_id', 'left')
                ->join('participants', 'participants.id = event_results.participant_id', 'left')
                ->join('clusters', 'clusters.id = participants.cluster_id', 'left') 
                ->groupBy('participants.id, participants.name, clusters.id, clusters.name, events.id, events.name')
                ->orderBy('total_points', 'DESC')
                ->findAll();
}

    //getter function for ranked results (for printing only)
    public function getRankedResults()
    {
        return $this->select([
                    'participants.id AS participant_id', 
                    'participants.name AS participant_name', 
                    'clusters.name AS cluster_name', 
                    'IFNULL(SUM(event_results.points), 0) AS total_points'
                ])
                ->join('participants', 'participants.id = event_results.participant_id', 'left')
                ->join('clusters', 'clusters.id = participants.cluster_id', 'left')
                ->groupBy('participants.id, participants.name, clusters.name')
                ->orderBy('total_points', 'DESC')
                ->findAll();
    }

    //pang sort lang ng participants according to their cluster(SQL Query)
        public function getParticipantsByCluster($cluster_id)
    {
        return $this->select('participants.id, participants.name')
                    ->where('participants.cluster_id', $cluster_id)
                    ->findAll();
    }
}
