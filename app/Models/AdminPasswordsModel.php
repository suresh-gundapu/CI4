<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminPasswordsModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'mod_admin_passwords';
    protected $primaryKey       = 'iPasswordId';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        "iAdminId",
        "iPasswordId",
        "vPassword",
        "eStatus",
        "dtAddedDate"
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'dtAddedDate';
    protected $updatedField  = '';
    protected $deletedField  = 'iSysRecDeleted';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}
