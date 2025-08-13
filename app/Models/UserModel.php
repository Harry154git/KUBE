<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table              = 'users';
    protected $primaryKey         = 'id';
    protected $useAutoIncrement   = true;
    protected $returnType         = 'array';
    protected $useSoftDeletes     = false;
    protected $allowedFields      = ['nama_lengkap', 'email', 'password', 'is_seller', 'toko_id'];

    // Dates
    protected $useTimestamps      = true;
    protected $createdField       = 'created_at';
    protected $updatedField       = 'updated_at';
    protected $deletedField       = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks     = true;
    protected $beforeInsert       = ['hashPassword'];
    protected $beforeUpdate       = ['hashPassword'];
    protected $afterInsert        = [];
    protected $afterUpdate        = [];
    protected $beforeFind         = [];
    protected $afterFind          = [];
    protected $beforeDelete       = [];
    protected $afterDelete        = [];

    /**
     * Method untuk hashing password secara otomatis sebelum disimpan.
     * @param array $data
     * @return array
     */
    protected function hashPassword(array $data)
    {
        if (!isset($data['data']['password'])) {
            return $data;
        }

        $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        return $data;
    }
}