<?php

namespace App\Models;

use CodeIgniter\Model;

class SorteoPeriodoModel extends Model
{
    protected $table            = 'sorteo_periodos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
		protected $allowedFields = [
				'start_date',
				'end_date',
				'fecha_ingreso',
				'fecha_corte',
				'name'
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

		public function getCurrentPeriodId()
    {
        $today = date('Y-m-d');

        $builder = $this->builder();
        $builder->where('start_date <=', $today);
        $builder->where('end_date >=', $today);
        $result = $builder->get()->getRowArray();

        return $result ? $result['id'] : null;
    }


}
