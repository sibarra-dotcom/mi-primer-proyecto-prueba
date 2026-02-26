<?php

namespace App\Models;

use CodeIgniter\Model;

class RepProdMetaModel extends Model
{
    protected $table            = 'reportes_prod_meta';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
		protected $allowedFields = [
				'ordenId',
				'fecha',
				'porcentaje_avanzado',
				'meta_dia',
				'total_producido',
				'cumplimiento',
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
	

		public function getByReporteId($reporteId)
    {
			$builder = $this->select('
					reportes_prod_tiempo.id AS t_id,
					reportes_prod_tiempo.hora_inicio,
					reportes_prod_tiempo.hora_fin,
					reportes_prod_tiempo.tiempo_paro,
					reporte_incidencia.id as incidId,
					reporte_incidencia.incidencia,
			')
			->join('reporte_incidencia', 'reporte_incidencia.id = reportes_prod_tiempo.incidId', 'left')
			->join('reportes_prod', 'reportes_prod.id = reportes_prod_tiempo.reporteId', 'left')
			->where('reportes_prod.id', $reporteId);

			return $builder->get()->getResultArray();
		}

}