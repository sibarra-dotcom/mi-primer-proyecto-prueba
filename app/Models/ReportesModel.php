<?php

namespace App\Models;

use CodeIgniter\Model;

class ReportesModel extends Model
{
    protected $table            = 'reportes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
			'userId',
			'formatoId',
			'produccionId',
			'firma_produccion',
			'fecha_firma_produccion',
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

		public function getAll()
		{
				$builder = $this->select('
						users.name, 
						users.last_name, 
						reportes.*,
						CONCAT(_plantas.name, " - ", _turnos.name) AS turno,
						reportes_registros.nro_orden,
						productos.descripcion AS producto,
						productos_procesos.descripcion AS proceso,
						')
						->join('users', 'users.id = reportes.userId')
						->join('reportes_registros', 'reportes_registros.reporteId = reportes.id')
						->join('productos', 'productos.id = reportes_registros.productoId')
						->join('productos_procesos', 'productos_procesos.id = reportes_registros.procesoId')
						->join('_turnos', '_turnos.id = reportes_registros.turnoId')
      			->join('_plantas', '_plantas.id = _turnos.plantaId')
						
						// ->join('maquinaria', 'maquinaria.id = mantenimiento.maqId')
						// ->join('users', 'users.id = mantenimiento.responsableId', 'left') 
						// ->join('mant_adjunto', 'mant_adjunto.id = mantenimiento.id', 'left') 
						// ->whereIn('mantenimiento.estado_ticket', [1, 2])
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

		public function getAllByOrdenFab($num_orden)
    {
			$builder = $this->select('

					reportes.*,
					users.name, 
					users.last_name, 
			')
			->join('users', 'users.id = reportes.produccionId', 'left') 
			->where('reportes.id', $num_orden);

			return $builder->get()->getRowArray();
		}



		/**
 * Get production data grouped by day for a given month/year and nro_orden.
 *
 * - goal: last meta_cantidad per day (reportes + reportes_registros)
 * - real: sum of piezas_producidas per day (reportes_prod + ordenes_fabricacion)
 */
// public function getProductionData(int $year, int $month, string $nro_orden): array
// {
//     $db = $this->db;

//     // --- GOAL: last meta_cantidad per day ---
//     $goalSql = "
//         SELECT DATE(r.created_at) AS date, rr.meta_cantidad AS goal
//         FROM reportes r
//         JOIN reportes_registros rr ON rr.reporteId = r.id
//         JOIN (
//             SELECT DATE(r2.created_at) AS date, MAX(r2.id) AS last_report_id
//             FROM reportes r2
//             JOIN reportes_registros rr2 ON rr2.reporteId = r2.id
//             WHERE rr2.nro_orden = ?
//               AND YEAR(r2.created_at) = ?
//               AND MONTH(r2.created_at) = ?
//             GROUP BY DATE(r2.created_at)
//         ) m ON m.date = DATE(r.created_at) AND r.id = m.last_report_id
//         WHERE rr.nro_orden = ?
//         ORDER BY date ASC
//     ";

//     $goals = $db->query($goalSql, [$nro_orden, $year, $month, $nro_orden])->getResultArray();

//     // --- REAL: sum piezas_producidas per day ---
//     $realSql = "
//         SELECT DATE(rp.created_at) AS date, SUM(rp.piezas_producidas) AS real_sum
//         FROM reportes_prod rp
//         JOIN ordenes_fabricacion ofa ON ofa.id = rp.ordenId
//         WHERE ofa.num_orden = ?
//           AND YEAR(rp.created_at) = ?
//           AND MONTH(rp.created_at) = ?
//         GROUP BY DATE(rp.created_at)
//         ORDER BY date ASC
//     ";

//     $reals = $db->query($realSql, [$nro_orden, $year, $month])->getResultArray();

//     // --- Build lookup maps ---
//     $goalMap = [];
//     foreach ($goals as $g) {
//         $goalMap[$g['date']] = (int) $g['goal'];
//     }

//     $realMap = [];
//     foreach ($reals as $r) {
//         $realMap[$r['date']] = (int) $r['real_sum'];
//     }

//     // --- Merge by available dates ---
//     $allDates = array_unique(array_merge(array_keys($goalMap), array_keys($realMap)));
//     sort($allDates);

//     $production = [];
//     foreach ($allDates as $date) {
//         $production[] = [
//             'date' => $date,                        // YYYY-MM-DD
//             'goal' => $goalMap[$date] ?? 0,         // last meta_cantidad that day
//             'real' => $realMap[$date] ?? 0,         // sum piezas_producidas that day
//         ];
//     }

//     return $production;
// }



}