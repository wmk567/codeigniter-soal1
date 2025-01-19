<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PhysicalProductModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Database\Exceptions\DatabaseException;

// Program that handles the physical product page and physical product CRUD
// Can be access by all user
class PhysicalProductController extends BaseController
{
    // Handle main page loading
    // non admin user will be redirected here after login
    public function index()
    {
        try{
            $session = \Config\Services::session();
            if (!$session->get('is_logged_in')) {
                return redirect()->to('/user/login')->with('error', 'Please log in first.');
            }

            $model = new PhysicalProductModel();
            $id = $this->request->getGet('id');

            $cache = \Config\Services::cache();
            $cacheKey = $id ? 'physical_product_' . $id : 'physical_products_list';
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

            return view('physical_product/data', $data);
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
            $validcheck->setRules([
                'name' => 'required',
                'description' => 'required',
                'price' => 'required',
                'type' => 'required',
                'quantity' => 'required'
            ]);

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

                $cache = \Config\Services::cache();
                $cache->delete('physical_products_list');
            }

            return view('physical_product/create');
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

        return view('physical_product/create');
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
            $validcheck->setRules([
                'name' => 'required',
                'description' => 'required',
                'price' => 'required',
                'type' => 'required',
                'quantity' => 'required'
            ]);

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

                $cache = \Config\Services::cache();
                $cache->delete('physical_product_' . $id);
                $cache->delete('physical_products_list');
            }

            return redirect()->to('/physical-products');
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

            $model = new PhysicalProductModel();
            $id = $this->request->getGet('id');

            $product = $model->getProductById($id);
            $data['products'] = $product ? [$product] : [];

            return view('physical_product/edit', $data);
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

            $model = new PhysicalProductModel();
            $model->deleteProduct($id);

            $cache = \Config\Services::cache();
            $cache->delete('physical_product_' . $id);
            $cache->delete('physical_products_list');

            return redirect()->to('/physical-products');
        } catch (DatabaseException){
            return view('db_error');
        }
    }
}
