<?php

namespace App\Models;

use CodeIgniter\Model;

class RepProdTiempoModel extends Model
{
    protected $table            = 'reportes_prod_tiempo';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
		protected $allowedFields = [
				'reporteId',
				'incidId',
				'hora_inicio',
				'hora_fin',
				'tiempo_paro',
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


		public function getIncidenciasByDateRange(string $startDate, string $endDate): array
		{
				return $this->db->table('reportes_prod_tiempo rpt')
						->select([
								'ri.id AS incidencia_id',
								'ri.incidencia',
								'COUNT(rpt.id) AS total',
								"SEC_TO_TIME(
										SUM(
												TIME_TO_SEC(
														STR_TO_DATE(rpt.tiempo_paro, '%H:%i')
												)
										)
								) AS total_tiempo_paro"
						])
						->join('reportes_prod rp', 'rp.id = rpt.reporteId')
						->join('reporte_incidencia ri', 'ri.id = rpt.incidId')
						->where('rp.created_at >=', $startDate . ' 00:00:00')
						->where('rp.created_at <=', $endDate . ' 23:59:59')
						->groupBy('ri.id')
						->orderBy('total', 'DESC')
						->get()
						->getResultArray();
		}

}