<?php

namespace App\Models;

use CodeIgniter\Model;

class OrdenFabModel extends Model
{
    protected $table            = 'ordenes_fabricacion';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
		protected $allowedFields = [
				'userId',
				'num_orden',
				'fecha_vencimiento',
				'fecha_compromiso',
				'omg',
				'codigo_cliente',
				'pedido',
				'tipo_orden',
				'nombre_deudor',
				'status_pedido',
				'origen',
				'cantidad_plan',
				'num_articulo',
				'desc_articulo',
				'lote',
				'rango_min',
				'rango_ideal',
				'rango_max',
				'caducidad',
				'rfc_cliente',
				'num_piezas',
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

		public function getAll1()
		{
				$builder = $this->select('
						users.name, 
						users.last_name, 
						reportes.*
						')
						->join('users', 'users.id = reportes.userId')
						->orderBy('reportes.id', 'DESC');

				return $builder->get()->getResultArray();
		}

		public function getAll($searchParams = [])
    {
				$builder = $this->db->table('ordenes_fabricacion of')
						->select("
								of.id,
								of.num_orden,
								of.desc_articulo,
								of.num_articulo,
								of.lote,
								of.nombre_deudor,
								of.created_at,
								of.status_pedido,
								MIN(rp.created_at) AS fecha_primer_reporte,
								MAX(rp.created_at) AS fecha_ultimo_reporte
						")
						->join('reportes_prod rp', 'rp.ordenId = of.id', 'left')
						->groupBy('of.id') 
						->orderBy('of.id', 'DESC');


        // === Apply search filters ===
        if (!empty($searchParams)) {
            if (!empty($searchParams['num_orden'])) {
                $builder->like('of.num_orden', $searchParams['num_orden']);
            }

            if (!empty($searchParams['num_articulo'])) {
                $builder->like('of.num_articulo', $searchParams['num_articulo']);
            }

            if (!empty($searchParams['lote'])) {
                $builder->like('of.lote', $searchParams['lote']);
            }

            if (!empty($searchParams['desc_articulo'])) {
                $builder->like('of.desc_articulo', $searchParams['desc_articulo']);
            }

            if (!empty($searchParams['nombre_deudor'])) {
                $builder->like('of.nombre_deudor', $searchParams['nombre_deudor']);
            }

            if (!empty($searchParams['status_pedido'])) {
                $builder->like('of.status_pedido', $searchParams['status_pedido']);
            }

						if (!empty($searchParams['created_at'])) {
								$builder->where('DATE(of.created_at)', $searchParams['created_at']);
						}

						if (!empty($searchParams['fecha_primer_reporte'])) {
								$builder->where('DATE(rp.created_at)', $searchParams['fecha_primer_reporte']);
						}

						if (!empty($searchParams['fecha_ultimo_reporte'])) {
								$builder->where('DATE(rp.created_at)', $searchParams['fecha_ultimo_reporte']);
						}

						// Optional: Search by range
						// if (!empty($searchParams['fecha_inicio']) && !empty($searchParams['fecha_fin'])) {
						// 		$builder->where("DATE(rp.created_at) >=", $searchParams['fecha_inicio'])
						// 						->where("DATE(rp.created_at) <=", $searchParams['fecha_fin']);
						// }

        }

        return $builder->get()->getResultArray();
    }


		public function getById($ordenId)
    {
			$builder = $this->select('
					ordenes_fabricacion.id,
					ordenes_fabricacion.num_orden,
					ordenes_fabricacion.fecha_vencimiento,
					ordenes_fabricacion.fecha_compromiso,
					ordenes_fabricacion.codigo_cliente,
					ordenes_fabricacion.pedido,
					ordenes_fabricacion.tipo_orden,
					ordenes_fabricacion.nombre_deudor,
					ordenes_fabricacion.status_pedido,
					ordenes_fabricacion.origen,
					ordenes_fabricacion.cantidad_plan,
					ordenes_fabricacion.num_articulo,
					ordenes_fabricacion.desc_articulo,
					ordenes_fabricacion.lote,
					ordenes_fabricacion.rango_min,
					ordenes_fabricacion.rango_ideal,
					ordenes_fabricacion.rango_max,
					ordenes_fabricacion.caducidad,
					ordenes_fabricacion.rfc_cliente,
					ordenes_fabricacion.num_piezas,
					ordenes_fabricacion.omg,
			')
			->where('ordenes_fabricacion.id', $ordenId);

			return $builder->get()->getRowArray();
		}

		public function getByTurnoIdDate1($turnoId, $date)
    {
			$subQuery = $this->db->table('reportes_prod_insp')
        ->select('reporteId, SUM(merma) AS total_merma')
        ->groupBy('reporteId');

			$builder = $this->select('
					ordenes_fabricacion.id AS orden_fab_id,
					ordenes_fabricacion.num_orden,
					ordenes_fabricacion.cantidad_plan,
					ordenes_fabricacion.num_articulo,
					ordenes_fabricacion.desc_articulo,
					reportes_prod.id AS reporte_prod_id,
					reportes_prod.linea,
					reportes_prod.piezas_producidas,
					reportes_prod.muestras,
					reportes_prod.piezas_acumuladas,
					reportes_prod.colectiva,
					COALESCE(NULLIF(reportes_prod.obs_reporte, ""), "-") AS obs_reporte,
					COALESCE(reportes_prod_insp_suma.total_merma, 0) AS total_merma
			')
			->join('reportes_prod', 'reportes_prod.ordenId = ordenes_fabricacion.id', 'inner')
			->join('(' . $subQuery->getCompiledSelect() . ') AS reportes_prod_insp_suma', 'reportes_prod_insp_suma.reporteId = reportes_prod.id', 'left')
			->where('reportes_prod.turnoId', $turnoId)
			->where('DATE(reportes_prod.created_at)', $date)
    	->orderBy('reportes_prod.id', 'DESC');

			return $builder->get()->getResultArray();

		}


public function getByTurnoIdDate($turnoId, $date)
{
    /*
     * Subquery: suma de merma por reporte
     */
    $mermaSubQuery = $this->db->table('reportes_prod_insp')
        ->select('reporteId, SUM(merma) AS total_merma')
        ->groupBy('reporteId');

    /*
     * Subquery: piezas acumuladas por orden hasta la fecha
     */
    $acumuladosSubQuery = $this->db->table('reportes_prod')
        ->select([
            'ordenId',
            'SUM(piezas_producidas) AS acumuladas_sin_muestras',
            'SUM(piezas_producidas + muestras) AS acumuladas_con_muestras'
        ])
        ->where('DATE(created_at) <=', $date)
        ->groupBy('ordenId');

    /*
     * Main query
     */
    $builder = $this->select([
            'ordenes_fabricacion.id AS orden_fab_id',
            'ordenes_fabricacion.num_orden',
            'ordenes_fabricacion.cantidad_plan',
            'ordenes_fabricacion.num_articulo',
            'ordenes_fabricacion.desc_articulo',

            'reportes_prod.id AS reporte_prod_id',
            'reportes_prod.linea',
            'reportes_prod.piezas_producidas',
            'reportes_prod.muestras',

            'COALESCE(acumulados.acumuladas_sin_muestras, 0) AS piezas_acumuladas_sin_muestras',
            'COALESCE(acumulados.acumuladas_con_muestras, 0) AS piezas_acumuladas_con_muestras',

            'reportes_prod.colectiva',
            'COALESCE(NULLIF(reportes_prod.obs_reporte, ""), "-") AS obs_reporte',
            'COALESCE(mermas.total_merma, 0) AS total_merma'
        ])
        ->join('reportes_prod', 'reportes_prod.ordenId = ordenes_fabricacion.id', 'inner')
        ->join(
            '(' . $acumuladosSubQuery->getCompiledSelect() . ') AS acumulados',
            'acumulados.ordenId = ordenes_fabricacion.id',
            'left'
        )
        ->join(
            '(' . $mermaSubQuery->getCompiledSelect() . ') AS mermas',
            'mermas.reporteId = reportes_prod.id',
            'left'
        )
        ->where('reportes_prod.turnoId', $turnoId)
        ->where('DATE(reportes_prod.created_at)', $date)
        ->orderBy('reportes_prod.id', 'DESC');

    return $builder->get()->getResultArray();
}





		public function getReporteByDate($date)
    {
			$subQuery = $this->db->table('reportes_prod_insp')
        ->select('reporteId, SUM(merma) AS total_merma')
        ->groupBy('reporteId');

			$builder = $this->select('
					_plantas.name AS planta_name,
					_turnos.name AS turno_name,
					ordenes_fabricacion.id AS orden_fab_id,
					ordenes_fabricacion.num_orden,
					ordenes_fabricacion.cantidad_plan,
					ordenes_fabricacion.num_articulo,
					ordenes_fabricacion.desc_articulo,
					reportes_prod_meta.fecha,
					reportes_prod_meta.porcentaje_avanzado,
					reportes_prod_meta.meta_dia,
					reportes_prod_meta.total_producido,
					reportes_prod_meta.cumplimiento,
					COALESCE(NULLIF(reportes_prod.obs_reporte, ""), "-") AS obs_reporte,
					COALESCE(reportes_prod_insp_suma.total_merma, 0) AS total_merma
			')
			

			->join('reportes_prod_meta', 'reportes_prod_meta.ordenId = ordenes_fabricacion.id')
			->join('reportes_prod', 'reportes_prod.ordenId = ordenes_fabricacion.id', 'inner')
			->join('(' . $subQuery->getCompiledSelect() . ') AS reportes_prod_insp_suma', 'reportes_prod_insp_suma.reporteId = reportes_prod.id', 'left')
			->join('_turnos', '_turnos.id = reportes_prod.turnoId')
			->join('_plantas', '_plantas.id = _turnos.plantaId')

			->where('DATE(reportes_prod_meta.fecha)', $date)
    	->groupBy('_plantas.name') 
    	->orderBy('_plantas.id');

			return $builder->get()->getResultArray();
		}



/**
 * Get fechas and status information for a given orden de fabricación
 */
public function getFechasOrdenFab(string $nro_orden): array
{
    $db = $this->db;

    // === Get ordenId + fecha_compromiso ===
    $orden = $db->table('ordenes_fabricacion')
        ->select('id, fecha_compromiso')
        ->where('num_orden', $nro_orden)
        ->get()
        ->getRowArray();

    if (!$orden) {
        return [];
    }

    $ordenId = $orden['id'];
    $fechaCompromiso = $orden['fecha_compromiso']
        ? date('d-m-Y', strtotime($orden['fecha_compromiso']))
        : null;

    // === Get fecha_arranque (first created_at) ===
    $fechaArranqueRow = $db->table('reportes_prod')
        ->select('created_at')
        ->where('ordenId', $ordenId)
        ->orderBy('created_at', 'ASC')
        ->limit(1)
        ->get()
        ->getRowArray();

    $fechaArranque = $fechaArranqueRow
        ? date('d-m-Y', strtotime($fechaArranqueRow['created_at']))
        : null;

    // === Get last status record ===
    $lastStatusRow = $db->table('reportes_prod')
        ->select('status_fabricacion, created_at')
        ->where('ordenId', $ordenId)
        ->orderBy('created_at', 'DESC')
        ->limit(1)
        ->get()
        ->getRowArray();

    $fechaTermino = [
        'color' => 'gray',
        'fecha' => 'PENDIENTE',
    ];

    $statusOrden = [
        'status' => 'EN PROCESO',
        'color'  => 'warning',
    ];

    $diferenciaCompromiso = [
        'color' => 'gray',
        'time'  => '--:--',
    ];

    if ($lastStatusRow) {
        $status = strtoupper($lastStatusRow['status_fabricacion']);
        $statusOrden['status'] = $status;

        // === Status color ===
        if ($status === 'FINALIZADO') {
            $statusOrden['color'] = 'icon';

            $fechaFin = $lastStatusRow['created_at'];
            $fechaTermino = [
                'color' => 'dark',
                'fecha' => date('d-m-Y', strtotime($fechaFin)),
            ];

            // === Diferencia compromiso ===
            if ($orden['fecha_compromiso']) {
                $dtCompromiso = new \DateTime($orden['fecha_compromiso']);
                $dtTermino    = new \DateTime($fechaFin);

                // Difference in seconds
                $secondsDiff = $dtTermino->getTimestamp() - $dtCompromiso->getTimestamp();
                $absDiff     = abs($secondsDiff);

                $days  = floor($absDiff / 86400);
                $hours = floor(($absDiff % 86400) / 3600);
                $mins  = floor(($absDiff % 3600) / 60);

                $sign = $secondsDiff <= 0 ? '+' : '-';
                $timeFmt = $sign . ($days > 0 ? $days . ' dia' . ($days > 1 ? 's' : '') . ', ' : '')
                          . ($hours > 0 ? $hours . ' h, ' : '')
                          . ($mins > 0 ? $mins . ' min' : '0 min');

                // === Colors ===
                if ($secondsDiff <= 0) {
                    // Terminado antes del compromiso
                    $color = 'icon'; // green
                } else {
                    // Terminado después del compromiso
                    $totalMinutes = floor($absDiff / 60);
                    if ($totalMinutes <= 24 * 60) {
                        $color = 'icon';
                    } elseif ($totalMinutes <= 48 * 60) {
                        $color = 'warning';
                    } else {
                        $color = 'error';
                    }
                }

                $diferenciaCompromiso = [
                    'color' => $color,
                    'time'  => $timeFmt,
                ];
            }
        } elseif ($status === 'EN PROCESO') {
            $statusOrden['color'] = 'warning';
        } elseif ($status === 'CANCELADO') {
            $statusOrden['color'] = 'error';
        }
    }

    return [
        'fecha_compromiso'      => $fechaCompromiso,
        'fecha_arranque'        => $fechaArranque,
        'fecha_termino'         => $fechaTermino,
        'diferencia_compromiso' => $diferenciaCompromiso,
        'status_orden'          => $statusOrden,
    ];
}





/**
 * Get personal and time metrics for a given production order (num_orden)
 *
 * Returns:
 * [
 *   'operadores_requeridos' => int,
 *   'duracion_total' => ['dias' => int, 'horas' => int, 'minutos' => int],
 *   'tiempo_efectivo' => ['horas' => float, 'porcentaje' => int],
 *   'tiempo_muerto' => ['dias' => int, 'horas' => int, 'minutos' => int],
 * ]
 */
public function getPersonalTiempos(string $num_orden): array
{
    $db = $this->db;

    // 1) Get ordenId
    $ordenRow = $db->table('ordenes_fabricacion')
        ->select('id')
        ->where('num_orden', $num_orden)
        ->limit(1)
        ->get()
        ->getRowArray();

    if (!$ordenRow) {
        return [
            'operadores_requeridos' => 0,
            'duracion_total'        => ['dias' => 0, 'horas' => 0, 'minutos' => 0],
            'tiempo_efectivo'      => ['horas' => 0, 'porcentaje' => 0],
            'tiempo_muerto'        => ['dias' => 0, 'horas' => 0, 'minutos' => 0],
        ];
    }

    $ordenId = (int) $ordenRow['id'];

    // 2) Count distinct operadores (personalId) for all reportes_prod of this ordenId
    $sqlOperadores = "
        SELECT COUNT(DISTINCT rpp.personalId) AS operadores
        FROM reportes_prod_personal rpp
        JOIN reportes_prod rp ON rp.id = rpp.reporteId
        WHERE rp.ordenId = ?
    ";
    $opRow = $db->query($sqlOperadores, [$ordenId])->getRowArray();
    $operadores = isset($opRow['operadores']) ? (int)$opRow['operadores'] : 0;

    // 3) Sum total duration from reportes_prod (hora_inicio_registro -> hora_fin_registro) in seconds
    // Handle crossing-midnight by adding 86400 seconds when TIMEDIFF is negative.
    $sqlTotalSecs = "
        SELECT COALESCE(SUM(
            CASE
                WHEN rp.hora_fin_registro IS NULL OR rp.hora_inicio_registro IS NULL THEN 0
                ELSE
                    CASE
                        WHEN TIME_TO_SEC(TIMEDIFF(rp.hora_fin_registro, rp.hora_inicio_registro)) < 0
                        THEN TIME_TO_SEC(TIMEDIFF(rp.hora_fin_registro, rp.hora_inicio_registro)) + 86400
                        ELSE TIME_TO_SEC(TIMEDIFF(rp.hora_fin_registro, rp.hora_inicio_registro))
                    END
            END
        ), 0) AS total_secs
        FROM reportes_prod rp
        WHERE rp.ordenId = ?
    ";
    $totRow = $db->query($sqlTotalSecs, [$ordenId])->getRowArray();
    $totalSecs = isset($totRow['total_secs']) ? (int)$totRow['total_secs'] : 0;

    // 4) Sum tiempo muerto from reportes_prod_tiempo (hora_fin - hora_inicio) for those reportes_prod
    $sqlDeadSecs = "
        SELECT COALESCE(SUM(
            CASE
                WHEN rpt.hora_fin IS NULL OR rpt.hora_inicio IS NULL THEN 0
                ELSE
                    CASE
                        WHEN TIME_TO_SEC(TIMEDIFF(rpt.hora_fin, rpt.hora_inicio)) < 0
                        THEN TIME_TO_SEC(TIMEDIFF(rpt.hora_fin, rpt.hora_inicio)) + 86400
                        ELSE TIME_TO_SEC(TIMEDIFF(rpt.hora_fin, rpt.hora_inicio))
                    END
            END
        ), 0) AS dead_secs
        FROM reportes_prod_tiempo rpt
        JOIN reportes_prod rp ON rp.id = rpt.reporteId
        WHERE rp.ordenId = ?
    ";
    $deadRow = $db->query($sqlDeadSecs, [$ordenId])->getRowArray();
    $deadSecs = isset($deadRow['dead_secs']) ? (int)$deadRow['dead_secs'] : 0;

    // 5) Compute effective time in seconds (ensure not negative)
    $effectiveSecs = max(0, $totalSecs - $deadSecs);

    // Helper to split seconds -> days,hours,minutes
    $secsToDHm = function(int $secs) {
        $days = (int) floor($secs / 86400);
        $secs -= $days * 86400;
        $hours = (int) floor($secs / 3600);
        $secs -= $hours * 3600;
        $minutes = (int) floor($secs / 60);
        return ['dias' => $days, 'horas' => $hours, 'minutos' => $minutes];
    };

    $duracion_total = $secsToDHm($totalSecs);
    $tiempo_muerto  = $secsToDHm($deadSecs);

    // tiempo_efectivo.horas as decimal (2 decimals) and porcentaje relative to totalSecs
    $effectiveHours = $totalSecs > 0 ? round($effectiveSecs / 3600, 2) : 0.0;

    $porcentaje = 0;
    if ($totalSecs > 0) {
        $porcentaje = (int) round(($effectiveSecs / $totalSecs) * 100);
    }

    $tiempo_efectivo = [
        'horas' => $effectiveHours,
        'porcentaje' => $porcentaje,
    ];

    return [
        'operadores_requeridos' => $operadores,
        'duracion_total'        => $duracion_total,
        'tiempo_efectivo'      => $tiempo_efectivo,
        'tiempo_muerto'        => $tiempo_muerto,
    ];
}




		/**
 * Get production data grouped by day for a given month/year and nro_orden.
 *
 * - goal: meta_dia from reportes_prod_meta (one record per fecha/ordenId)
 * - real: sum of piezas_producidas from reportes_prod grouped by date
 */
public function getProductionData(int $year, int $month, string $nro_orden): array
{
    $db = $this->db;

    // --- Get ordenId ---
    $orden = $db->table('ordenes_fabricacion')
        ->select('id')
        ->where('num_orden', $nro_orden)
        ->get()
        ->getRowArray();

    if (!$orden) {
        return [];
    }

    $ordenId = $orden['id'];

    // --- GOAL: meta_dia per day ---
    $goalSql = "
        SELECT DATE(rpm.fecha) AS date, rpm.meta_dia AS goal
        FROM reportes_prod_meta rpm
        WHERE rpm.ordenId = ?
          AND YEAR(rpm.fecha) = ?
          AND MONTH(rpm.fecha) = ?
        ORDER BY date ASC
    ";
    $goals = $db->query($goalSql, [$ordenId, $year, $month])->getResultArray();

    // --- REAL: sum piezas_producidas per day ---
    $realSql = "
        SELECT DATE(rp.created_at) AS date, SUM(rp.piezas_producidas) AS real_sum
        FROM reportes_prod rp
        WHERE rp.ordenId = ?
          AND YEAR(rp.created_at) = ?
          AND MONTH(rp.created_at) = ?
        GROUP BY DATE(rp.created_at)
        ORDER BY date ASC
    ";
    $reals = $db->query($realSql, [$ordenId, $year, $month])->getResultArray();

    // --- Build lookup maps ---
    $goalMap = [];
    foreach ($goals as $g) {
        $goalMap[$g['date']] = (int) $g['goal'];
    }

    $realMap = [];
    foreach ($reals as $r) {
        $realMap[$r['date']] = (int) $r['real_sum'];
    }

    // --- Merge by available dates ---
    $allDates = array_unique(array_merge(array_keys($goalMap), array_keys($realMap)));
    sort($allDates);

    $production = [];
    foreach ($allDates as $date) {
        $production[] = [
            'date' => $date,                   // YYYY-MM-DD
            'goal' => $goalMap[$date] ?? 0,    // meta_dia
            'real' => $realMap[$date] ?? 0,    // sum piezas_producidas
        ];
    }

    return $production;
}




/**
 * Get total piezas_producidas, scrap percentage and standard scrap rate for a given nro_orden
 */
public function getTotalPiezas(string $nro_orden): array
{
    $db = $this->db;

    $sql = "
        SELECT 
            COALESCE(SUM(rp.piezas_producidas), 0) AS total,
            COALESCE(SUM(rp.muestras), 0) AS muestras
        FROM reportes_prod rp
        JOIN ordenes_fabricacion ofa ON ofa.id = rp.ordenId
        WHERE ofa.num_orden = ?
    ";

    $row = $db->query($sql, [$nro_orden])->getRowArray();

    $total    = isset($row['total']) ? (int) $row['total'] : 0;
    $muestras = isset($row['muestras']) ? (int) $row['muestras'] : 0;

    // ✅ scrap relative to good output
    $scrapt = $total > 0
        ? number_format(($muestras / $total) * 100, 2) . '%'
        : '0.00%';

    // ✅ standard scrap relative to total produced (good + scrap)
    $total_con_scrap = $total + $muestras;
    $scrap_std = $total_con_scrap > 0
        ? number_format(($muestras / $total_con_scrap) * 100, 2) . '%'
        : '0.00%';

    return [
        'total'      => $total,
        'muestras'   => $muestras,
        'scrapt'     => $scrapt,
        'scrap_std'  => $scrap_std,
    ];
}



/**
 * Get total merma for a given num_orden
 */
public function getTotalMerma(string $num_orden): array
{
    $db = $this->db;

    // 1) Get total_merma
    $mermaRow = $db->table('reportes_prod_insp rpi')
        ->select('COALESCE(SUM(CAST(rpi.merma AS UNSIGNED)), 0) AS total_merma')
        ->join('reportes_prod rp', 'rp.id = rpi.reporteId', 'inner')
        ->join('ordenes_fabricacion of', 'of.id = rp.ordenId', 'inner')
        ->where('of.num_orden', $num_orden)
        ->get()
        ->getRowArray();

    $totalMerma = isset($mermaRow['total_merma']) ? (int)$mermaRow['total_merma'] : 0;

    // 2) Get total_piezas_producidas
    $piezasRow = $db->table('ordenes_fabricacion of')
        ->select('COALESCE(SUM(rp.piezas_producidas), 0) AS total_piezas')
        ->join('reportes_prod rp', 'rp.ordenId = of.id', 'inner')
        ->where('of.num_orden', $num_orden)
        ->get()
        ->getRowArray();

    $totalPiezas = isset($piezasRow['total_piezas']) ? (int)$piezasRow['total_piezas'] : 0;

    // 3) Calculate %
    $percent = ($totalPiezas > 0)
        ? round(($totalMerma / $totalPiezas) * 100, 2)
        : 0;

    return [
        'merma_percent' => $percent . '%',
    ];
}

public function getDailyMeta($date)
{
    /* ===============================
     * Subquery: Total producido por ORDEN
     * =============================== */
    $subQueryProd = $this->db->table('reportes_prod rp')
        ->select('rp.ordenId, SUM(rp.piezas_producidas) AS total_producido')
        ->where('DATE(rp.created_at)', $date)
        ->groupBy('rp.ordenId');

    /* ===============================
     * Subquery: Total merma por ORDEN
     * =============================== */
    $subQueryMerma = $this->db->table('reportes_prod_insp rpi')
        ->select('rp.ordenId, SUM(rpi.merma) AS total_merma')
        ->join('reportes_prod rp', 'rp.id = rpi.reporteId', 'inner')
        ->where('DATE(rp.created_at)', $date)
        ->groupBy('rp.ordenId');

    /* ===============================
     * Main query
     * =============================== */
    $builder = $this->db->table('ordenes_fabricacion of')
        ->select("
            of.id AS orden_fab_id,
            of.num_orden,
            of.cantidad_plan,
            of.num_articulo,
            of.desc_articulo AS producto,
            p.name AS planta,
            '{$date}' AS fecha,
            COALESCE(prod.total_producido, 0) AS total_producido,
            COALESCE(merma.total_merma, 0) AS total_merma,
            COALESCE(meta.meta_dia, 0) AS meta_del_dia
        ")
        ->join('reportes_prod rp', 'rp.ordenId = of.id', 'inner')
        ->join('_turnos t', 't.id = rp.turnoId', 'inner')
        ->join('_plantas p', 'p.id = t.plantaId', 'inner')
        ->join(
            '(' . $subQueryProd->getCompiledSelect() . ') AS prod',
            'prod.ordenId = of.id',
            'left'
        )
        ->join(
            '(' . $subQueryMerma->getCompiledSelect() . ') AS merma',
            'merma.ordenId = of.id',
            'left'
        )
        ->join(
            'reportes_prod_meta meta',
            'meta.ordenId = of.id AND DATE(meta.fecha) = "' . $date . '"',
            'left'
        )
        ->where('DATE(rp.created_at)', $date)
        ->groupBy('of.id, of.num_orden, p.name, meta.meta_dia')
        ->orderBy('p.name')
        ->orderBy('of.num_orden');

    return $builder->get()->getResultArray();
}



public function getDailyMeta1($date)
{
    // Subquery: total producido per producto
    $subQueryProd = $this->db->table('reportes_prod rp')
        ->select('of.desc_articulo, SUM(rp.piezas_producidas) AS total_producido')
        ->join('ordenes_fabricacion of', 'of.id = rp.ordenId', 'inner')
        ->where('DATE(rp.created_at)', $date)
        ->groupBy('of.desc_articulo');

    // Subquery: total merma per producto
    $subQueryMerma = $this->db->table('reportes_prod_insp rpi')
        ->select('of.desc_articulo, SUM(rpi.merma) AS total_merma')
        ->join('reportes_prod rp', 'rp.id = rpi.reporteId', 'inner')
        ->join('ordenes_fabricacion of', 'of.id = rp.ordenId', 'inner')
        ->where('DATE(rp.created_at)', $date)
        ->groupBy('of.desc_articulo');

    $builder = $this->db->table('ordenes_fabricacion of')
        ->select("
            of.id AS orden_fab_id,
            of.num_orden,
            of.cantidad_plan,
            of.num_articulo,
            of.desc_articulo AS producto,
            p.name AS planta,
            '{$date}' AS fecha,
            COALESCE(prod.total_producido, 0) AS total_producido,
            COALESCE(merma.total_merma, 0) AS total_merma,
            COALESCE(meta.meta_dia, 0) AS meta_del_dia
        ")
        ->join('reportes_prod rp', 'rp.ordenId = of.id', 'inner')
        ->join('_turnos t', 't.id = rp.turnoId', 'inner')
        ->join('_plantas p', 'p.id = t.plantaId', 'inner')
        ->join('(' . $subQueryProd->getCompiledSelect() . ') AS prod', 'prod.desc_articulo = of.desc_articulo', 'left')
        ->join('(' . $subQueryMerma->getCompiledSelect() . ') AS merma', 'merma.desc_articulo = of.desc_articulo', 'left')
        ->join('reportes_prod_meta meta', 'meta.ordenId = of.id AND DATE(meta.fecha) = "' . $date . '"', 'left')
        ->where('DATE(rp.created_at)', $date)
        ->groupBy('p.name, of.desc_articulo, meta.meta_dia')
        ->orderBy('p.name')
        ->orderBy('of.desc_articulo');

    return $builder->get()->getResultArray();
}



public function getDonutData(string $nro_orden): array
{
    $db = $this->db;

    // 1) Get ordenId
    $orden = $db->table('ordenes_fabricacion')
        ->select('id')
        ->where('num_orden', $nro_orden)
        ->get()
        ->getRowArray();

    if (!$orden) {
        return [
            "labels" => [],
            "series" => [],
        ];
    }

    $ordenId = $orden['id'];

    // 2) Aggregate piezas_producidas by turno
    $builder = $db->table('reportes_prod rp')
        ->select('t.name as turno, SUM(rp.piezas_producidas) as total')
        ->join('_turnos t', 't.id = rp.turnoId')
        ->where('rp.ordenId', $ordenId)
        ->groupBy('t.name, t.id')
        ->orderBy('t.id', 'ASC');

    $result = $builder->get()->getResultArray();

    // 3) Build labels + series arrays
    $labels = [];
    $series = [];

    foreach ($result as $row) {
        $labels[] = $row['turno'];
        $series[] = (int) $row['total'];
    }

    return [
        "labels" => $labels,
        "series" => $series,
    ];
}




public function getGaugeData(string $num_orden): array
{
    $orden = $this->db->table('ordenes_fabricacion')
        ->select('id, cantidad_plan')
        ->where('num_orden', $num_orden)
        ->get()
        ->getRowArray();

    if (!$orden) {
        return [
            "label" => "Avance",
            "value" => 0
        ];
    }

    // Clean cantidad_plan: "1,000 PIEZAS" → 1000
    $cantidadPlan = preg_replace('/[^0-9]/', '', $orden['cantidad_plan']);
    $cantidadPlan = (int) $cantidadPlan;

    // === Get produced pieces ===
    $produced = $this->db->table('reportes_prod')
        ->selectSum('piezas_producidas', 'total')
        ->where('ordenId', $orden['id'])
        ->get()
        ->getRowArray();

    $producedTotal = (int) ($produced['total'] ?? 0);

    // === Calculate % ===
    $value = $cantidadPlan > 0 ? round(($producedTotal / $cantidadPlan) * 100, 2) : 0;

    return [
        "label" => "Avance",
        "value" => $value
    ];
}


// public function getIncidenciasData(string $num_orden): array
// {
//     $orden = $this->db->table('ordenes_fabricacion')
//         ->select('id')
//         ->where('num_orden', $num_orden)
//         ->get()
//         ->getRowArray();

//     if (!$orden) {
//         return [
//             'incidencias' => [],
//             'total_incidencias' => 0,
//         ];
//     }

//     $builder = $this->db->table('reportes_prod rp')
//         ->select('ri.incidencia, COUNT(rpt.id) as total')
//         ->join('reportes_prod_tiempo rpt', 'rpt.reporteId = rp.id')
//         ->join('reporte_incidencia ri', 'ri.id = rpt.incidId')
//         ->where('rp.ordenId', $orden['id'])
//         ->groupBy('ri.incidencia')
//         ->orderBy('total', 'DESC');

//     $result = $builder->get()->getResultArray();

//     $incidencias = [];
//     $totalIncidencias = 0;

//     foreach ($result as $row) {
//         $incidencias[$row['incidencia']] = (int) $row['total'];
//         $totalIncidencias += (int) $row['total'];
//     }

//     return [
//         'incidencias' => $incidencias,
//         'total_incidencias' => $totalIncidencias,
//     ];
// }

public function getIncidenciasData(string $num_orden): array
{
    // 1. Get orden ID
    $orden = $this->db->table('ordenes_fabricacion')
        ->select('id')
        ->where('num_orden', $num_orden)
        ->get()
        ->getRowArray();

    if (!$orden) {
        return [
            'incidencias' => [],
            'total_incidencias' => 0,
        ];
    }

    // 2. Get ALL incidencias from master table
    $allIncidencias = $this->db->table('reporte_incidencia')
        ->select('id, incidencia')
        ->get()
        ->getResultArray();

    // 3. Get actual counts for this orden
    $counts = $this->db->table('reportes_prod rp')
        ->select('ri.id as incidencia_id, COUNT(rpt.id) as total')
        ->join('reportes_prod_tiempo rpt', 'rpt.reporteId = rp.id')
        ->join('reporte_incidencia ri', 'ri.id = rpt.incidId')
        ->where('rp.ordenId', $orden['id'])
        ->groupBy('ri.id')
        ->get()
        ->getResultArray();

    // Index counts by incidencia_id
    $countMap = [];
    foreach ($counts as $row) {
        $countMap[$row['incidencia_id']] = (int) $row['total'];
    }

    // 4. Build final array with all incidencias (default 0)
    $incidencias = [];
    $totalIncidencias = 0;
    foreach ($allIncidencias as $row) {
        $value = $countMap[$row['id']] ?? 0;
        $incidencias[$row['incidencia']] = $value;
        $totalIncidencias += $value;
    }

    return [
        'incidencias' => $incidencias,
        'total_incidencias' => $totalIncidencias,
    ];
}




/**
 * Get tiempos e incidencias grouped by orden.
 *
 * @param string $date  Format "YYYY-MM-DD" used to filter reportes_prod.created_at (for last_incidencias)
 * @return array  ['ordenes' => [ ...ordered order objects... ]]
 */
public function getTiemposIncidencias11(string $date): array
{
    $db = $this->db;

    $colors = [
        1 => "rgba(153, 102, 255, 0.8)",
        2 => "rgba(178, 34, 34, 0.8)",
        3 => "rgba(30, 144, 255, 0.8)",
        4 => "rgba(0, 128, 128, 0.8)",
        5 => "rgba(128, 0, 128, 0.8)",
        6 => "rgba(139, 69, 19, 0.8)",
    ];

    $secToHuman = function (int $sec): string {
        $h = (int) floor($sec / 3600);
        $m = (int) floor(($sec % 3600) / 60);

        if ($h === 0) {
            return sprintf("%02d min", $m);
        }
        return sprintf("%02d hr %02d min", $h, $m);
    };

    // 1) incidents on requested date (timeline entries)
    $sqlDateInc = "
        SELECT
            of.num_orden,
            rp.id AS report_id,
            rp.created_at,
            p.name AS planta,
            t.name AS turno_name,
            rpt.hora_inicio,
            rpt.hora_fin,
            rpt.tiempo_paro,
            ri.id AS incidencia_id,
            ri.incidencia,
            COALESCE(
                CASE
                    WHEN rpt.hora_inicio IS NOT NULL AND rpt.hora_fin IS NOT NULL
                    THEN TIME_TO_SEC(TIMEDIFF(rpt.hora_fin, rpt.hora_inicio))
                    ELSE 0
                END, 0
            ) AS dur_sec
        FROM reportes_prod rp
        INNER JOIN ordenes_fabricacion of ON of.id = rp.ordenId
        LEFT JOIN _turnos t ON t.id = rp.turnoId
        LEFT JOIN _plantas p ON p.id = t.plantaId
        LEFT JOIN reportes_prod_tiempo rpt ON rpt.reporteId = rp.id
        LEFT JOIN reporte_incidencia ri ON ri.id = rpt.incidId
        WHERE DATE(rp.created_at) = ?
        ORDER BY of.num_orden, rp.created_at, rpt.hora_inicio
    ";
    $rowsDateInc = $db->query($sqlDateInc, [$date])->getResultArray();

    // 2) totals across ALL records per order (used for tiempo_total and percents)
    $sqlTotals = "
        SELECT
            of.num_orden,
            COALESCE(SUM(
                CASE
                    WHEN rpt.hora_inicio IS NOT NULL AND rpt.hora_fin IS NOT NULL
                    THEN TIME_TO_SEC(TIMEDIFF(rpt.hora_fin, rpt.hora_inicio))
                    ELSE 0
                END
            ), 0) AS tiempo_muerto_sec,
            COALESCE(SUM(
                CASE
                    WHEN rp.hora_inicio_registro IS NOT NULL AND rp.hora_fin_registro IS NOT NULL
                    THEN TIME_TO_SEC(TIMEDIFF(rp.hora_fin_registro, rp.hora_inicio_registro))
                    ELSE 0
                END
            ), 0) AS tiempo_total_shift_sec
        FROM reportes_prod_tiempo rpt
        INNER JOIN reportes_prod rp ON rp.id = rpt.reporteId
        INNER JOIN ordenes_fabricacion of ON of.id = rp.ordenId
        GROUP BY of.num_orden
    ";
    $rowsTotals = $db->query($sqlTotals)->getResultArray();
    $totalsMap = [];
    foreach ($rowsTotals as $r) {
        $totalsMap[$r['num_orden']] = [
            'tiempo_muerto_sec' => (int)$r['tiempo_muerto_sec'],
            'tiempo_total_shift_sec' => (int)$r['tiempo_total_shift_sec'],
        ];
    }

    // 3) full historial detalles (all dates) per order
    $sqlHist = "
        SELECT
            of.num_orden,
            rp.created_at,
            p.name AS planta,
            t.name AS turno_name,
            ri.incidencia,
            rpt.hora_inicio,
            rpt.hora_fin,
            rpt.tiempo_paro,
            COALESCE(
                CASE
                    WHEN rpt.hora_inicio IS NOT NULL AND rpt.hora_fin IS NOT NULL
                    THEN TIME_TO_SEC(TIMEDIFF(rpt.hora_fin, rpt.hora_inicio))
                    ELSE 0
                END, 0
            ) AS dur_sec
        FROM reportes_prod_tiempo rpt
        INNER JOIN reportes_prod rp ON rp.id = rpt.reporteId
        INNER JOIN ordenes_fabricacion of ON of.id = rp.ordenId
        LEFT JOIN _turnos t ON t.id = rp.turnoId
        LEFT JOIN _plantas p ON p.id = t.plantaId
        LEFT JOIN reporte_incidencia ri ON ri.id = rpt.incidId
        WHERE of.num_orden IS NOT NULL
        ORDER BY of.num_orden, rp.created_at DESC, rpt.hora_inicio DESC
    ";
    $rowsHist = $db->query($sqlHist)->getResultArray();

    // 4) resumen historial grouped by order + incidencia type
    $sqlHistResumen = "
        SELECT
            of.num_orden,
            ri.incidencia,
            COALESCE(SUM(
                CASE
                    WHEN rpt.hora_inicio IS NOT NULL AND rpt.hora_fin IS NOT NULL
                    THEN TIME_TO_SEC(TIMEDIFF(rpt.hora_fin, rpt.hora_inicio))
                    ELSE 0
                END
            ), 0) AS total_sec
        FROM reportes_prod_tiempo rpt
        INNER JOIN reportes_prod rp ON rp.id = rpt.reporteId
        INNER JOIN ordenes_fabricacion of ON of.id = rp.ordenId
        LEFT JOIN reporte_incidencia ri ON ri.id = rpt.incidId
        GROUP BY of.num_orden, ri.incidencia
        ORDER BY of.num_orden
    ";
    $rowsHistResumen = $db->query($sqlHistResumen)->getResultArray();

    // -------------------------
    // Build master list of orders (based on all queries) so we initialize orders properly
    // -------------------------
    $ordersKeys = [];

    foreach ($rowsTotals as $r) {
        $ordersKeys[$r['num_orden']] = true;
    }
    foreach ($rowsDateInc as $r) {
        $ordersKeys[$r['num_orden']] = true;
    }
    foreach ($rowsHist as $r) {
        $ordersKeys[$r['num_orden']] = true;
    }
    foreach ($rowsHistResumen as $r) {
        $ordersKeys[$r['num_orden']] = true;
    }

    // Initialize ordersMap keyed by num_orden
    $ordersMap = [];
    foreach (array_keys($ordersKeys) as $no) {
        $ordersMap[$no] = [
            'orden_num' => $no,
            'tiempo_total' => '0 hr 0 min',
            'last_incidencias' => [],
            'tiempo_muerto' => '0%',
            'tiempo_efectivo' => '100%',
            'incidencias_detalles_historial' => [],
            'incidencias_resumen_historial' => [],
        ];
    }

    // -------------------------
    // Fill last_incidencias (ONLY those on $date) grouped by num_orden
    // -------------------------
    foreach ($rowsDateInc as $r) {
        $no = $r['num_orden'];

        // defensive init
        if (!isset($ordersMap[$no])) {
            $ordersMap[$no] = [
                'orden_num' => $no,
                'tiempo_total' => '0 hr 0 min',
                'last_incidencias' => [],
                'tiempo_muerto' => '0%',
                'tiempo_efectivo' => '100%',
                'incidencias_detalles_historial' => [],
                'incidencias_resumen_historial' => [],
            ];
        }

        $datePart = $r['created_at'] ? date('Y-m-d', strtotime($r['created_at'])) : null;
        $startFull = ($datePart && $r['hora_inicio']) ? $datePart . ' ' . $r['hora_inicio'] : null;
        $endFull   = ($datePart && $r['hora_fin'])   ? $datePart . ' ' . $r['hora_fin'] : null;

        $entry = [
            'start' => $startFull,
            'end' => $endFull,
            'color' => isset($colors[(int)$r['incidencia_id']]) ? $colors[(int)$r['incidencia_id']] : 'rgba(0,0,0,0.5)',
            'incidencia' => $r['incidencia'],
            'planta_turno' => trim(($r['planta'] ?? '') . ' ' . ($r['turno_name'] ?? '')),
            'dur_sec' => (int)$r['dur_sec'],
        ];

        $ordersMap[$no]['last_incidencias'][] = $entry;
    }

    // -------------------------
    // Populate tiempo_total and percents using totalsMap (ALL dates)
    // -------------------------
    foreach ($ordersMap as $no => &$entry) {
        if (isset($totalsMap[$no])) {
            $muerto = (int)$totalsMap[$no]['tiempo_muerto_sec'];
            $shift  = (int)$totalsMap[$no]['tiempo_total_shift_sec'];

            $entry['tiempo_total'] = $secToHuman($muerto);

            $porcMuerto = 0.0;
            if ($shift > 0) {
                $porcMuerto = round((($muerto / $shift) * 100), 1);
            }
            $entry['tiempo_muerto'] = $porcMuerto . '%';
            $entry['tiempo_efectivo'] = (string)(round(100 - $porcMuerto, 1)) . '%';
        } else {
            // defaults already set
            $entry['tiempo_total'] = '00 hr 00 min';
            $entry['tiempo_muerto'] = '0%';
            $entry['tiempo_efectivo'] = '100%';
        }
    }
    unset($entry);

    // -------------------------
    // Fill historial detalles (all dates) with full datetimes
    // -------------------------
    foreach ($rowsHist as $r) {
        $no = $r['num_orden'];
        if (!isset($ordersMap[$no])) {
            // defensive init
            $ordersMap[$no] = [
                'orden_num' => $no,
                'tiempo_total' => '0 hr 0 min',
                'last_incidencias' => [],
                'tiempo_muerto' => '0%',
                'tiempo_efectivo' => '100%',
                'incidencias_detalles_historial' => [],
                'incidencias_resumen_historial' => [],
            ];
        }

        $datePart = $r['created_at'] ? date('Y-m-d', strtotime($r['created_at'])) : null;
        $startFull = ($datePart && $r['hora_inicio']) ? $datePart . ' ' . $r['hora_inicio'] : null;
        $endFull   = ($datePart && $r['hora_fin']) ? $datePart . ' ' . $r['hora_fin'] : null;

        $ordersMap[$no]['incidencias_detalles_historial'][] = [
            'fecha' => $r['created_at'] ? date('d-m-Y H:i:s', strtotime($r['created_at'])) : null,
            'turno' => trim(($r['planta'] ?? '') . ' ' . ($r['turno_name'] ?? '')),
            'tipo_incid' => $r['incidencia'],
            'hora_inicio' => $startFull,
            'hora_fin' => $endFull,
            'tiempo_paro' => $r['tiempo_paro'],
            'dur_sec' => (int)$r['dur_sec'],
        ];
    }

    // -------------------------
    // Build resumen_historial per order
    // -------------------------
    $resumenTemp = [];
    foreach ($rowsHistResumen as $r) {
        $no = $r['num_orden'];
        $inc = $r['incidencia'] ?? 'OTROS';
        $sec = (int)$r['total_sec'];
        if (!isset($resumenTemp[$no])) $resumenTemp[$no] = [];
        if (!isset($resumenTemp[$no][$inc])) $resumenTemp[$no][$inc] = 0;
        $resumenTemp[$no][$inc] += $sec;
    }

    foreach ($resumenTemp as $no => $incMap) {
        if (!isset($ordersMap[$no])) {
            $ordersMap[$no] = [
                'orden_num' => $no,
                'tiempo_total' => '0 hr 0 min',
                'last_incidencias' => [],
                'tiempo_muerto' => '0%',
                'tiempo_efectivo' => '100%',
                'incidencias_detalles_historial' => [],
                'incidencias_resumen_historial' => [],
            ];
        }

        foreach ($incMap as $incName => $totalSec) {
            $ordersMap[$no]['incidencias_resumen_historial'][] = [
                'tipo_incid' => '- ' . $incName,
                'tiempo' => $secToHuman((int)$totalSec),
                'total_seconds' => (int)$totalSec,
            ];
        }
    }

    // sort last_incidencias inside each order by start datetime (optional)
    foreach ($ordersMap as $no => &$entry) {
        if (!empty($entry['last_incidencias'])) {
            usort($entry['last_incidencias'], function ($a, $b) {
                return strcmp($a['start'] ?? '', $b['start'] ?? '');
            });
        }
    }
    unset($entry);

    // Final result: only 'ordenes' (indexed array)
    $result = [
        'ordenes' => array_values($ordersMap)
    ];

    return $result;
}


public function getMermaByDate(string $date = null): array
{
    $builder = $this->db->table('reporte_inspeccion ri');

    $builder->select([
        'of.num_orden',
        'of.desc_articulo',
        'ri.id AS item_id',
        'ri.name AS item_name',
        'rp.piezas_producidas',
        'rp.muestras',
        'COALESCE(SUM(rpi.merma), 0) AS total_merma'
    ]);

    $builder->join('reportes_prod_insp rpi', 'rpi.itemId = ri.id', 'left');
    $builder->join('reportes_prod rp', 'rp.id = rpi.reporteid', 'left');
    $builder->join('ordenes_fabricacion of', 'of.id = rp.ordenId', 'left');

    if ($date !== null) {
        $builder->where('DATE(rp.created_at)', $date);
    }

    $builder->groupBy([
        'of.num_orden',
        'ri.id',
        'ri.name'
    ]);

    $rows = $builder->get()->getResultArray();

    return $this->groupMermaData($rows);
}


private function groupMermaData(array $rows): array
{
    $grouped = [];

    foreach ($rows as $row) {
        $orden = $row['num_orden'];
        $producto = $row['desc_articulo'] ?? $orden;

        // Initialize order if not exists
        if (!isset($grouped[$orden])) {
            $grouped[$orden] = [
                'num_orden' => $orden,
                'producto' => $producto,
								'total_sum' => 0,
                'empaque' => [
                    'total' => 0,
                    'items' => []
                ],
                'presentacion' => [
                    'total' => 0,
                    'items' => []
                ],
                'contenido' => [
                    'total' => 0,
                    'items' => []
                ],
            ];
        }

        // Detect category
        $category = match (true) {
            $row['item_id'] >= 1 && $row['item_id'] <= 6  => 'empaque',
            $row['item_id'] >= 7 && $row['item_id'] <= 12 => 'presentacion',
            $row['item_id'] >= 13 && $row['item_id'] <= 17 => 'contenido',
            default => null
        };

        if ($category === null) {
            continue;
        }

        $merma = (int) $row['total_merma'];

				// $denominator = (int)$row['piezas_producidas'] + (int)$row['muestras'];
				$denominator = (int)$row['piezas_producidas'];

				if ($denominator != 0) {
						$porcentaje = $merma / $denominator * 100;
				} else {
						// Handle division by zero (default to 0 or another value as needed)
						$porcentaje = 0;
				}

				$label = $row['item_name'] . ' (' . number_format($porcentaje, 3) . '%)';
				$grouped[$orden][$category]['porcentaje'] = $porcentaje . '%';


        // Always register item (even if 0)
        $grouped[$orden][$category]['items'][$label] = $merma;

        // Sum totals
        $grouped[$orden][$category]['total'] += $merma;


				$sumCategories = ['empaque', 'presentacion'];

				if (in_array($category, $sumCategories, true)) {
						$grouped[$orden]['total_sum'] += $merma;
				}
    }

    // IMPORTANT: return indexed array, not associative
    return array_values($grouped);
}


public function getTiemposIncidencias(string $date): array
{
    $db = $this->db;

    $colors = [
        1 => "rgba(153, 102, 255, 0.8)",
        2 => "rgba(178, 34, 34, 0.8)",
        3 => "rgba(30, 144, 255, 0.8)",
        4 => "rgba(0, 128, 128, 0.8)",
        5 => "rgba(128, 0, 128, 0.8)",
        6 => "rgba(139, 69, 19, 0.8)",
    ];

    // --- helper: format seconds to "HH hr MM min" or "MM min"
    $secToHuman = function (int $sec): string {
        $h = (int) floor($sec / 3600);
        $m = (int) floor(($sec % 3600) / 60);
        if ($h === 0) {
            return sprintf('%02d min', $m);
        }
        return sprintf('%02d hr %02d min', $h, $m);
    };

    // 1) incidents on requested date (timeline entries)
    $sqlDateInc = "
        SELECT
            of.num_orden,
            rp.id AS report_id,
            rp.created_at,
            p.name AS planta,
            t.name AS turno_name,
            rpt.hora_inicio,
            rpt.hora_fin,
            rpt.tiempo_paro,
            ri.id AS incidencia_id,
            ri.incidencia,
            COALESCE(
                CASE
                    WHEN rpt.hora_inicio IS NOT NULL AND rpt.hora_fin IS NOT NULL
                    THEN TIME_TO_SEC(TIMEDIFF(rpt.hora_fin, rpt.hora_inicio))
                    ELSE 0
                END, 0
            ) AS dur_sec
        FROM reportes_prod rp
        INNER JOIN ordenes_fabricacion of ON of.id = rp.ordenId
        LEFT JOIN _turnos t ON t.id = rp.turnoId
        LEFT JOIN _plantas p ON p.id = t.plantaId
        LEFT JOIN reportes_prod_tiempo rpt ON rpt.reporteId = rp.id
        LEFT JOIN reporte_incidencia ri ON ri.id = rpt.incidId
        WHERE DATE(rp.created_at) = ?
        ORDER BY of.num_orden, rp.created_at, rpt.hora_inicio
    ";
    $rowsDateInc = $db->query($sqlDateInc, [$date])->getResultArray();

    // 2) totals across ALL records per order (used for tiempo_total and percents)
    $sqlTotals = "
        SELECT
            of.num_orden,
            COALESCE(SUM(
                CASE
                    WHEN rpt.hora_inicio IS NOT NULL AND rpt.hora_fin IS NOT NULL
                    THEN TIME_TO_SEC(TIMEDIFF(rpt.hora_fin, rpt.hora_inicio))
                    ELSE 0
                END
            ), 0) AS tiempo_muerto_sec,
            COALESCE(SUM(
                CASE
                    WHEN rp.hora_inicio_registro IS NOT NULL AND rp.hora_fin_registro IS NOT NULL
                    THEN TIME_TO_SEC(TIMEDIFF(rp.hora_fin_registro, rp.hora_inicio_registro))
                    ELSE 0
                END
            ), 0) AS tiempo_total_shift_sec
        FROM reportes_prod_tiempo rpt
        INNER JOIN reportes_prod rp ON rp.id = rpt.reporteId
        INNER JOIN ordenes_fabricacion of ON of.id = rp.ordenId
        GROUP BY of.num_orden
    ";
    $rowsTotals = $db->query($sqlTotals)->getResultArray();

    // 3) full historial detalles (all dates) per order
    $sqlHist = "
        SELECT
            of.num_orden,
            rp.created_at,
            p.name AS planta,
            t.name AS turno_name,
            ri.incidencia,
            rpt.hora_inicio,
            rpt.hora_fin,
            rpt.tiempo_paro,
            COALESCE(
                CASE
                    WHEN rpt.hora_inicio IS NOT NULL AND rpt.hora_fin IS NOT NULL
                    THEN TIME_TO_SEC(TIMEDIFF(rpt.hora_fin, rpt.hora_inicio))
                    ELSE 0
                END, 0
            ) AS dur_sec
        FROM reportes_prod_tiempo rpt
        INNER JOIN reportes_prod rp ON rp.id = rpt.reporteId
        INNER JOIN ordenes_fabricacion of ON of.id = rp.ordenId
        LEFT JOIN _turnos t ON t.id = rp.turnoId
        LEFT JOIN _plantas p ON p.id = t.plantaId
        LEFT JOIN reporte_incidencia ri ON ri.id = rpt.incidId
        ORDER BY of.num_orden, rp.created_at DESC, rpt.hora_inicio DESC
    ";
    $rowsHist = $db->query($sqlHist)->getResultArray();

    // 4) resumen historial grouped by order + incidencia type
    $sqlHistResumen = "
        SELECT
            of.num_orden,
            ri.incidencia,
            COALESCE(SUM(
                CASE
                    WHEN rpt.hora_inicio IS NOT NULL AND rpt.hora_fin IS NOT NULL
                    THEN TIME_TO_SEC(TIMEDIFF(rpt.hora_fin, rpt.hora_inicio))
                    ELSE 0
                END
            ), 0) AS total_sec
        FROM reportes_prod_tiempo rpt
        INNER JOIN reportes_prod rp ON rp.id = rpt.reporteId
        INNER JOIN ordenes_fabricacion of ON of.id = rp.ordenId
        LEFT JOIN reporte_incidencia ri ON ri.id = rpt.incidId
        GROUP BY of.num_orden, ri.incidencia
        ORDER BY of.num_orden
    ";
    $rowsHistResumen = $db->query($sqlHistResumen)->getResultArray();

    // -------------------------
    // Only keep orders that exist in rowsDateInc (filtered by $date)
    // -------------------------
    $validOrders = [];
    foreach ($rowsDateInc as $r) {
        $validOrders[$r['num_orden']] = true;
    }

    // Initialize ordersMap only for valid orders
    $ordersMap = [];
    foreach (array_keys($validOrders) as $no) {
        $ordersMap[$no] = [
            'orden_num' => $no,
            'tiempo_total' => '00 hr 00 min',
            'last_incidencias' => [],
            'tiempo_muerto' => '0%',
            'tiempo_efectivo' => '100%',
            'incidencias_detalles_historial' => [],
            'incidencias_resumen_historial' => [],
        ];
    }

    // -------------------------
    // Fill last_incidencias (ONLY those on $date)
    // -------------------------
    foreach ($rowsDateInc as $r) {
        $no = $r['num_orden'];
        if (!isset($validOrders[$no])) continue;

        $datePart = $r['created_at'] ? date('Y-m-d', strtotime($r['created_at'])) : null;
        $startFull = ($datePart && $r['hora_inicio']) ? $datePart . ' ' . $r['hora_inicio'] : null;
        $endFull   = ($datePart && $r['hora_fin'])   ? $datePart . ' ' . $r['hora_fin'] : null;

        $ordersMap[$no]['last_incidencias'][] = [
            'start' => $startFull,
            'end' => $endFull,
            'color' => $colors[(int)($r['incidencia_id'] ?? 0)] ?? 'rgba(0,0,0,0.5)',
            'incidencia' => $r['incidencia'],
            'planta_turno' => trim(($r['planta'] ?? '') . ' ' . ($r['turno_name'] ?? '')),
            'dur_sec' => (int)$r['dur_sec'],
        ];
    }

    // -------------------------
    // Populate tiempo_total and percents using totalsMap (ALL dates)
    // -------------------------
    $totalsMap = [];
    foreach ($rowsTotals as $r) {
        $totalsMap[$r['num_orden']] = [
            'tiempo_muerto_sec' => (int)$r['tiempo_muerto_sec'],
            'tiempo_total_shift_sec' => (int)$r['tiempo_total_shift_sec'],
        ];
    }

    foreach ($ordersMap as $no => &$entry) {
        if (isset($totalsMap[$no])) {
            $muerto = $totalsMap[$no]['tiempo_muerto_sec'];
            $shift  = $totalsMap[$no]['tiempo_total_shift_sec'];

            $entry['tiempo_total'] = $secToHuman($muerto);

            $porcMuerto = ($shift > 0) ? round(($muerto / $shift) * 100, 1) : 0;
            $entry['tiempo_muerto'] = $porcMuerto . '%';
            $entry['tiempo_efectivo'] = (string)(round(100 - $porcMuerto, 1)) . '%';
        }
    }
    unset($entry);

    // -------------------------
    // Fill historial detalles (all dates)
    // -------------------------
    foreach ($rowsHist as $r) {
        $no = $r['num_orden'];
        if (!isset($validOrders[$no])) continue;

        $datePart = $r['created_at'] ? date('Y-m-d', strtotime($r['created_at'])) : null;
        $startFull = ($datePart && $r['hora_inicio']) ? $datePart . ' ' . $r['hora_inicio'] : null;
        $endFull   = ($datePart && $r['hora_fin']) ? $datePart . ' ' . $r['hora_fin'] : null;

        $ordersMap[$no]['incidencias_detalles_historial'][] = [
            'fecha' => $r['created_at'] ? date('d-m-Y H:i:s', strtotime($r['created_at'])) : null,
            'turno' => trim(($r['planta'] ?? '') . ' ' . ($r['turno_name'] ?? '')),
            'tipo_incid' => $r['incidencia'],
            'hora_inicio' => $startFull,
            'hora_fin' => $endFull,
            'tiempo_paro' => $r['tiempo_paro'],
            'dur_sec' => (int)$r['dur_sec'],
        ];
    }

    // -------------------------
    // Build resumen_historial per order
    // -------------------------
    $resumenTemp = [];
    foreach ($rowsHistResumen as $r) {
        $no = $r['num_orden'];
        if (!isset($validOrders[$no])) continue;

        $inc = $r['incidencia'] ?? 'OTROS';
        $sec = (int)$r['total_sec'];
        if (!isset($resumenTemp[$no])) $resumenTemp[$no] = [];
        if (!isset($resumenTemp[$no][$inc])) $resumenTemp[$no][$inc] = 0;
        $resumenTemp[$no][$inc] += $sec;
    }

    foreach ($resumenTemp as $no => $incMap) {
        foreach ($incMap as $incName => $totalSec) {
            $ordersMap[$no]['incidencias_resumen_historial'][] = [
                'tipo_incid' => '- ' . $incName,
                'tiempo' => $secToHuman($totalSec),
                'total_seconds' => $totalSec,
            ];
        }
    }

    // sort last_incidencias by start datetime
    foreach ($ordersMap as &$entry) {
        if (!empty($entry['last_incidencias'])) {
            usort($entry['last_incidencias'], fn($a, $b) => strcmp($a['start'] ?? '', $b['start'] ?? ''));
        }
    }
    unset($entry);

    return [
        'ordenes' => array_values($ordersMap)
    ];
}



// refactor the "last_incidencias" array, it must be group by num_orden, currently get all the records existing in table reportes_prod_tiempo (id, reporteId fk reportes_prod.id) consider reportes_prod.ordenId is fk for table ordenes_fabricacion.id,
//  the new return must be: 

// [

// 	'last_incidencias' => [],

// 	'ordenes' => [ here will be all data for each orden

// 	[
// 		'orden_num' => $no,
// 		'tiempo_total' => '0 hr 0 min',
		
// 		'tiempo_muerto' => '0%',
// 		'tiempo_efectivo' => '100%',
// 		'incidencias_detalles_historial' => [],
// 		'incidencias_resumen_historial' => [],
// 	]

// 		[
// 		'orden_num' => $no,
// 		'tiempo_total' => '0 hr 0 min',
		
// 		'tiempo_muerto' => '0%',
// 		'tiempo_efectivo' => '100%',
// 		'incidencias_detalles_historial' => [],
// 		'incidencias_resumen_historial' => [],
// 	]

// 	etc
// 	]

// ]




}