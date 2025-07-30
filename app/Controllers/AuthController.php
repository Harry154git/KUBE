<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class AuthController extends BaseController
{
    public function __construct()
    {
        // Memuat helper yang dibutuhkan untuk semua method di controller ini
        helper(['form', 'url', 'session']);
    }

    /**
     * Menampilkan halaman login.
     */
    public function login()
    {
        // Jika sudah login, arahkan ke home
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/home');
        }
        return view('login_view'); // Pastikan Anda punya view ini
    }

    /**
     * Memproses percobaan login dari form.
     */
    public function attemptLogin()
    {
        $userModel = new UserModel();
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $userModel->where('email', $email)->first();

        if ($user && password_verify($password, $user['password'])) {
            // Jika login berhasil, set session
            $sessionData = [
                'user_id'       => $user['id'],
                'nama_lengkap'  => $user['nama_lengkap'],
                'email'         => $user['email'],
                'isLoggedIn'    => TRUE
            ];
            session()->set($sessionData);
            return redirect()->to('/home');
        }

        // Jika login gagal
        session()->setFlashdata('error', 'Email atau Password salah.');
        return redirect()->to('/login');
    }

    /**
     * Menampilkan halaman registrasi dan menangani pendaftaran.
     */
    public function register()
    {
        $data = [];

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'nama_lengkap' => 'required|min_length[3]|max_length[255]',
                'email'        => 'required|valid_email|is_unique[users.email]',
                'password'     => 'required|min_length[8]',
                'password_confirm' => 'required|matches[password]'
            ];

            if ($this->validate($rules)) {
                $model = new UserModel();
                $newData = [
                    'nama_lengkap' => $this->request->getPost('nama_lengkap'),
                    'email'        => $this->request->getPost('email'),
                    'password'     => $this->request->getPost('password'), // Model akan hash otomatis
                ];
                $model->save($newData);

                session()->setFlashdata('success', 'Registrasi berhasil! Silakan login.');
                return redirect()->to('/login');
            } else {
                // Jika validasi gagal, kirim error ke view
                $data['validation'] = $this->validator;
            }
        }

        // Tampilkan view register.php
        return view('register_view', $data); // Pastikan Anda punya view ini
    }

    /**
     * Menghapus session dan logout pengguna.
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}