<?php

namespace App\Models;

use CodeIgniter\Model;

class UserOperarioModel extends Model
{
    protected $table            = 'users_operarios';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
			'userId',
			'turno',
			'puesto',
			'empleadoId'
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

		public function getByEmpleadoId($pin)
		{
				return $this->select('
                        users.id AS user_id,
                        users.name,
                        users.last_name,
                        users_operarios.empleadoId,
                        users_operarios.turno,
                        users_operarios.puesto,
                        roles.rol
                    ')
                    ->join('users', 'users.id = users_operarios.userId')
										->join('roles', 'roles.id = users.rol_id')
                    ->where('users_operarios.empleadoId', $pin)
                    ->first();
		}

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

		public function getById($reporteId)
    {
			$builder = $this->select('

					reportes.*,
					users.name, 
					users.last_name, 
			')
			->join('users', 'users.id = reportes.produccionId', 'left') 
			->where('reportes.id', $reporteId);

			return $builder->get()->getRowArray();
		}


		
}