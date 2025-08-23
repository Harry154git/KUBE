<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\StoreModel;

class AuthController extends BaseController
{
    public function __construct()
    {
        helper(['form', 'url', 'session']);
    }

    public function login()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/home');
        }
        return view('login_view');
    }

    public function attemptLogin()
    {
        $userModel = new UserModel();
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $userModel->where('email', $email)->first();

        if ($user && password_verify($password, $user['password'])) {
            $sessionData = [
                'user_id'     => $user['id'],
                'full_name'   => $user['full_name'],
                'email'       => $user['email'],
                'is_seller'   => $user['is_seller'],
                'store_id'    => $user['store_id'],
                'isLoggedIn'  => TRUE
            ];
            session()->set($sessionData);

            if ($user['is_seller'] && empty(session()->get('store_name')) && !empty($user['store_id'])) {
                $storeModel = new StoreModel();
                $store = $storeModel->find($user['store_id']);
                if ($store) {
                    session()->set('store_name', $store['store_name']);
                }
            }

            return redirect()->to('/home');
        }

        session()->setFlashdata('error', 'Email or Password wrong.');
        return redirect()->to('/login');
    }

    public function register()
    {
        $data = [];

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'full_name' => 'required|min_length[3]|max_length[255]',
                'email'         => 'required|valid_email|is_unique[users.email]',
                'password'      => 'required|min_length[8]',
                'password_confirm' => 'required|matches[password]'
            ];

            if ($this->validate($rules)) {
                $model = new UserModel();
                $newData = [
                    'full_name' => $this->request->getPost('full_name'), 
                    'email'         => $this->request->getPost('email'),
                    'password'      => $this->request->getPost('password'),
                ];
                $model->save($newData);

                session()->setFlashdata('success', 'Registration successful! Please login.');
                return redirect()->to('/login');
            } else {
                $data['validation'] = $this->validator;
            }
        }

        return view('register_view', $data);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}