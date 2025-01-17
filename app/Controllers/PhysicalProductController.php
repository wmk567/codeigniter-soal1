<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PhysicalProductModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Database\Exceptions\DatabaseException;

class PhysicalProductController extends BaseController
{
    public function index()
    {
        try{
            $model = new PhysicalProductModel();
            $id = $this->request->getGet('id');

            if ($id) {
                $product = $model->getProductById($id);
                $data['products'] = $product ? [$product] : [];
            } else {
                $data['products'] = $model->getProducts();
            }

            return view('physical_product/data', $data);
        } catch (DatabaseException){
            return view('db_error');
        }
    }

    public function create(){   
        try{
            $validcheck = \Config\Services::validation();
            $validcheck->setRules(['name' => 'required']);

            if($validcheck->withRequest($this->request)->run()){
                $data = [
                    'name' => $this->request->getPost('name'),
                    'description' => $this->request->getPost('description'),
                    'price' => $this->request->getPost('price'),
                    'type' => $this->request->getPost('type'),
                    'quantity' => $this->request->getPost('quantity'),
                ];

                $model = new PhysicalProductModel();
                $model->setProduct($data);
            }

            return view('physical_product/create');
        } catch (DatabaseException){
            return view('db_error');
        }
    }

    public function createView()
    {
        return view('physical_product/create');
    }

    public function edit(){
        try{
            if ($this->request->getMethod(true) !== 'PUT') {
                return redirect()->back()->with('error', 'Invalid request method.');
            }

            $validcheck = \Config\Services::validation();
            $validcheck->setRules(['name' => 'required']);

            $id = $this->request->getPost()['id'] ?? null;

            if($validcheck->withRequest($this->request)->run()){
                $data = [
                    'name' => $this->request->getPost('name'),
                    'description' => $this->request->getPost('description'),
                    'price' => $this->request->getPost('price'),
                    'type' => $this->request->getPost('type'),
                    'quantity' => $this->request->getPost('quantity'),
                ];

                $model = new PhysicalProductModel();
                $model->editProduct($id, $data);
            }

            return redirect()->to('/physical-products');
        } catch (DatabaseException){
            return view('db_error');
        }
    }

    public function createEditView()
    {
        try{
            $model = new PhysicalProductModel();
            $id = $this->request->getGet('id');

            $product = $model->getProductById($id);
            $data['products'] = $product ? [$product] : [];
            return view('physical_product/edit', $data);
        } catch (DatabaseException){
            return view('db_error');
        }
    }

    public function delete(){
        try{
            $id = $this->request->getPost('id');

            $model = new PhysicalProductModel();
            $model->deleteProduct($id);

            return redirect()->to('/physical-products');
        } catch (DatabaseException){
            return view('db_error');
        }
    }
}
