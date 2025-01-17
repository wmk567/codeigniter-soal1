<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id'; 
    protected $allowedFields = ['name', 'description', 'price', 'type', 'filename', 'quantity', 'created_at', 'updated_at', 'deleted_at'];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';

    public function getProducts()
    {
        return $this->where('deleted_at', null)->findAll();
    }

    public function getProductById($id)
    {
        return $this->where('deleted_at', null)->find($id);
    }

    public function setProduct($data){
        return $this->insert($data);
    }

    public function editProduct($id, $data){
        return $this->update($id, $data);
    }

    public function deleteProduct($id){
        $currentTime = date('Y-m-d H:i:s');
        $data = [
            'deleted_at' => $currentTime
        ];

        return $this->update($id, $data);
    }
}
