<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AddressModel;

class AddressController extends BaseController
{
    protected $addressModel;
    protected $userId;

    public function __construct()
    {
        $this->addressModel = new AddressModel();
        // Pastikan user sudah login untuk mengakses controller ini
        $this->userId = session()->get('user_id');
        helper(['form', 'url', 'session']);
    }

    /**
     * Menampilkan halaman daftar alamat.
     */
    public function index()
    {
        if (!$this->userId) {
            return redirect()->to('/login');
        }

        $data = [
            'addresses' => $this->addressModel->where('user_id', $this->userId)->orderBy('is_primary', 'DESC')->findAll(),
            'validation' => \Config\Services::validation() // Kirim validation ke view
        ];

        return view('addresses/addresses_view', $data);
    }

    /**
     * Menyimpan alamat baru.
     */
    public function create()
    {
        // 1. Cek batas maksimal alamat (3)
        $addressCount = $this->addressModel->countByUserId($this->userId);
        if ($addressCount >= 3) {
            return redirect()->to('/addresses')->with('error', 'Anda sudah mencapai batas maksimal 3 alamat.');
        }

        // 2. Validasi input
        if (!$this->validate($this->getAddressValidationRules())) {
            return redirect()->to('/addresses')->withInput()->with('error', 'Gagal menambahkan alamat. Periksa kembali data yang Anda masukkan.');
        }

        // 3. Siapkan data untuk disimpan
        $data = [
            'user_id'        => $this->userId,
            'label'          => $this->request->getPost('label'),
            'recipient_name' => $this->request->getPost('recipient_name'),
            'phone_number'   => $this->request->getPost('phone_number'),
            'address'        => $this->request->getPost('address'),
            'city'           => $this->request->getPost('city'),
            'province'       => $this->request->getPost('province'),
            'postal_code'    => $this->request->getPost('postal_code'),
            'is_primary'     => ($addressCount == 0), // Alamat pertama otomatis jadi utama
        ];

        // 4. Simpan data
        if ($this->addressModel->save($data)) {
            session()->setFlashdata('success', 'Alamat baru berhasil ditambahkan.');
        } else {
            session()->setFlashdata('error', 'Gagal menambahkan alamat.');
        }

        return redirect()->to('/addresses');
    }

    /**
     * Memperbarui data alamat.
     */
    public function update($id)
    {
        // 1. Validasi input
        if (!$this->validate($this->getAddressValidationRules())) {
            return redirect()->to('/addresses')->withInput()->with('error', 'Gagal memperbarui alamat. Periksa kembali data yang Anda masukkan.');
        }

        // 2. Pastikan user hanya bisa mengedit alamatnya sendiri
        $address = $this->addressModel->find($id);
        if (!$address || $address['user_id'] != $this->userId) {
            return redirect()->to('/addresses')->with('error', 'Aksi tidak diizinkan.');
        }
        
        // 3. Siapkan data untuk diupdate
        $data = [
            'label'          => $this->request->getPost('label'),
            'recipient_name' => $this->request->getPost('recipient_name'),
            'phone_number'   => $this->request->getPost('phone_number'),
            'address'        => $this->request->getPost('address'),
            'city'           => $this->request->getPost('city'),
            'province'       => $this->request->getPost('province'),
            'postal_code'    => $this->request->getPost('postal_code'),
        ];

        // 4. Update data
        if($this->addressModel->update($id, $data)) {
            session()->setFlashdata('success', 'Alamat berhasil diperbarui.');
        } else {
            session()->setFlashdata('error', 'Gagal memperbarui alamat.');
        }
        
        return redirect()->to('/addresses');
    }

    /**
     * Menghapus alamat.
     */
    public function delete($id)
    {
        $address = $this->addressModel->find($id);

        // Pastikan user hanya bisa menghapus alamatnya sendiri
        if (!$address || $address['user_id'] != $this->userId) {
            return redirect()->to('/addresses')->with('error', 'Aksi tidak diizinkan.');
        }
        
        // Alamat utama tidak boleh dihapus
        if ($address['is_primary']) {
            return redirect()->to('/addresses')->with('error', 'Alamat utama tidak dapat dihapus. Jadikan alamat lain sebagai utama terlebih dahulu.');
        }
        
        if($this->addressModel->delete($id)) {
            session()->setFlashdata('success', 'Alamat berhasil dihapus.');
        } else {
            session()->setFlashdata('error', 'Gagal menghapus alamat.');
        }

        return redirect()->to('/addresses');
    }

    /**
     * Mengatur alamat sebagai alamat utama.
     */
    public function setPrimary($id)
    {
        $address = $this->addressModel->find($id);

        if ($address && $address['user_id'] == $this->userId) {
            $this->addressModel->setPrimaryAddress($id, $this->userId);
            session()->setFlashdata('success', 'Alamat utama berhasil diubah.');
        } else {
            session()->setFlashdata('error', 'Aksi tidak diizinkan.');
        }

        return redirect()->to('/addresses');
    }
    
    /**
     * Helper untuk aturan validasi alamat.
     */
    private function getAddressValidationRules(): array
    {
        return [
            'label'          => 'required|max_length[100]',
            'recipient_name' => 'required|max_length[255]',
            'phone_number'   => 'required|max_length[20]',
            'address'        => 'required',
            'city'           => 'required|max_length[100]',
            'province'       => 'required|max_length[100]',
            'postal_code'    => 'required|max_length[10]',
        ];
    }
}