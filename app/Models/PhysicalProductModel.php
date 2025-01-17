<?php

namespace App\Models;

use CodeIgniter\Model;

class PhysicalProductModel extends ProductModel
{
    protected $allowedFields = ['name', 'description', 'price', 'type', 'quantity', 'created_at', 'updated_at', 'deleted_at'];

    public function getProducts()
    {
        return $this->where('deleted_at', null)->where('type', 'physical')->findAll();
    }

    public function getProductById($id)
    {
        return $this->where('deleted_at', null)->where('type', 'physical')->find($id);
    }
}
