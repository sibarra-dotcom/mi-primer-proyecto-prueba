<?php

namespace App\Models;

use CodeIgniter\Model;

class ReportesPersonalModel extends Model
{
    protected $table            = 'reportes_personal';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
			'reporteId',
			'personalId',
			'horas',
		];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

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

		public function getById($reporteId)
    {
			$builder = $this->select('
					users.name, 
					users.last_name, 
					reportes_personal.horas, 
			')
			->join('users', 'users.id = reportes_personal.personalId', 'left') 
			->where('reportes_personal.reporteId', $reporteId);

			// return $builder->get()->getRowArray();
			return $builder->get()->getResultArray();

		}

}