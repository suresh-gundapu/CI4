<?php

namespace App\Models;

use CodeIgniter\Model;

class CountryModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'mod_country';
    protected $primaryKey       = 'iCountryId';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        "iCountryId",
        "vCountry",
        "vCountryCode",
        "vCountryCodeISO_3",
        "vCountryFlag",
        "vDialCode",
        "tDescription",
        "eStatus"
    ];

    // Dates
    protected $useTimestamps = false;
	protected $dateFormat    = 'datetime';
    protected $createdField  = '';
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
