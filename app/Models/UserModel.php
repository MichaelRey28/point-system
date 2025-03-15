<?php

namespace App\Models;

use CodeIgniter\Model;

//for account creation and login

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'email', 'password', 'role', 'created_at'];
}
