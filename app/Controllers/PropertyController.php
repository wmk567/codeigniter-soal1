<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PropertyModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Database\Exceptions\DatabaseException;

class PropertyController extends BaseController
{
    public function index(){
        try{
            $model = new PropertyModel();
            $id = $this->request->getGet('product_id');

            
            $property = $model->getPropertyByProductId($id);
            $data['properties'] = $property;
             
            return view('property/data', $data);
        } catch (DatabaseException){
            return view('db_error');
        }
    }

    public function create()
    {
        try{ 
            $validcheck = \Config\Services::validation();
            $validcheck->setRules(['product_id' => 'required']);

            if($validcheck->withRequest($this->request)->run()){

                $data = [
                    'product_id' => $this->request->getPost('product_id'),
                    'property_name' => $this->request->getPost('property_name'),
                    'property_value' => $this->request->getPost('property_value'),
                ];

                $model = new PropertyModel();
                $model->setProperty($data);
            }

            return redirect()->to('/property?product_id=' . $this->request->getPost('product_id'));
        } catch (DatabaseException){
            return view('db_error');
        }
    }

    public function edit(){
        try{
            if ($this->request->getMethod(true) !== 'PUT') {
                return redirect()->back()->with('error', 'Invalid request method.');
            }

            $validcheck = \Config\Services::validation();
            $validcheck->setRules(['product_id' => 'required']);

            $id = $this->request->getPost()['id'] ?? null;

            if($validcheck->withRequest($this->request)->run()){
                $data = [
                    'product_id' => $this->request->getPost('product_id'),
                    'property_name' => $this->request->getPost('property_name'),
                    'property_value' => $this->request->getPost('property_value'),
                ];

                $model = new PropertyModel();
                $model->editProperty($id, $data);
            }

            return redirect()->to('/property?product_id=' . $this->request->getPost('product_id'));
        } catch (DatabaseException){
            return view('db_error');
        }
    }

    public function createEditView()
    {
        try {
            $model = new PropertyModel();
            $id = $this->request->getGet('id');

            $property = $model->getPropertyById($id);
            $data['property'] = $property ? [$property] : [];
            return view('property/edit', $data);
        } catch (DatabaseException){
            return view('db_error');
        }
    }

    public function delete(){
        try{
            $id = $this->request->getPost('id');

            $model = new PropertyModel();
            $model->deleteProperty($id);

            return redirect()->to('/property?product_id=' . $this->request->getPost('product_id'));
        } catch (DatabaseException){
            return view('db_error');
        }
    }
}
