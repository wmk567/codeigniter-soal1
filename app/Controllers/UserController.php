<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Database\Exceptions\DatabaseException;

// handle login and register function and page loading
class UserController extends BaseController
{
    public function index()
    {
        return view('user/login');
    }

    public function register()
    {
        return view('user/register');
    }

    // Create new user
    public function create(){   
        try{
            $validcheck = \Config\Services::validation();
            $validcheck->setRules([
                'username' => 'required',
                'password' => 'required'
            ]);

            if($validcheck->withRequest($this->request)->run()){

                $model = new UserModel();
                $user = $model->findUserByUsername($this->request->getPost('username'));
                if($user){
                    return redirect()->to('/user/register')->with('error', 'Username already exist');
                }

                $data = [
                    'name' => $this->request->getPost('username'),
                    'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT)
                ];

                $model->setUser($data);
            }

            return view('user/login');
        } catch (DatabaseException){
            return view('db_error');
        }
    }

    public function login(){
        try {
            $validcheck = \Config\Services::validation();
            $validcheck->setRules([
                'username' => 'required',
                'password' => 'required'
            ]);

            if($validcheck->withRequest($this->request)->run()){

                $username = $this->request->getPost('username');
                $password = $this->request->getPost('password');

                $model = new UserModel();
                $user = $model->findUserByUsername($username);

                if ($user) {
                    $session = \Config\Services::session();
                    $session->set([
                        'user_id' => $user['id'],
                        'username' => $user['name'],
                        'type' => $user['type'],
                        'is_logged_in' => true
                    ]);
                    if (password_verify($password, $user['password'])) {
                        if($user['type'] === 'admin'){
                            return redirect()->to('/products');
                        } else {
                            return redirect()->to('/physical-products');
                        }
                    } else {
                        return redirect()->back()->with('error', 'Invalid password.');
                    }
                } else {
                    return redirect()->back()->with('error', 'User not found.');
                }
            }
            
        } catch (DatabaseException){
            return view('db_error');
        }
    }

    public function logout()
    {
        $session = \Config\Services::session();
        $session->destroy();
        
        return redirect()->to('/user/login')->with('success', 'You have been logged out.');
    }

}
