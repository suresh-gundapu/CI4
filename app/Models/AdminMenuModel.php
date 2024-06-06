<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminMenuModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'mod_admin_menu';
    protected $primaryKey       = 'iAdminMenuId';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        "iAdminMenuId",
        "iParentId",
        "vMenuDisplay",
        "vIcon",
        "vURL",
        "eOpen",
        "eMenuType",
        "iCapabilityId",
        "vCapabilityCode",
        "vModuleName",
        "vDashBoardPage",
        "vUniqueMenuCode",
        "iColumnNumber",
        "iSequenceOrder",
        "eStatus"
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
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
