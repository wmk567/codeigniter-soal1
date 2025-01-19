<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id'; 
    protected $allowedFields = ['name', 'password', 'type', 'created_at', 'updated_at', 'deleted_at'];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';

    public function getUsers()
    {
        return $this->where('deleted_at', null)->findAll();
    }

    public function getUserById($id)
    {
        return $this->where('deleted_at', null)->find($id);
    }

    public function setUser($data){
        return $this->insert($data);
    }

    public function findUserByUsername($username){
        return $this->where('name', $username)->first();
    }
}
