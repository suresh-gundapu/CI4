<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'mod_customer';
    protected $primaryKey       = 'iCustomerId';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [

        "iCustomerId",
        "vFirstName",
        "vLastName",
        "vEmail",
        "vUserName",
        "vPassword",
        "vPhonenumber",
        "vProfileImage",
        "eAuthType",
        "vAuthCode",
        "eMobileVerified",
        "vOTPCode",
        "eEmailVerified",
        "vVerificationCode",
        "dLatitude",
        "dLongitude",
        "eStatus"
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'dtRegisteredDate';
    protected $updatedField  = 'dModifiedDate';
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
