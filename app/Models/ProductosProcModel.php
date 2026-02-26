<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductosProcModel extends Model
{
    protected $table            = 'productos_procesos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
				'descripcion', 'planta'
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

		public function getAll()
		{
				$builder = $this->select('
						users.name, 
						users.last_name, 
						reportes.*
						')
						->join('users', 'users.id = reportes.userId')
						->orderBy('reportes.id', 'DESC');

				return $builder->get()->getResultArray();
		}

		public function getById($procId)
    {
			$builder = $this->select('
					productos_procesos.id,
					productos_procesos.descripcion
			')
			->where('productos_procesos.id', $procId);

			return $builder->get()->getRowArray();
		}
}