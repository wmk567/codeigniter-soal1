<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use CodeIgniter\Database\Exceptions\DatabaseException;


// Program that handles the product page and product CRUD
// Can only be access by admin user
class ProductController extends BaseController
{
    // Handle main page loading
    // admin user will be redirected here after login
    public function index()
    {
        try{
            
            $session = \Config\Services::session();
            if (!$session->get('is_logged_in')) {
                return redirect()->to('/user/login')->with('error', 'Please log in first.');
            }
            if($session->get('type') !== 'admin'){
                return redirect()->to('/user/login')->with('error', 'Permission failed');
            }

            $model = new ProductModel();
            $id = $this->request->getGet('id');

            $cache = \Config\Services::cache();
            $cacheKey = $id ? 'product_' . $id : 'products_list';
            $cachedData = $cache->get($cacheKey);

            if ($cachedData === null) {
                if ($id) {
                    $product = $model->getProductById($id);
                    $data['products'] = $product ? [$product] : [];
                } else {
                    $data['products'] = $model->getProducts();
                }
    
                $cache->save($cacheKey, $data['products'], 600);
            } else {
                $data['products'] = $cachedData;
            }

            return view('product/data', $data);
        } catch (DatabaseException){
            return view('db_error');
        }
    }

    // Handle new data creation
    public function create(){
        try{
            $session = \Config\Services::session();
            if (!$session->get('is_logged_in')) {
                return redirect()->to('/user/login')->with('error', 'Please log in first.');
            }
            if($session->get('type') !== 'admin'){
                return redirect()->to('/user/login')->with('error', 'Permission failed');
            }

            $validcheck = \Config\Services::validation();
            $validcheck->setRules([
                'name' => 'required',
                'description' => 'required',
                'price' => 'required',
                'type' => 'required'
            ]);
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

                $cache = \Config\Services::cache();
                $cache->delete('products_list');
            }

            return view('product/create');
        } catch (DatabaseException){
            return view('db_error');
        }
    }

    // Handle new data page loading
    public function createView()
    {
        $session = \Config\Services::session();
        if (!$session->get('is_logged_in')) {
            return redirect()->to('/user/login')->with('error', 'Please log in first.');
        }
        if($session->get('type') !== 'admin'){
            return redirect()->to('/user/login')->with('error', 'Permission failed');
        }

        return view('product/create');
    }

    // Handle data edit
    public function edit(){
        try{
            $session = \Config\Services::session();
            if (!$session->get('is_logged_in')) {
                return redirect()->to('/user/login')->with('error', 'Please log in first.');
            }
            if($session->get('type') !== 'admin'){
                return redirect()->to('/user/login')->with('error', 'Permission failed');
            }

            if ($this->request->getMethod(true) !== 'PUT') {
                return redirect()->back()->with('error', 'Invalid request method.');
            }

            $validcheck = \Config\Services::validation();
            $validcheck->setRules([
                'name' => 'required',
                'description' => 'required',
                'price' => 'required',
                'type' => 'required'
            ]);
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

                $cache = \Config\Services::cache();
                $cache->delete('product_' . $id);
                $cache->delete('products_list');
            }

            return redirect()->to('/products');
        } catch (DatabaseException){
            return view('db_error');
        }
    }

    // Handle edit data page loading
    public function createEditView()
    {
        try {
            $session = \Config\Services::session();
            if (!$session->get('is_logged_in')) {
                return redirect()->to('/user/login')->with('error', 'Please log in first.');
            }
            if($session->get('type') !== 'admin'){
                return redirect()->to('/user/login')->with('error', 'Permission failed');
            }

            $model = new ProductModel();
            $id = $this->request->getGet('id');

            $product = $model->getProductById($id);
            $data['products'] = $product ? [$product] : [];

            return view('product/edit', $data);
        } catch (DatabaseException){
            return view('db_error');
        }
    }

    // Handle data deletion (soft delete)
    public function delete(){
        try{
            $session = \Config\Services::session();
            if (!$session->get('is_logged_in')) {
                return redirect()->to('/user/login')->with('error', 'Please log in first.');
            }
            if($session->get('type') !== 'admin'){
                return redirect()->to('/user/login')->with('error', 'Permission failed');
            }

            $id = $this->request->getPost('id');

            $model = new ProductModel();
            $model->deleteProduct($id);

            $cache = \Config\Services::cache();
            $cache->delete('product_' . $id);
            $cache->delete('products_list');

            return redirect()->to('/products');
        } catch (DatabaseException){
            return view('db_error');
        }
    }
}
