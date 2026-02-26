<?php

namespace App\Models;

use CodeIgniter\Model;

class SorteoInvModel extends Model
{
    protected $table            = 'sorteo_inventario';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
		protected $allowedFields = [
				'productoId',
				'stock',
				'periodoId',
				'activo'
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

		    public function getLista($periodoId)
    {
        // Start the builder with the necessary columns
        $builder = $this->select('
                sorteo_inventario.id AS inv_id,
                sorteo_inventario.stock AS stock_total,
                sorteo_inventario.activo,
                sorteo_productos.id AS prod_id,
                sorteo_productos.nombre,
                sorteo_productos.descripcion,
                sorteo_productos.codigo,
                sorteo_productos.lote,
                sorteo_productos.color,
                sorteo_productos.imagen,
                sorteo_periodos.name AS period,
                COALESCE(COUNT(sorteo_entregas.productoId), 0) AS stock_entregado,
                (sorteo_inventario.stock - COALESCE(COUNT(sorteo_entregas.productoId), 0)) AS stock_restante
            ')
            ->join('sorteo_productos', 'sorteo_productos.id = sorteo_inventario.productoId', 'left')
            ->join('sorteo_periodos', 'sorteo_periodos.id = sorteo_inventario.periodoId', 'left')
            ->join('sorteo_entregas', 'sorteo_entregas.productoId = sorteo_productos.id', 'left')
						->where('sorteo_inventario.periodoId', $periodoId)
            ->groupBy('sorteo_inventario.id, sorteo_productos.nombre, sorteo_productos.descripcion, 
                sorteo_productos.codigo, sorteo_productos.lote, sorteo_productos.color, 
                sorteo_productos.imagen, sorteo_periodos.name, sorteo_inventario.stock')
            ->orderBy('sorteo_inventario.id', 'DESC');

        return $builder->get()->getResultArray();
    }

		public function getByProductId($productId)
    {
			$builder = $this->select('

					sorteo_productos.id,
					sorteo_productos.nombre,
					sorteo_productos.codigo,
					sorteo_productos.lote,
					sorteo_productos.color,
					sorteo_productos.imagen,
					sorteo_productos.fecha_ingreso,
					sorteo_productos.fecha_vencimiento,
					sorteo_inventario.stock,
					sorteo_inventario.periodoId,
			')
			->join('sorteo_productos', 'sorteo_productos.id = sorteo_inventario.productoId')
			->where('sorteo_inventario.productoId', $productId);

			return $builder->get()->getRowArray();
		}



				public function getLista1()
    {
			$builder = $this->select('
					sorteo_inventario.id,
					sorteo_inventario.stock,
					sorteo_productos.nombre,
					sorteo_productos.descripcion,
					sorteo_productos.codigo,
					sorteo_productos.lote,
					sorteo_productos.color,
					sorteo_productos.imagen,
					sorteo_periodos.name AS period
			')

			->join('sorteo_productos', 'sorteo_productos.id = sorteo_inventario.productoId', 'left')
			->join('sorteo_periodos', 'sorteo_periodos.id = sorteo_inventario.periodoId', 'left')

			// ->where('sorteo_periodos.periodId', 14)
			->orderBy('sorteo_inventario.id', 'DESC');

			return $builder->get()->getResultArray();
		}

}
