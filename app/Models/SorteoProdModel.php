<?php

namespace App\Models;

use CodeIgniter\Model;

class SorteoProdModel extends Model
{
    protected $table            = 'sorteo_productos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
		protected $allowedFields = [
				'nombre',
				'descripcion',
				'codigo',
				'lote',
				'color',
				'imagen',
				'fecha_ingreso',
				'fecha_vencimiento'
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


		public function getProductsByPeriod($periodoId)
    {
			$builder = $this->select('
					sorteo_productos.id,
					sorteo_productos.nombre,
					sorteo_productos.codigo,
					sorteo_productos.lote,
					sorteo_productos.color,
					sorteo_productos.imagen,
			')
			->join('sorteo_inventario', 'sorteo_inventario.productoId = sorteo_productos.id', 'left')
			->where('sorteo_inventario.periodoId', $periodoId)
			->where('sorteo_inventario.activo', 1);

			return $builder->get()->getResultArray();
		}

}
