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
     * Displays the user profile page.
     */
    public function profile()
    {
        $userId = session()->get('user_id');
        $data['user'] = $this->userModel->find($userId);

        return view('profile_view', $data);
    }

    /**
     * Displays the settings page and handles data updates.
     */
    public function settings()
    {
        $userId = session()->get('user_id');
        $data['user'] = $this->userModel->find($userId);

        if ($this->request->getMethod() === 'post') {
            // Check the type of form submitted
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
            'full_name' => 'required|min_length[3]|max_length[255]', // Mengubah 'nama_lengkap'
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/settings')->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->userModel->update($userId, [
            'full_name' => $this->request->getPost('full_name'), // Mengubah 'nama_lengkap'
        ]);

        // Update session as well so the name in the navbar changes
        session()->set('full_name', $this->request->getPost('full_name')); // Mengubah 'nama_lengkap'

        session()->setFlashdata('success', 'Profile successfully updated.');
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

        // Verify old password
        if (!password_verify($currentPassword, $user['password'])) {
            session()->setFlashdata('error', 'Your old password does not match.');
            return redirect()->to('/settings');
        }

        // Update new password (Model will hash automatically)
        $this->userModel->update($userId, ['password' => $newPassword]);

        session()->setFlashdata('success', 'Password successfully changed.');
        return redirect()->to('/settings');
    }
}
