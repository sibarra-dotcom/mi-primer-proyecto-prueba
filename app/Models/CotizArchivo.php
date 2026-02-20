<?php

namespace App\Models;

use CodeIgniter\Model;

class CotizArchivo extends Model
{
    protected $table            = 'cotizacion_archivo';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['cotizacionId', 'archivo'];

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


    public function getAdjuntos($cotiz_id)
    {
        $builder = $this->select('cotizacion_archivo.created_at as fecha, 
            cotizacion_archivo.archivo,
            users.name,
            users.last_name
            ')
            ->join('cotizacion', 'cotizacion.id = cotizacion_archivo.cotizacionId')
            ->join('users', 'cotizacion.userId = users.id')
            ->where('cotizacion.id', $cotiz_id);


        // return as object PHP
        // return $builder->limit(5, 0)->get()->getResult();
        // return as array PHP
        return $builder->get()->getResultArray();
    }

}
