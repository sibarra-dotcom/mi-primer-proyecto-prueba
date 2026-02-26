<?php

namespace App\Models;

use CodeIgniter\Model;

class ReportesRegistroModel extends Model
{
    protected $table            = 'reportes_registros';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
				'reporteId',
				'turnoId',
				'turno',
				'nro_orden',
				'linea',
				'productoId',
				'codigo',
				'procesoId',
				'tipo_proceso',
				'meta_piezas',
				'meta_cantidad',
				'meta_medida',
				'real_piezas',
				'real_cantidad',
				'real_medida',
				'total_personal',
				'total_horas_extras',
				'incidencias',
				'desviaciones',
				'total_tiempo_muerto',
				'total_tiempo_efectivo',
				'observacion',
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
					reportes_registros.id,
					reportes_registros.reporteId,
					reportes_registros.turnoId,
					CONCAT(_plantas.name, " - ", _turnos.name) AS turno,
					reportes_registros.nro_orden,
					reportes_registros.linea,
					reportes_registros.productoId,
					reportes_registros.codigo,
					reportes_registros.procesoId,
					reportes_registros.tipo_proceso,
					reportes_registros.meta_piezas,
					reportes_registros.meta_cantidad,
					reportes_registros.meta_medida,
					reportes_registros.real_piezas,
					reportes_registros.real_cantidad,
					reportes_registros.real_medida,
					reportes_registros.total_personal,
					reportes_registros.total_horas_extras,
					reportes_registros.incidencias,
					reportes_registros.desviaciones,
					reportes_registros.total_tiempo_muerto,
					reportes_registros.total_tiempo_efectivo,
					reportes_registros.observacion,
					productos.descripcion AS nombre_producto, 
					productos_procesos.descripcion AS proceso
			')
			->join('productos_procesos', 'productos_procesos.id = reportes_registros.procesoId', 'inner') 
			->join('productos', 'productos.id = reportes_registros.productoId', 'inner') 
			->join('_turnos', '_turnos.id = reportes_registros.turnoId')
      ->join('_plantas', '_plantas.id = _turnos.plantaId')
			->where('reportes_registros.reporteId', $reporteId);

			return $builder->get()->getRowArray();
		}



}