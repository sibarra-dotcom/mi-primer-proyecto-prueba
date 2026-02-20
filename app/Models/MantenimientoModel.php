<?php

namespace App\Models;

use CodeIgniter\Model;

class MantenimientoModel extends Model
{
    protected $table            = 'mantenimiento';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['userId', 'maqId', 'responsableId', 'produccionId', 'limpiezaId', 'calidadId', 'solicitante', 'prioridad', 'asunto', 'descripcion', 'estado_maq', 'estado_ticket', 'diagnostico', 'reparacion_detalle', 'fecha_reparacion', 'fecha_arranque', 'fecha_cierre', 'compra_pieza', 'cambio_pieza', 'nota_inventario', 'firma_encargado', 'firma_responsable', 'firma_calidad', 'firma_produccion', 'firma_limpieza', 'requiere_limpieza'];

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

    public function search(array $searchCriteria = [])
    {
				$builder = $this->select('
					COALESCE(mant_adjunto.archivo, "-") AS archivo, 
					COALESCE(users.id, "-") AS user_id, 
        	COALESCE(users.name, "-") AS name, 
        	COALESCE(users.last_name, "-") AS last_name, 
						mantenimiento.id, 
						mantenimiento.solicitante,
						COALESCE(mantenimiento.responsableId, "-") AS responsableId,  
						mantenimiento.prioridad,
						mantenimiento.fecha_reparacion,
						mantenimiento.fecha_arranque,
						mantenimiento.fecha_cierre, 
						mantenimiento.asunto, 
						mantenimiento.descripcion, 
						mantenimiento.estado_ticket, 
						mantenimiento.estado_maq, 
						mantenimiento.diagnostico,
						mantenimiento.reparacion_detalle,
						mantenimiento.cambio_pieza,
						mantenimiento.compra_pieza,
						mantenimiento.nota_inventario,
						mantenimiento.created_at, 
						mantenimiento.updated_at,
						maquinaria.nombre, 
						maquinaria.marca, 
						maquinaria.modelo, 
						maquinaria.serie, 
						maquinaria.linea, 
						maquinaria.planta, 
						maquinaria.year, 
						-- Calculate deadline based on prioridad
						CASE 
								WHEN mantenimiento.estado_ticket = 4 THEN 
										mantenimiento.updated_at -- Keep the same deadline when closed
								ELSE 
										CASE 
												WHEN mantenimiento.prioridad = "ALTA" THEN DATE_ADD(DATE(mantenimiento.created_at), INTERVAL 3 DAY)
												WHEN mantenimiento.prioridad = "MEDIA" THEN DATE_ADD(DATE(mantenimiento.created_at), INTERVAL 5 DAY)
												WHEN mantenimiento.prioridad = "BAJA" THEN DATE_ADD(DATE(mantenimiento.created_at), INTERVAL 10 DAY)
										END
						END AS deadline,
		
						-- Stop counting days_remaining when estado_ticket = 4
						CASE 
								WHEN mantenimiento.estado_ticket = 4 THEN 
										(SELECT DATEDIFF(
												CASE 
														WHEN mantenimiento.prioridad = "ALTA" THEN DATE_ADD(DATE(mantenimiento.created_at), INTERVAL 3 DAY)
														WHEN mantenimiento.prioridad = "MEDIA" THEN DATE_ADD(DATE(mantenimiento.created_at), INTERVAL 5 DAY)
														WHEN mantenimiento.prioridad = "BAJA" THEN DATE_ADD(DATE(mantenimiento.created_at), INTERVAL 10 DAY)
												END,
												DATE(mantenimiento.updated_at)
										)) -- Keep last calculated value
								ELSE 
										DATEDIFF(
												CASE 
														WHEN mantenimiento.prioridad = "ALTA" THEN DATE_ADD(DATE(mantenimiento.created_at), INTERVAL 3 DAY)
														WHEN mantenimiento.prioridad = "MEDIA" THEN DATE_ADD(DATE(mantenimiento.created_at), INTERVAL 5 DAY)
														WHEN mantenimiento.prioridad = "BAJA" THEN DATE_ADD(DATE(mantenimiento.created_at), INTERVAL 10 DAY)
												END,
												CURDATE()
										)
						END AS days_remaining
				')
            ->join('maquinaria', 'maquinaria.id = mantenimiento.maqId')
            ->join('users', 'users.id = mantenimiento.responsableId', 'left') 
						->join('mant_adjunto', 'mant_adjunto.id = mantenimiento.id', 'left') 
            ->groupBy('mantenimiento.id')
            ->orderBy('mantenimiento.id', 'DESC');


        // Apply search filters dynamically
        if (!empty($searchCriteria)) {
            if (!empty($searchCriteria['solicitante'])) {
                $builder->like('mantenimiento.solicitante', $searchCriteria['solicitante']);
            }

            if (!empty($searchCriteria['estado_ticket'])) {
								$builder->like('mantenimiento.estado_ticket', $searchCriteria['estado_ticket']);
						}

            if (!empty($searchCriteria['ticketId'])) {
                $builder->where('mantenimiento.id', $searchCriteria['ticketId']);
            }

            if (!empty($searchCriteria['planta'])) {
                $builder->like('maquinaria.planta', $searchCriteria['planta']);
            }

            if (!empty($searchCriteria['linea'])) {
                $builder->like('maquinaria.linea', $searchCriteria['linea']);
            }

            if (!empty($searchCriteria['nombre'])) {
                $builder->like('maquinaria.nombre', $searchCriteria['nombre']);
            }

						if (!empty($searchCriteria['prioridad'])) {
								$builder->where('mantenimiento.prioridad', $searchCriteria['prioridad']);
						}

						if (!empty($searchCriteria['estado_maq'])) {
								$builder->where('mantenimiento.estado_maq', $searchCriteria['estado_maq']);
						}

						if (!empty($searchCriteria['fecha'])) {
								$builder->where('DATE(mantenimiento.created_at)', $searchCriteria['fecha']);
						}


            // if (!empty($searchCriteria['status_desarrollo'])) {
            //     $builder->having('status_desarrollo', $searchCriteria['status_desarrollo']);
            // }

        }

        return $builder->findAll();
    }



    // in CodeIgniter 4 im using this function to get articles with inner join
    // public function getListaArticulos()
    // {
    //     $builder = $this->select('proveedor.razon_social as proveedor, 
    //         cotizacion_detalle.nombreDelArticulo as articulo, 
    //         cotizacion_detalle.costoPorUnidad as costo, 
    //         cotizacion_detalle.divisa, 
    //         cotizacion.fecha, 
    //         cotizacion.vigencia, 
    //         COALESCE(cotizacion.incoterm, "-") as incoterm,
    //         COUNT(CASE WHEN articulo_comment.comentario != "empty" THEN 1 END) AS num_comm')
    //         ->join('proveedor', 'proveedor.id = cotizacion.proveedorId')
    //         ->join('cotizacion_detalle', 'cotizacion_detalle.cotizacionId = cotizacion.id')
    //         ->join('articulo_comment', 'articulo_comment.articuloId = cotizacion_detalle.id')
    //         ->groupBy('cotizacion_detalle.id');
    //     return $builder->limit(10, 0)->get()->getResultArray();
    // }
    // now i need to join the results ( i mean add in each array data of the article) with this     table "aprobaciones" (art_id, area status, comentario, createdAt); in 
    // this table, the status are "pending" "rejected" "aproved", but this recorda in db appears acoording the supervisors of each area (desarrollo or calidad, or costos) update the status of each single article, how to manage to get in array data of each product somethin like "status_desarrollo" => "pending", "status_calidad" => "approved", "status_costos" => "rejected" 

		public function getAllPendientes()
		{
				$builder = $this->select('
					COALESCE(mant_adjunto.archivo, "-") AS archivo, 
					COALESCE(users.id, "-") AS user_id, 
        	COALESCE(users.name, "-") AS name, 
        	COALESCE(users.last_name, "-") AS last_name, 
						mantenimiento.id, 
						mantenimiento.solicitante,
						COALESCE(mantenimiento.responsableId, "-") AS responsableId,  
						mantenimiento.prioridad,
						mantenimiento.fecha_reparacion,
						mantenimiento.fecha_arranque,
						mantenimiento.fecha_cierre, 
						mantenimiento.asunto, 
						mantenimiento.descripcion, 
						mantenimiento.estado_ticket, 
						mantenimiento.estado_maq, 
						mantenimiento.diagnostico,
						mantenimiento.reparacion_detalle,
						mantenimiento.cambio_pieza,
						mantenimiento.compra_pieza,
						mantenimiento.nota_inventario,
						mantenimiento.created_at, 
						mantenimiento.updated_at,
						maquinaria.nombre, 
						maquinaria.marca, 
						maquinaria.modelo, 
						maquinaria.serie, 
						maquinaria.linea, 
						maquinaria.planta, 
						maquinaria.year, 
						-- Calculate deadline based on prioridad
						CASE 
								WHEN mantenimiento.estado_ticket = 4 THEN 
										mantenimiento.updated_at -- Keep the same deadline when closed
								ELSE 
										CASE 
												WHEN mantenimiento.prioridad = "ALTA" THEN DATE_ADD(DATE(mantenimiento.created_at), INTERVAL 3 DAY)
												WHEN mantenimiento.prioridad = "MEDIA" THEN DATE_ADD(DATE(mantenimiento.created_at), INTERVAL 5 DAY)
												WHEN mantenimiento.prioridad = "BAJA" THEN DATE_ADD(DATE(mantenimiento.created_at), INTERVAL 10 DAY)
										END
						END AS deadline,
		
						-- Stop counting days_remaining when estado_ticket = 4
						CASE 
								WHEN mantenimiento.estado_ticket = 4 THEN 
										(SELECT DATEDIFF(
												CASE 
														WHEN mantenimiento.prioridad = "ALTA" THEN DATE_ADD(DATE(mantenimiento.created_at), INTERVAL 3 DAY)
														WHEN mantenimiento.prioridad = "MEDIA" THEN DATE_ADD(DATE(mantenimiento.created_at), INTERVAL 5 DAY)
														WHEN mantenimiento.prioridad = "BAJA" THEN DATE_ADD(DATE(mantenimiento.created_at), INTERVAL 10 DAY)
												END,
												DATE(mantenimiento.updated_at)
										)) -- Keep last calculated value
								ELSE 
										DATEDIFF(
												CASE 
														WHEN mantenimiento.prioridad = "ALTA" THEN DATE_ADD(DATE(mantenimiento.created_at), INTERVAL 3 DAY)
														WHEN mantenimiento.prioridad = "MEDIA" THEN DATE_ADD(DATE(mantenimiento.created_at), INTERVAL 5 DAY)
														WHEN mantenimiento.prioridad = "BAJA" THEN DATE_ADD(DATE(mantenimiento.created_at), INTERVAL 10 DAY)
												END,
												CURDATE()
										)
						END AS days_remaining
				')
				->join('maquinaria', 'maquinaria.id = mantenimiento.maqId')
				->join('users', 'users.id = mantenimiento.responsableId', 'left') 
				->join('mant_adjunto', 'mant_adjunto.id = mantenimiento.id', 'left') 
				->whereIn('mantenimiento.estado_ticket', [1, 2])
				->orderBy('mantenimiento.id', 'DESC');
		
				return $builder->findAll();
		}

		
    public function getAllPendientes1()
    {
			$builder = $this->select('
			mantenimiento.id, 
			mantenimiento.solicitante,
			COALESCE(mantenimiento.responsableId, "-") AS responsableId,  
			mantenimiento.prioridad,
			mantenimiento.fecha_reparacion,
			mantenimiento.fecha_arranque,
			mantenimiento.fecha_cierre, 
			mantenimiento.asunto, 
			mantenimiento.descripcion, 
			mantenimiento.estado_ticket, 
			mantenimiento.estado_maq, 
			mantenimiento.diagnostico,
			mantenimiento.reparacion_detalle,
			mantenimiento.cambio_pieza,
			mantenimiento.compra_pieza,
			mantenimiento.nota_inventario,
			mantenimiento.created_at, 
			maquinaria.nombre, 
			maquinaria.marca, 
			maquinaria.modelo, 
			maquinaria.serie, 
			maquinaria.linea, 
			maquinaria.planta, 
			maquinaria.year, 
        -- Calculate deadline based on prioridad
        CASE 
            WHEN mantenimiento.prioridad = "ALTA" THEN DATE_ADD(mantenimiento.created_at, INTERVAL 3 DAY)
            WHEN mantenimiento.prioridad = "MEDIA" THEN DATE_ADD(mantenimiento.created_at, INTERVAL 5 DAY)
            WHEN mantenimiento.prioridad = "BAJA" THEN DATE_ADD(mantenimiento.created_at, INTERVAL 10 DAY)
        END AS deadline,
        -- Calculate days remaining or overdue
        DATEDIFF(
            CASE 
                WHEN mantenimiento.prioridad = "ALTA" THEN DATE_ADD(mantenimiento.created_at, INTERVAL 3 DAY)
                WHEN mantenimiento.prioridad = "MEDIA" THEN DATE_ADD(mantenimiento.created_at, INTERVAL 5 DAY)
                WHEN mantenimiento.prioridad = "BAJA" THEN DATE_ADD(mantenimiento.created_at, INTERVAL 10 DAY)
            END,
            CURDATE()
        ) AS days_remaining
			')
			->join('maquinaria', 'maquinaria.id = mantenimiento.maqId')
			->whereIn('mantenimiento.estado_ticket', [1, 4])
			->orderBy('mantenimiento.id', 'DESC');

			return $builder->findAll();
    }

		// baja 10, media 5, alta 3
    public function getTickets($userId)
    {
			$builder = $this->select('
					COALESCE(mant_adjunto.archivo, "-") AS archivo, 
					COALESCE(users.id, "-") AS user_id, 
        	COALESCE(users.name, "-") AS name, 
        	COALESCE(users.last_name, "-") AS last_name, 
					mantenimiento.id, 
					mantenimiento.solicitante,
					COALESCE(mantenimiento.responsableId, "-") AS responsableId,  
					mantenimiento.prioridad,
					mantenimiento.fecha_reparacion,
					mantenimiento.fecha_arranque,
					mantenimiento.fecha_cierre, 
					mantenimiento.asunto, 
					mantenimiento.descripcion, 
					mantenimiento.estado_ticket, 
					mantenimiento.estado_maq, 
					mantenimiento.diagnostico,
					mantenimiento.reparacion_detalle,
					mantenimiento.cambio_pieza,
					mantenimiento.compra_pieza,
					mantenimiento.nota_inventario,
					mantenimiento.created_at, 
					mantenimiento.updated_at,
					maquinaria.nombre, 
					maquinaria.marca, 
					maquinaria.modelo, 
					maquinaria.serie, 
					maquinaria.linea, 
					maquinaria.planta, 
					maquinaria.year, 
					-- Calculate deadline based on prioridad
					CASE 
							WHEN mantenimiento.estado_ticket = 4 THEN 
									mantenimiento.updated_at -- Keep the same deadline when closed
							ELSE 
									CASE 
											WHEN mantenimiento.prioridad = "ALTA" THEN DATE_ADD(DATE(mantenimiento.created_at), INTERVAL 3 DAY)
											WHEN mantenimiento.prioridad = "MEDIA" THEN DATE_ADD(DATE(mantenimiento.created_at), INTERVAL 5 DAY)
											WHEN mantenimiento.prioridad = "BAJA" THEN DATE_ADD(DATE(mantenimiento.created_at), INTERVAL 10 DAY)
									END
					END AS deadline,

					-- Stop counting days_remaining when estado_ticket = 4
					CASE 
							WHEN mantenimiento.estado_ticket = 4 THEN 
									(SELECT DATEDIFF(
											CASE 
													WHEN mantenimiento.prioridad = "ALTA" THEN DATE_ADD(DATE(mantenimiento.created_at), INTERVAL 3 DAY)
													WHEN mantenimiento.prioridad = "MEDIA" THEN DATE_ADD(DATE(mantenimiento.created_at), INTERVAL 5 DAY)
													WHEN mantenimiento.prioridad = "BAJA" THEN DATE_ADD(DATE(mantenimiento.created_at), INTERVAL 10 DAY)
											END,
											DATE(mantenimiento.updated_at)
									)) -- Keep last calculated value
							ELSE 
									DATEDIFF(
											CASE 
													WHEN mantenimiento.prioridad = "ALTA" THEN DATE_ADD(DATE(mantenimiento.created_at), INTERVAL 3 DAY)
													WHEN mantenimiento.prioridad = "MEDIA" THEN DATE_ADD(DATE(mantenimiento.created_at), INTERVAL 5 DAY)
													WHEN mantenimiento.prioridad = "BAJA" THEN DATE_ADD(DATE(mantenimiento.created_at), INTERVAL 10 DAY)
											END,
											CURDATE()
									)
					END AS days_remaining
			')
			->join('maquinaria', 'maquinaria.id = mantenimiento.maqId')
			->join('users', 'users.id = mantenimiento.responsableId', 'left') 
			->join('mant_adjunto', 'mant_adjunto.id = mantenimiento.id', 'left') 
			// ->where('mantenimiento.userId', $userId)
			->where('mantenimiento.responsableId', $userId)
			->orderBy('mantenimiento.id', 'DESC');

			return $builder->findAll();
		}

		public function getTicket($id)
    {
			$builder = $this->select('
					COALESCE(mant_adjunto.archivo, "-") AS archivo, 
					COALESCE(users.id, "-") AS user_id, 
        	COALESCE(users.name, "-") AS name, 
        	COALESCE(users.last_name, "-") AS last_name, 
					mantenimiento.id, 
					mantenimiento.solicitante,
					COALESCE(mantenimiento.responsableId, "-") AS responsableId,  
					mantenimiento.prioridad,
					mantenimiento.fecha_reparacion,
					mantenimiento.fecha_arranque,
					mantenimiento.fecha_cierre, 
					mantenimiento.asunto, 
					mantenimiento.descripcion, 
					mantenimiento.estado_ticket, 
					mantenimiento.estado_maq, 
					mantenimiento.diagnostico,
					mantenimiento.reparacion_detalle,
					mantenimiento.cambio_pieza,
					mantenimiento.compra_pieza,
					mantenimiento.requiere_limpieza,
					mantenimiento.nota_inventario,
					mantenimiento.created_at, 
					mantenimiento.updated_at,
					maquinaria.nombre, 
					maquinaria.marca, 
					maquinaria.modelo, 
					maquinaria.serie, 
					maquinaria.linea, 
					maquinaria.planta, 
					maquinaria.year, 
					-- Calculate deadline based on prioridad
					CASE 
							WHEN mantenimiento.estado_ticket = 4 THEN 
									mantenimiento.updated_at -- Keep the same deadline when closed
							ELSE 
									CASE 
											WHEN mantenimiento.prioridad = "ALTA" THEN DATE_ADD(DATE(mantenimiento.created_at), INTERVAL 3 DAY)
											WHEN mantenimiento.prioridad = "MEDIA" THEN DATE_ADD(DATE(mantenimiento.created_at), INTERVAL 5 DAY)
											WHEN mantenimiento.prioridad = "BAJA" THEN DATE_ADD(DATE(mantenimiento.created_at), INTERVAL 10 DAY)
									END
					END AS deadline,

					-- Stop counting days_remaining when estado_ticket = 4
					CASE 
							WHEN mantenimiento.estado_ticket = 4 THEN 
									(SELECT DATEDIFF(
											CASE 
													WHEN mantenimiento.prioridad = "ALTA" THEN DATE_ADD(DATE(mantenimiento.created_at), INTERVAL 3 DAY)
													WHEN mantenimiento.prioridad = "MEDIA" THEN DATE_ADD(DATE(mantenimiento.created_at), INTERVAL 5 DAY)
													WHEN mantenimiento.prioridad = "BAJA" THEN DATE_ADD(DATE(mantenimiento.created_at), INTERVAL 10 DAY)
											END,
											DATE(mantenimiento.updated_at)
									)) -- Keep last calculated value
							ELSE 
									DATEDIFF(
											CASE 
													WHEN mantenimiento.prioridad = "ALTA" THEN DATE_ADD(DATE(mantenimiento.created_at), INTERVAL 3 DAY)
													WHEN mantenimiento.prioridad = "MEDIA" THEN DATE_ADD(DATE(mantenimiento.created_at), INTERVAL 5 DAY)
													WHEN mantenimiento.prioridad = "BAJA" THEN DATE_ADD(DATE(mantenimiento.created_at), INTERVAL 10 DAY)
											END,
											CURDATE()
									)
					END AS days_remaining
			')
			->join('maquinaria', 'maquinaria.id = mantenimiento.maqId')
			->join('users', 'users.id = mantenimiento.responsableId', 'left') 
			->join('mant_adjunto', 'mant_adjunto.id = mantenimiento.id', 'left') 
			->where('mantenimiento.id', $id);

			return $builder->get()->getRowArray();
		}

    public function getAll($limit = 20)
    {
			$builder = $this->select('
					COALESCE(mant_adjunto.archivo, "-") AS archivo, 
					COALESCE(users.id, "-") AS user_id, 
        	COALESCE(users.name, "-") AS name, 
        	COALESCE(users.last_name, "-") AS last_name, 
					mantenimiento.id, 
					mantenimiento.solicitante,
					COALESCE(mantenimiento.responsableId, "-") AS responsableId,  
					mantenimiento.prioridad,
					mantenimiento.fecha_reparacion,
					mantenimiento.fecha_arranque,
					mantenimiento.fecha_cierre, 
					mantenimiento.asunto, 
					mantenimiento.descripcion, 
					mantenimiento.estado_ticket, 
					mantenimiento.estado_maq, 
					mantenimiento.diagnostico,
					mantenimiento.reparacion_detalle,
					mantenimiento.cambio_pieza,
					mantenimiento.compra_pieza,
					mantenimiento.nota_inventario,
					mantenimiento.created_at, 
					mantenimiento.updated_at,
					maquinaria.nombre, 
					maquinaria.marca, 
					maquinaria.modelo, 
					maquinaria.serie, 
					maquinaria.linea, 
					maquinaria.planta, 
					maquinaria.year, 
					-- Calculate deadline based on prioridad
					CASE 
							WHEN mantenimiento.estado_ticket = 4 THEN 
									mantenimiento.updated_at -- Keep the same deadline when closed
							ELSE 
									CASE 
											WHEN mantenimiento.prioridad = "ALTA" THEN DATE_ADD(DATE(mantenimiento.created_at), INTERVAL 3 DAY)
											WHEN mantenimiento.prioridad = "MEDIA" THEN DATE_ADD(DATE(mantenimiento.created_at), INTERVAL 5 DAY)
											WHEN mantenimiento.prioridad = "BAJA" THEN DATE_ADD(DATE(mantenimiento.created_at), INTERVAL 10 DAY)
									END
					END AS deadline,

					-- Stop counting days_remaining when estado_ticket = 4
					CASE 
							WHEN mantenimiento.estado_ticket = 4 THEN 
									(SELECT DATEDIFF(
											CASE 
													WHEN mantenimiento.prioridad = "ALTA" THEN DATE_ADD(DATE(mantenimiento.created_at), INTERVAL 3 DAY)
													WHEN mantenimiento.prioridad = "MEDIA" THEN DATE_ADD(DATE(mantenimiento.created_at), INTERVAL 5 DAY)
													WHEN mantenimiento.prioridad = "BAJA" THEN DATE_ADD(DATE(mantenimiento.created_at), INTERVAL 10 DAY)
											END,
											DATE(mantenimiento.updated_at)
									)) -- Keep last calculated value
							ELSE 
									DATEDIFF(
											CASE 
													WHEN mantenimiento.prioridad = "ALTA" THEN DATE_ADD(DATE(mantenimiento.created_at), INTERVAL 3 DAY)
													WHEN mantenimiento.prioridad = "MEDIA" THEN DATE_ADD(DATE(mantenimiento.created_at), INTERVAL 5 DAY)
													WHEN mantenimiento.prioridad = "BAJA" THEN DATE_ADD(DATE(mantenimiento.created_at), INTERVAL 10 DAY)
											END,
											CURDATE()
									)
					END AS days_remaining
			')
			->join('maquinaria', 'maquinaria.id = mantenimiento.maqId')
			->join('users', 'users.id = mantenimiento.responsableId', 'left') 
			->join('mant_adjunto', 'mant_adjunto.id = mantenimiento.id', 'left') 
			->orderBy('mantenimiento.id', 'DESC');

      return $builder->limit($limit, 0)->get()->getResultArray();
    }


		
}
