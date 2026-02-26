<?php

namespace App\Models;

use CodeIgniter\Model;

class ArticuloAdj extends Model
{
    protected $table            = 'articulo_adjunto';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['userId', 'articuloId', 'archivo'];

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


    public function getFicha($artId)
    {
        $builder = $this->select('
            cotizacion_detalle.nombreDelArticulo AS articulo,
						articulo_adjunto.created_at AS fecha, 
            articulo_adjunto.archivo,
            users.name,
            users.last_name
            ')
            ->join('cotizacion_detalle', 'cotizacion_detalle.id = articulo_adjunto.articuloId')
            ->join('users', 'users.id = articulo_adjunto.userId')
            ->where('cotizacion_detalle.id', $artId);


        // return as object PHP
        // return $builder->limit(5, 0)->get()->getResult();
        // return as array PHP
        return $builder->get()->getResultArray();
    }

}
