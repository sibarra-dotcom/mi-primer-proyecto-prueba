<?php

namespace App\Models;

use CodeIgniter\Model;

class RepProdPersonalModel extends Model
{
    protected $table            = 'reportes_prod_personal';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
		protected $allowedFields = [
				'reporteId',
				'personalId',
				'puesto',
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
					reportes_prod.*,
					users.name, 
					users.last_name, 
					users.signature
			')
			->join('users', 'users.id = reportes_prod.produccionId', 'left') 
			->where('reportes_prod.id', $reporteId);

			return $builder->get()->getRowArray();
		}

}