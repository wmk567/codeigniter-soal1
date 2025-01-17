<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use CodeIgniter\Database\Exceptions\DatabaseException;

class ProductController extends BaseController
{
    public function index()
    {
        try{
            $model = new ProductModel();
            $id = $this->request->getGet('id');

            if ($id) {
                $product = $model->getProductById($id);
                $data['products'] = $product ? [$product] : [];
            } else {
                $data['products'] = $model->getProducts();
            }

            return view('product/data', $data);
        } catch (DatabaseException){
            return view('db_error');
        }
    }

    public function create(){
        try{ 
            $validcheck = \Config\Services::validation();
            $validcheck->setRules(['name' => 'required']);
            $uploadedFileName = "";

            if($validcheck->withRequest($this->request)->run()){
                $file = $this->request->getFile('filename');

                if ($file && $file->isValid() && !$file->hasMoved()) {
                    $mimeType = $file->getMimeType();
                    $allowedMimeTypes = ['image/jpeg', 'image/png'];

                    if (!in_array($mimeType, $allowedMimeTypes)) {
                        return redirect()->back()->with('error', 'Invalid file type.');
                    }

                    $uploadPath = WRITEPATH . 'uploads/';
                    $uploadedFileName = $file->getRandomName();
                    $file->move($uploadPath, $uploadedFileName);
                }

                $data = [
                    'name' => $this->request->getPost('name'),
                    'description' => $this->request->getPost('description'),
                    'price' => $this->request->getPost('price'),
                    'type' => $this->request->getPost('type'),
                    'quantity' => $this->request->getPost('quantity'),
                    'filename' => $uploadedFileName,
                ];

                $model = new ProductModel();
                $model->setProduct($data);
            }

            return view('product/create');
        } catch (DatabaseException){
            return view('db_error');
        }
    }

    public function createView()
    {
        return view('product/create');
    }

    public function edit(){
        try{
            if ($this->request->getMethod(true) !== 'PUT') {
                return redirect()->back()->with('error', 'Invalid request method.');
            }

            $validcheck = \Config\Services::validation();
            $validcheck->setRules(['name' => 'required']);
            $uploadedFileName = "";

            $id = $this->request->getPost()['id'] ?? null;

            if($validcheck->withRequest($this->request)->run()){
                $file = $this->request->getFile('filename');

                if ($file && $file->isValid() && !$file->hasMoved()) {
                    $mimeType = $file->getMimeType();
                    $allowedMimeTypes = ['image/jpeg', 'image/png'];

                    if (!in_array($mimeType, $allowedMimeTypes)) {
                        return redirect()->back()->with('error', 'Invalid file type.');
                    }

                    $uploadPath = WRITEPATH . 'uploads/';
                    $uploadedFileName = $file->getRandomName();
                    $file->move($uploadPath, $uploadedFileName);
                }

                $data = [
                    'name' => $this->request->getPost('name'),
                    'description' => $this->request->getPost('description'),
                    'price' => $this->request->getPost('price'),
                    'type' => $this->request->getPost('type'),
                    'quantity' => $this->request->getPost('quantity'),
                    'filename' => $uploadedFileName,
                ];

                $model = new ProductModel();
                $model->editProduct($id, $data);
            }

            return redirect()->to('/products');
        } catch (DatabaseException){
            return view('db_error');
        }
    }

    public function createEditView()
    {
        try {
            $model = new ProductModel();
            $id = $this->request->getGet('id');

            $product = $model->getProductById($id);
            $data['products'] = $product ? [$product] : [];
            return view('product/edit', $data);
        } catch (DatabaseException){
            return view('db_error');
        }
    }

    public function delete(){
        try{
            $id = $this->request->getPost('id');

            $model = new ProductModel();
            $model->deleteProduct($id);

            return redirect()->to('/products');
        } catch (DatabaseException){
            return view('db_error');
        }
    }
}
