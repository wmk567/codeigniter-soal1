<?php

namespace App\Models;

use CodeIgniter\Model;

class PropertyModel extends Model
{
    protected $table = 'property';
    protected $primaryKey = 'id'; 
    protected $allowedFields = ['product_id', 'property_name', 'property_value', 'created_at', 'updated_at', 'deleted_at'];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';

    public function getProperty()
    {
        return $this->where('deleted_at', null)->findAll();
    }

    public function getPropertyById($id)
    {
        return $this->where('deleted_at', null)->find($id);
    }

    public function getPropertyByProductId($id)
    {
        return $this->where('deleted_at', null)->where('product_id', $id)->findAll();
    }

    public function setProperty($data){
        return $this->insert($data);
    }

    public function editProperty($id, $data){
        return $this->update($id, $data);
    }

    public function deleteProperty($id){
        $currentTime = date('Y-m-d H:i:s');
        $data = [
            'deleted_at' => $currentTime
        ];

        return $this->update($id, $data);
    }
}
