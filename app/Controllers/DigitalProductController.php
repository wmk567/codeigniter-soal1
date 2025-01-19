<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DigitalProductModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Database\Exceptions\DatabaseException;

// Program that handles the digital product page and digital product CRUD
// Can be access by all user
class DigitalProductController extends BaseController
{
    // Handle main page loading
    public function index()
    {
        try{
            $session = \Config\Services::session();
            if (!$session->get('is_logged_in')) {
                return redirect()->to('/user/login')->with('error', 'Please log in first.');
            }

            $model = new DigitalProductModel();
            $id = $this->request->getGet('id');

            $cache = \Config\Services::cache();
            $cacheKey = $id ? 'digital_product_' . $id : 'digital_products_list';
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

            return view('digital_product/data', $data);
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
                    'filename' => $uploadedFileName,
                ];

                $model = new DigitalProductModel();
                $model->setProduct($data);

                $cache = \Config\Services::cache();
                $cache->delete('digital_products_list');
            }

            return view('digital_product/create');
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

        return view('digital_product/create');
    }

    // Handle data edit
    public function edit(){
        try{
            $session = \Config\Services::session();
            if (!$session->get('is_logged_in')) {
                return redirect()->to('/user/login')->with('error', 'Please log in first.');
            }

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
                    'filename' => $uploadedFileName,
                ];

                $model = new DigitalProductModel();
                $model->editProduct($id, $data);

                $cache = \Config\Services::cache();
                $cache->delete('digital_product_' . $id);
                $cache->delete('digital_products_list');
            }

            return redirect()->to('/digital-products');
        } catch (DatabaseException){
            return view('db_error');
        }
    }

    // Handle edit data page loading
    public function createEditView()
    {
        try{
            $session = \Config\Services::session();
            if (!$session->get('is_logged_in')) {
                return redirect()->to('/user/login')->with('error', 'Please log in first.');
            }

            $model = new DigitalProductModel();
            $id = $this->request->getGet('id');

            $product = $model->getProductById($id);
            $data['products'] = $product ? [$product] : [];
            return view('digital_product/edit', $data);
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

            $id = $this->request->getPost('id');

            $model = new DigitalProductModel();
            $model->deleteProduct($id);

            $cache = \Config\Services::cache();
            $cache->delete('digital_product_' . $id);
            $cache->delete('digital_products_list');

            return redirect()->to('/digital-products');
        } catch (DatabaseException){
            return view('db_error');
        }
    }
}
