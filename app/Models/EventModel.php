<?php

namespace App\Models;

use CodeIgniter\Model;
//PARA MACALL AND MAPROCESS NIYA GOING CONTROLLERS

class EventModel extends Model
{
    protected $table = 'events';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'created_at', 'updated_at', 'deleted_at'];
    protected $useTimestamps = true;
    protected $useSoftDeletes = true;
    
    
    protected $dateFormat = 'datetime';
    protected $deletedField = 'deleted_at';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}