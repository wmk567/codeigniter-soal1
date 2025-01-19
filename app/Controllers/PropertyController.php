<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PropertyModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Database\Exceptions\DatabaseException;

// Program that handles the property page and property CRUD
// Can only be access by admin user
// Property data connected to product data by the product_id
class PropertyController extends BaseController
{

    // Handle main page loading with create new data form
    public function index(){
        try{
            $session = \Config\Services::session();
            if (!$session->get('is_logged_in')) {
                return redirect()->to('/user/login')->with('error', 'Please log in first.');
            }
            if($session->get('type') !== 'admin'){
                return redirect()->to('/user/login')->with('error', 'Permission failed');
            }

            $model = new PropertyModel();
            $id = $this->request->getGet('product_id');

            $cache = \Config\Services::cache();
            $cacheKey = 'property_' . $id;
            $cachedData = $cache->get($cacheKey);
            
            if ($cachedData === null) {
                $property = $model->getPropertyByProductId($id);
                $data['properties'] = $property;

                $cache->save($cacheKey, $data['properties'], 600);
            } else {
                $data['properties'] = $cachedData;
                var_dump($data);
            }
        
            return view('property/data', $data);
        } catch (DatabaseException){
            return view('db_error');
        }
    }

    // Handle new data creation
    public function create()
    {
        try{
            $session = \Config\Services::session();
            if (!$session->get('is_logged_in')) {
                return redirect()->to('/user/login')->with('error', 'Please log in first.');
            }
            if($session->get('type') !== 'admin'){
                return redirect()->to('/user/login')->with('error', 'Permission failed');
            }

            $contentType = $this->request->getHeaderLine('Content-Type');
            if (str_contains($contentType, 'application/json')) {
                $data = $this->request->getJSON(true); 
            } else {
                $data = $this->request->getPost();
            }

            $validcheck = \Config\Services::validation();
            $validcheck->setRules([
                'product_id' => 'required',
                'property_name' => 'required',
                'property_value' => 'required'
            ]);

            if($validcheck->run($data)){
                $property_data = [
                    'product_id' => $data['product_id'],
                    'property_name' => $data['property_name'],
                    'property_value' => $data['property_value'],
                ];

                $model = new PropertyModel();
                $model->setProperty($property_data);

                $cache = \Config\Services::cache();
                $cache->delete('property_' . $data['product_id']);
            }

            return redirect()->to('/property?product_id=' . $data['product_id']);
        } catch (DatabaseException){
            return view('db_error');
        }
    }

    // Handle edit data
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

            $contentType = $this->request->getHeaderLine('Content-Type');
            if (str_contains($contentType, 'application/json')) {
                $data = $this->request->getJSON(true); 
            } else {
                $data = $this->request->getPost();
            }

            $id = $data['id'];

            $validcheck = \Config\Services::validation();
            $validcheck->setRules([
                'product_id' => 'required',
                'property_name' => 'required',
                'property_value' => 'required'
            ]);


            if($validcheck->run($data)){
                $property_data = [
                    'product_id' => $data['product_id'],
                    'property_name' => $data['property_name'],
                    'property_value' => $data['property_value'],
                ];

                $model = new PropertyModel();
                $model->editProperty($id, $property_data);

                $cache = \Config\Services::cache();
                $cache->delete('property_' . $data['product_id']);
            }

            return redirect()->to('/property?product_id=' . $data['product_id']);
        } catch (DatabaseException){
            var_dump($data);
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

            $model = new PropertyModel();
            $id = $this->request->getGet('id');

            $property = $model->getPropertyById($id);
            $data['property'] = $property ? [$property] : [];
            return view('property/edit', $data);
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

            $model = new PropertyModel();
            $model->deleteProperty($id);

            $cache = \Config\Services::cache();
            $cache->delete('property_' . $this->request->getPost('product_id'));

            return redirect()->to('/property?product_id=' . $this->request->getPost('product_id'));
        } catch (DatabaseException){
            return view('db_error');
        }
    }
}
