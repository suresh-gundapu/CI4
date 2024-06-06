<?php

namespace App\Models;

use CodeIgniter\Model;

class ModExecutedNotificationsModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'mod_executed_notifications';
    protected $primaryKey       = 'iExecutedNotificationId';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        "iEntityId",
        "iGroupId",
        "eEntityType",
        "vReceiver",
        "eNotificationType",
        "vRedirectLink",
        "vSubject",
        "tContent",
        "tParams",
        "tError",
        "eStatus"
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'dtSendDateTime';
    protected $updatedField  = 'updated_at';
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
