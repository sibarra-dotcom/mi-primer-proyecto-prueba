<?php

namespace App\Models;

use CodeIgniter\Model;

class ReportesProdModel extends Model
{
    protected $table            = 'reportes_prod';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
		protected $allowedFields = [
				'ordenId',
				'turnoId',
				'userId',
				'formatoId',
				'produccionId',
				'firma_produccion',
				'fecha_firma_produccion',
				'cajas_turno',
				'linea',
				'piezas_producidas',
				'muestras',
				'piezas_acumuladas',
				'batch_inicial',
				'batch_final',
				'cantidad_mezcla',
				'lote_mezcla',
				'peso_tm',
				'peso_tv',
				'colectiva',
				'status_fabricacion',
				'observacion_produc',
				'observacion_reporte',
				'obs_reporte',
				'hora_inicio_registro',
				'hora_fin_registro',
				'created_at',
				'updated_at'
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

		public function getObsReporte($reporteId)
		{
				$builder = $this->select('
						reportes_prod.id, 
						reportes_prod.obs_reporte, 
						')
						->where('reportes_prod.id', $reporteId);

				return $builder->get()->getRowArray();
		}

		public function getAll()
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

		public function getById($reporteId)
    {
			$builder = $this->select('
					reportes_prod.*,
					users.name, 
					users.last_name, 
					users.signature,
					CONCAT(_plantas.name, " - ", _turnos.name) AS turno,
			')
			->join('users', 'users.id = reportes_prod.produccionId', 'left') 
			->join('_turnos', '_turnos.id = reportes_prod.turnoId', 'left') 
			->join('_plantas', '_plantas.id = _turnos.plantaId', 'left') 
			->where('reportes_prod.id', $reporteId);

			return $builder->get()->getRowArray();
		}

		public function getAcumulado1($ordenId)
    {
			$builder = $this->selectSum('reportes_prod.piezas_producidas')
			->where('reportes_prod.ordenId', $ordenId);

			$result = $builder->get()->getRow();
 
			return $result->piezas_producidas ?? 0; 
		}

		public function getAcumulado($ordenId)
		{
				$builder = $this->selectSum('reportes_prod.piezas_producidas')
						->selectSum('reportes_prod.muestras')
						->where('reportes_prod.ordenId', $ordenId);

				$result = $builder->get()->getRow();

				return ($result->piezas_producidas ?? 0) + ($result->muestras ?? 0);
		}

		public function getAllByOrdenFab($num_orden)
    {
			$builder = $this->select('
					reportes_prod.*,
					ordenes_fabricacion.userId,
					ordenes_fabricacion.num_orden,
					ordenes_fabricacion.fecha_vencimiento,
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
					ordenes_fabricacion.num_piezas

			')
			->join('ordenes_fabricacion', 'ordenes_fabricacion.id = reportes_prod.ordenId', 'left') 
			->where('ordenes_fabricacion.num_orden', $num_orden)
			->orderBy('reportes_prod.id', 'DESC');

			return $builder->get()->getResultArray();
		}


}