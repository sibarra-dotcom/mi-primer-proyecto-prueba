<?php

namespace App\Models;

use CodeIgniter\Model;

class Cotizacion extends Model
{
    protected $table            = 'cotizacion';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['userId', 'proveedorId', 'contactoId', 'fecha', 'origen', 'vigencia', 'incoterm'];

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
        $builder = $this->select('proveedor.razon_social as proveedor, 
            cotizacion_detalle.nombreDelArticulo as articulo, 
            cotizacion_detalle.costoPorUnidad as costo, 
            cotizacion_detalle.divisa, 
            cotizacion_detalle.impuesto, 
            cotizacion_detalle.medicion, 
            cotizacion_detalle.minimo, 
            cotizacion_detalle.importe, 
            cotizacion_detalle.diasDeEnvio, 
            cotizacion_detalle.cantidadPer, 
            cotizacion_detalle.periodo, 
            cotizacion_detalle.tipoDia, 
            cotizacion_detalle.id as art_id, 
            cotizacion.id as cotiz_id, 
            cotizacion.origen, 
            cotizacion.fecha, 
            cotizacion.vigencia, 
            COALESCE(cotizacion.incoterm, "-") as incoterm,
						COUNT(DISTINCT CASE 
                WHEN articulo_condicion.articuloId IS NOT NULL 
                AND articulo_condicion.condicion IS NOT NULL 
                AND articulo_condicion.condicion != "empty" 
                THEN articulo_condicion.id
            END) AS num_cond,
            COUNT(DISTINCT CASE 
                WHEN articulo_comment.articuloId IS NOT NULL 
                AND articulo_comment.comentario IS NOT NULL 
                AND articulo_comment.comentario != "empty" 
                THEN articulo_comment.id
            END) AS num_comm,
            COALESCE(
                MAX(CASE 
                    WHEN aprobaciones.area = "desarrollo" AND aprobaciones.articuloId IS NOT NULL 
                    THEN aprobaciones.status 
                END), "PENDIENTE"
            ) AS status_desarrollo,
            COALESCE(
                MAX(CASE 
                    WHEN aprobaciones.area = "calidad" AND aprobaciones.articuloId IS NOT NULL 
                    THEN aprobaciones.status 
                END), "PENDIENTE"
            ) AS status_calidad,
            COALESCE(
                MAX(CASE 
                    WHEN aprobaciones.area = "costos" AND aprobaciones.articuloId IS NOT NULL 
                    THEN aprobaciones.status 
                END), "PENDIENTE"
            ) AS status_costos

            ')
            ->join('proveedor', 'proveedor.id = cotizacion.proveedorId')
            ->join('cotizacion_detalle', 'cotizacion_detalle.cotizacionId = cotizacion.id')
            ->join('articulo_comment', 'articulo_comment.articuloId = cotizacion_detalle.id', 'left')
            ->join('articulo_condicion', 'articulo_condicion.articuloId = cotizacion_detalle.id', 'left')
            ->join('aprobaciones', 'aprobaciones.articuloId = cotizacion_detalle.id', 'left')
            ->groupBy('cotizacion_detalle.id')
            ->orderBy('cotizacion_detalle.id', 'DESC');


        // Apply search filters dynamically
        if (!empty($searchCriteria)) {
            if (!empty($searchCriteria['proveedor'])) {
                $builder->like('proveedor.razon_social', $searchCriteria['proveedor']);
            }

            if (!empty($searchCriteria['articulo'])) {
                $builder->like('cotizacion_detalle.nombreDelArticulo', $searchCriteria['articulo']);
            }

            if (!empty($searchCriteria['fecha'])) {
                $builder->like('cotizacion.fecha', $searchCriteria['fecha']);
            }

            if (!empty($searchCriteria['vigencia'])) {
                $builder->like('cotizacion.vigencia', $searchCriteria['vigencia']);
            }

            // if (!empty($searchCriteria['origen'])) {
            //     $builder->where('cotizacion.origen', $searchCriteria['origen']);
            // }

            if (!empty($searchCriteria['origen'])) {
                $builder->like('cotizacion.origen', $searchCriteria['origen']);
            }

            if (!empty($searchCriteria['divisa'])) {
                $builder->like('cotizacion_detalle.divisa', $searchCriteria['divisa']);
            }

            if (!empty($searchCriteria['impuesto'])) {
                $builder->like('cotizacion_detalle.impuesto', $searchCriteria['impuesto']);
            }

            if (!empty($searchCriteria['status_desarrollo'])) {
                $builder->having('status_desarrollo', $searchCriteria['status_desarrollo']);
            }

            if (!empty($searchCriteria['status_calidad'])) {
                $builder->having('status_calidad', $searchCriteria['status_calidad']);
            }

            if (!empty($searchCriteria['status_costos'])) {
                $builder->having('status_costos', $searchCriteria['status_costos']);
            }

        }

        // Fetch the filtered results
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


    public function getListaWithAprob($limit = 20)
    {
        $builder = $this->select('proveedor.razon_social as proveedor, 
            cotizacion_detalle.nombreDelArticulo as articulo, 
            cotizacion_detalle.costoPorUnidad as costo, 
            cotizacion_detalle.divisa, 
            cotizacion_detalle.impuesto, 
            cotizacion_detalle.medicion, 
            cotizacion_detalle.minimo, 
            cotizacion_detalle.importe, 
            cotizacion_detalle.diasDeEnvio, 
            cotizacion_detalle.cantidadPer, 
            cotizacion_detalle.periodo, 
            cotizacion_detalle.tipoDia, 
            cotizacion_detalle.id as art_id, 
            cotizacion.id as cotiz_id, 
            cotizacion.origen, 
            cotizacion.fecha, 
            cotizacion.vigencia, 
            COALESCE(cotizacion.incoterm, "-") as incoterm,
						COUNT(DISTINCT CASE 
                WHEN articulo_condicion.articuloId IS NOT NULL 
                AND articulo_condicion.condicion IS NOT NULL 
                AND articulo_condicion.condicion != "empty" 
                THEN articulo_condicion.id
            END) AS num_cond,
            COUNT(DISTINCT CASE 
                WHEN articulo_comment.articuloId IS NOT NULL 
                AND articulo_comment.comentario IS NOT NULL 
                AND articulo_comment.comentario != "empty" 
                THEN articulo_comment.id
            END) AS num_comm,
            COALESCE(
                MAX(CASE 
                    WHEN aprobaciones.area = "desarrollo" AND aprobaciones.articuloId IS NOT NULL 
                    THEN aprobaciones.status 
                END), "PENDIENTE"
            ) AS status_desarrollo,
            COALESCE(
                MAX(CASE 
                    WHEN aprobaciones.area = "calidad" AND aprobaciones.articuloId IS NOT NULL 
                    THEN aprobaciones.status 
                END), "PENDIENTE"
            ) AS status_calidad,
            COALESCE(
                MAX(CASE 
                    WHEN aprobaciones.area = "costos" AND aprobaciones.articuloId IS NOT NULL 
                    THEN aprobaciones.status 
                END), "PENDIENTE"
            ) AS status_costos


            ')
            ->join('proveedor', 'proveedor.id = cotizacion.proveedorId')
            ->join('cotizacion_detalle', 'cotizacion_detalle.cotizacionId = cotizacion.id')
            ->join('articulo_comment', 'articulo_comment.articuloId = cotizacion_detalle.id', 'left')
            ->join('articulo_condicion', 'articulo_condicion.articuloId = cotizacion_detalle.id', 'left')
            ->join('aprobaciones', 'aprobaciones.articuloId = cotizacion_detalle.id', 'left')
            ->groupBy('cotizacion_detalle.id')
            ->orderBy('cotizacion_detalle.id', 'DESC');

        // return as object PHP
        // return $builder->limit(5, 0)->get()->getResult();
        // return as array PHP
            // return $builder->get()->getResultArray();
        return $builder->limit($limit, 0)->get()->getResultArray();
    }

    public function getListaWithComment()
    {
        $builder = $this->select('proveedor.razon_social as proveedor, 
            cotizacion_detalle.nombreDelArticulo as articulo, 
            cotizacion_detalle.costoPorUnidad as costo, 
            cotizacion_detalle.divisa, 
            cotizacion_detalle.impuesto, 
            cotizacion_detalle.medicion, 
            cotizacion_detalle.minimo, 
            cotizacion_detalle.importe, 
            cotizacion_detalle.diasDeEnvio, 
            cotizacion_detalle.cantidadPer, 
            cotizacion_detalle.periodo, 
            cotizacion_detalle.tipoDia, 
            cotizacion_detalle.id as art_id, 
            cotizacion.id as cotiz_id, 
            cotizacion.origen, 
            cotizacion.fecha, 
            cotizacion.vigencia, 
            COALESCE(cotizacion.incoterm, "-") as incoterm,
            COUNT(CASE WHEN articulo_comment.comentario != "empty" THEN 1 END) AS num_comm')
            ->join('proveedor', 'proveedor.id = cotizacion.proveedorId')
            ->join('cotizacion_detalle', 'cotizacion_detalle.cotizacionId = cotizacion.id')
            ->join('articulo_comment', 'articulo_comment.articuloId = cotizacion_detalle.id')
            ->groupBy('cotizacion_detalle.id');


        // return as object PHP
        // return $builder->limit(5, 0)->get()->getResult();
        // return as array PHP
            // return $builder->get()->getResultArray();
        return $builder->limit(10, 0)->get()->getResultArray();
    }

            // COUNT(articulo_comment.comentario) as num_comm')


    public function searchSingleArt($cotiz_id, $art_id)
    {
        $builder = $this->select('proveedor.razon_social as proveedor, 
            cotizacion_detalle.nombreDelArticulo as articulo, 
            cotizacion_detalle.costoPorUnidad as costo, 
            cotizacion_detalle.divisa, 
            cotizacion_detalle.impuesto, 
            cotizacion_detalle.medicion, 
            cotizacion_detalle.minimo, 
            cotizacion_detalle.importe, 
            cotizacion_detalle.diasDeEnvio, 
            cotizacion_detalle.cantidadPer, 
            cotizacion_detalle.periodo, 
            cotizacion_detalle.tipoDia, 
            cotizacion_detalle.id as art_id, 
            cotizacion.id as cotiz_id, 
            cotizacion.origen, 
            cotizacion.fecha, 
            cotizacion.vigencia, 
            COALESCE(cotizacion.incoterm, "-") as incoterm')
            ->join('proveedor', 'proveedor.id = cotizacion.proveedorId')
            ->join('cotizacion_detalle', 'cotizacion_detalle.cotizacionId = cotizacion.id')
            ->where('cotizacion.id', $cotiz_id)
            ->where('cotizacion_detalle.id', $art_id);

        // return $builder->get()->getResultArray();
        return $builder->get()->getRowArray();
    }



}
