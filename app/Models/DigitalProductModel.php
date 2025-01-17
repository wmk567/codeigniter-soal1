<?php

namespace App\Models;

class DigitalProductModel extends ProductModel
{
    protected $allowedFields = ['name', 'description', 'price', 'type', 'filename', 'created_at', 'updated_at', 'deleted_at'];

    public function getProducts()
    {
        return $this->where('deleted_at', null)->where('type', 'digital')->findAll();
    }

    public function getProductById($id)
    {
        return $this->where('deleted_at', null)->where('type', 'digital')->find($id);
    }
}
