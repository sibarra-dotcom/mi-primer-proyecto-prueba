<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\SorteoPeriodoModel; 


class SorteoEntregaModel extends Model
{
    protected $table            = 'sorteo_entregas';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
		protected $allowedFields = [
				'userId',
				'productoId'
		];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
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

		public function getLista()
    {
			$builder = $this->select('
					sorteo_entregas.id,
					sorteo_productos.codigo,
					sorteo_productos.lote,
					sorteo_productos.descripcion,
					roles.rol, 
					users.name, 
					users.last_name, 
					users_operarios.turno,
					sorteo_entregas.created_at
			')

			->join('sorteo_productos', 'sorteo_productos.id = sorteo_entregas.productoId', 'left')
			->join('users', 'users.id = sorteo_entregas.userId', 'left')
			->join('roles', 'roles.id = users.rol_id')
			->join('users_operarios', 'users_operarios.userId = sorteo_entregas.userId', 'left')
			->orderBy('sorteo_entregas.id', 'DESC');

			return $builder->get()->getResultArray();
		}

		public function getListaByPeriod(array $period)
		{
				$builder = $this->select('
								sorteo_entregas.id,
								sorteo_productos.codigo,
								sorteo_productos.lote,
								sorteo_productos.descripcion,
								roles.rol, 
								users.name, 
								users.last_name, 
								users_operarios.turno,
								sorteo_entregas.created_at
						')
						->join('sorteo_productos', 'sorteo_productos.id = sorteo_entregas.productoId', 'left')
						->join('users', 'users.id = sorteo_entregas.userId', 'left')
						->join('roles', 'roles.id = users.rol_id')
						->join('users_operarios', 'users_operarios.userId = sorteo_entregas.userId', 'left')
						->where('sorteo_entregas.created_at >=', $period['fecha_ingreso'] . ' 00:00:00')
						->where('sorteo_entregas.created_at <=', $period['fecha_corte'] . ' 23:59:59')
						->orderBy('sorteo_entregas.id', 'DESC');

				return $builder->get()->getResultArray();
		}


		public function checkEntregaSorteo($userId)
		{
        $SortPeriod = new SorteoPeriodoModel();
        $currentPeriodId = $SortPeriod->getCurrentPeriodId();

				return $this->select('
										sorteo_entregas.id AS entrega_id,
										')
										->join('sorteo_inventario', 'sorteo_inventario.productoId = sorteo_entregas.productoId')
										->where('sorteo_inventario.periodoId', $currentPeriodId)
										->where('sorteo_entregas.userId', $userId)
										->where('sorteo_entregas.productoId IS NOT NULL')
										->first();
		}

}
