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
        return redirect()->to('/home');
    }

    public function attemptLogin()
    {
        // (BARU) Tangkap URL redirect dari form
        $redirectUrl = $this->request->getPost('redirect_url') ?? '/home';

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

            return redirect()->to($redirectUrl);
        }

        session()->setFlashdata('error', 'Email or Password wrong.');
        return redirect()->to('/login')->withInput()->with('redirect_url', $redirectUrl);
    }

    public function register()
    {
        // Jika pengguna sudah login, arahkan ke home.
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/home');
        }

        $data = [];
        $redirectUrl = $this->request->getPost('redirect_url') ?? '/home';

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

                $redirectUrl = $this->request->getPost('redirect_url') ?: '/home';
                session()->setFlashdata('success_register', 'Registrasi berhasil! Silakan login.');
                return redirect()->to($redirectUrl);
            } else {
                $redirectUrl = $this->request->getPost('redirect_url') ?: '/home';
                return redirect()->to($redirectUrl)
                                ->withInput()
                                ->with('validation', $this->validator)
                                ->with('redirect_url', $redirectUrl); 
            }
        }

        return redirect()->to('/home');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}