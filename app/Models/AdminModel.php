<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'mod_admin';
	protected $primaryKey           = 'iAdminId';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'array';
	protected $useSoftDelete        = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		"vName", 
		"vEmail", 
		"vUserName",
        "vPassword",
        "vPhonenumber",
		"iGroupId",
		"eAuthType",
		"vAuthCode",
		"vOTPCode",
		"eEmailVerified",
		"vVerificationCode",
		"isTemporaryPassword",
		"dPasswordExpiredOn",
        "eStatus",
		"iLoginFailedAttempts",
		"dLoginLockedUntil"
	];

	// Dates
	protected $useTimestamps        = false;
	protected $dateFormat           = 'datetime';
	protected $createdField         = 'dtAddedDate';
	protected $updatedField         = 'dtModifiedDate';
	protected $deletedField         = 'iSysRecDeleted';

	// Validation
	protected $validationRules      = [];
	protected $validationMessages   = [];
	protected $skipValidation       = false;
	protected $cleanValidationRules = true;

	// Callbacks
	protected $allowCallbacks       = true;
	protected $beforeInsert         = [];
	protected $afterInsert          = [];
	protected $beforeUpdate         = [];
	protected $afterUpdate          = [];
	protected $beforeFind           = [];
	protected $afterFind            = [];
	protected $beforeDelete         = [];
	protected $afterDelete          = [];
}