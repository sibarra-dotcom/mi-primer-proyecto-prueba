<?php

namespace App\Models;

use CodeIgniter\Model;

class ComedorEntregaModel extends Model
{
    protected $table            = 'comedor_entregas';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
		protected $allowedFields = [
				'userId',
				'fecha',
				'nombre',
				'menu_dia',
				'menu_base',
				'observacion',
				'horario'
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

    public function checkComidaDia($user_id)
    {
        $today = date('Y-m-d');

				$builder = $this->select('
						comedor_entregas.id
				')
				->where('userId', $user_id)
        ->where('DATE(fecha)', $today);

				$records = $builder->get()->getResultArray();
				return $records;
    }

}
