<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductosModel extends Model
{
    protected $table            = 'productos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
			'codigo',
			'descripcion',
			'linea',
			'unidad',
			'caja',
			'peso_volumen',
			'unidad_medida',
			'mesofilos',
			'coliformes',
			'coli',
			'hongos_levaduras',
			'color',
			'sabor',
			'olor',
			'descripcion_visual',
			'p_especifico_min',
			'p_especifico_max',
			'humedad', // polvos
			'brix_min',
			'brix_max',
			'ph_min',
			'ph_max',
			'acidez_min',
			'acidez_max',
			'densidad_min',
			'densidad_max',	
			'tiempo_desintegracion',
			'mf',
			'volumen_liquido',
			'volumen_polvo',
			'desintegracion_capsula',
			'densidad_capsula',
			'humedad_capsula',
			'contenido_capsula',
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

		public function getById($prodId)
    {
			$builder = $this->select('
					productos.id,
					productos.descripcion,
					productos.codigo,
					productos.linea,
					productos.peso_volumen,
					productos.unidad_medida,
					productos.mesofilos,
					productos.coliformes,
					productos.coli,
					productos.hongos_levaduras,
					productos.color,
					productos.sabor,
					productos.olor,
					productos.descripcion_visual,
					productos.p_especifico_min,
					productos.p_especifico_max,
					productos.humedad,
					productos.brix_min,
					productos.brix_max,
					productos.ph_min,
					productos.ph_max,
					productos.acidez_min,
					productos.acidez_max,
					productos.densidad_min,
					productos.densidad_max,
					productos.tiempo_desintegracion,
					productos.mf,
					productos.volumen_liquido,
					productos.volumen_polvo,
					productos.desintegracion_capsula,
					productos.densidad_capsula,
					productos.humedad_capsula,
					productos.contenido_capsula
			')
			->where('productos.id', $prodId);

			return $builder->get()->getRowArray();
		}


		public function getByCodigoWithRanges($prodCodigo)
		{
				$row = $this->select('
						productos.id,
						productos.descripcion,
						productos.codigo,
						productos.linea,
						productos.peso_volumen,
						productos.unidad_medida,
						productos.mesofilos,
						productos.coliformes,
						productos.coli,
						productos.hongos_levaduras,
						productos.color,
						productos.sabor,
						productos.olor,
						productos.descripcion_visual,
						productos.p_especifico_min,
						productos.p_especifico_max,
						productos.humedad,
						productos.brix_min,
						productos.brix_max,
						productos.ph_min,
						productos.ph_max,
						productos.acidez_min,
						productos.acidez_max,
						productos.densidad_min,
						productos.densidad_max,
						productos.tiempo_desintegracion,
						productos.mf,
						productos.volumen_liquido,
						productos.volumen_polvo,
						productos.desintegracion_capsula,
						productos.densidad_capsula,
						productos.humedad_capsula,
						productos.contenido_capsula
				')
				->where('productos.codigo', $prodCodigo)
				->get()
				->getRowArray();

				if (!$row) {
						return null;
				}

				$range = function ($min, $max) {
						if ($min === "N.A." && $max === "N.A.") {
								return 'N.A.';
						}

						return trim(($min ?? '') . ' - ' . ($max ?? ''), ' -');
				};

				return [
						'id' => $row['id'],
						'descripcion' => $row['descripcion'],
						'codigo' => $row['codigo'],
						'linea' => $row['linea'],
						'peso_volumen' => $row['peso_volumen'],
						'unidad_medida' => $row['unidad_medida'],
						'mesofilos' => $row['mesofilos'],
						'coliformes' => $row['coliformes'],
						'coli' => $row['coli'],
						'hongos_levaduras' => $row['hongos_levaduras'],
						'color' => !empty($row['color']) ? $row['color'] : 'N.A.',
						'humedad' => !empty($row['humedad']) ? $row['humedad'] : 'N.A.',
						'sabor' => !empty($row['sabor']) ? $row['sabor'] : 'N.A.',
						'olor' => !empty($row['olor']) ? $row['olor'] : 'N.A.',
						'tiempo_desintegracion' => !empty($row['tiempo_desintegracion']) ? $row['tiempo_desintegracion'] : 'N.A.',
						'descripcion_visual' => !empty($row['descripcion_visual']) ? $row['descripcion_visual'] : 'N.A.',

						// Ranges
						'p_especifico' => $range($row['p_especifico_min'], $row['p_especifico_max']),
						'brix'         => $range($row['brix_min'], $row['brix_max']),
						'ph'           => $range($row['ph_min'], $row['ph_max']),
						'acidez'       => $range($row['acidez_min'], $row['acidez_max']),
						'densidad'     => $range($row['densidad_min'], $row['densidad_max']),
				];
		}

}