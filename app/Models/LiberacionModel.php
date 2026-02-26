<?php

namespace App\Models;

use CodeIgniter\Model;

class LiberacionModel extends Model
{
    protected $table            = 'liberaciones';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
			'ordenId',
			'userId',
			'produccionId',
			'calidadId',
			'desarrolloId',
			'laboratorioId',
			'firma_produccion',
			'fecha_firma_produccion',
			'firma_calidad',
			'fecha_firma_calidad',
			'firma_desarrollo',
			'fecha_firma_desarrollo',
			'firma_laboratorio',
			'fecha_firma_laboratorio',
			'especificacion',
			'hora_inicio',
			'hora_fin',
			'fecha',
			'descripcion_visual',
			'color',
			'sabor',
			'aroma',
			'humedad',
			'densidad',	
			'ph',
			'brix',
			'acidez',
			'tiempo_desintegracion',
			'lote_producto',
			'dilusion'
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

		public function getAll($ordenId, $lib_id = null)
    {
			$builder = $this->select('
					liberaciones.id,
					liberaciones.ordenId,
					liberaciones.userId,
					liberaciones.produccionId,
					liberaciones.calidadId,
					liberaciones.desarrolloId,
					liberaciones.laboratorioId,
					liberaciones.firma_produccion,
					liberaciones.fecha_firma_produccion,
					liberaciones.firma_calidad,
					liberaciones.fecha_firma_calidad,
					liberaciones.firma_desarrollo,
					liberaciones.fecha_firma_desarrollo,
					liberaciones.firma_laboratorio,
					liberaciones.fecha_firma_laboratorio,
					liberaciones.especificacion,
					liberaciones.hora_inicio,
					liberaciones.hora_fin,
					liberaciones.fecha,
					liberaciones.descripcion_visual,
					liberaciones.color,
					liberaciones.sabor,
					liberaciones.aroma,
					liberaciones.humedad,
					liberaciones.densidad,
					liberaciones.ph,
					liberaciones.brix,
					liberaciones.acidez,
					liberaciones.tiempo_desintegracion,
					liberaciones.lote_producto,
					liberaciones.dilusion,
			')
			->where('liberaciones.ordenId', $ordenId);
			// ->orderBy('liberaciones.id', 'DESC');

			if ($lib_id !== null) {
				$builder->where('liberaciones.id', $lib_id);
				return $builder->get()->getRowArray();
			} else {
				return $builder->get()->getResultArray();
			}

		}
}