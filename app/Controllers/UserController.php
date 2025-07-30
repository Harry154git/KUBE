<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class UserController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        helper(['form', 'url', 'session']);
    }

    /**
     * Menampilkan halaman profil pengguna.
     */
    public function profile()
    {
        $userId = session()->get('user_id');
        $data['user'] = $this->userModel->find($userId);

        return view('profile_view', $data);
    }

    /**
     * Menampilkan halaman pengaturan dan menangani pembaruan data.
     */
    public function settings()
    {
        $userId = session()->get('user_id');
        $data['user'] = $this->userModel->find($userId);

        if ($this->request->getMethod() === 'post') {
            // Cek jenis form yang di-submit
            $formType = $this->request->getPost('form_type');

            if ($formType === 'update_profile') {
                return $this->updateProfile($userId);
            }

            if ($formType === 'update_password') {
                return $this->updatePassword($userId);
            }
        }

        return view('settings_view', $data);
    }

    private function updateProfile($userId)
    {
        $rules = [
            'nama_lengkap' => 'required|min_length[3]|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/settings')->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->userModel->update($userId, [
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
        ]);

        // Update session juga agar nama di navbar berubah
        session()->set('nama_lengkap', $this->request->getPost('nama_lengkap'));

        session()->setFlashdata('success', 'Profil berhasil diperbarui.');
        return redirect()->to('/settings');
    }

    private function updatePassword($userId)
    {
        $rules = [
            'current_password' => 'required',
            'new_password'     => 'required|min_length[8]',
            'confirm_password' => 'required|matches[new_password]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/settings')->withInput()->with('errors', $this->validator->getErrors());
        }

        $user = $this->userModel->find($userId);
        $currentPassword = $this->request->getPost('current_password');
        $newPassword = $this->request->getPost('new_password');

        // Verifikasi password lama
        if (!password_verify($currentPassword, $user['password'])) {
            session()->setFlashdata('error', 'Password lama Anda tidak cocok.');
            return redirect()->to('/settings');
        }

        // Update password baru (Model akan hash otomatis)
        $this->userModel->update($userId, ['password' => $newPassword]);

        session()->setFlashdata('success', 'Password berhasil diubah.');
        return redirect()->to('/settings');
    }
}