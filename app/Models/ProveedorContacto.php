<?php

namespace App\Models;

use CodeIgniter\Model;

class ProveedorContacto extends Model
{
    protected $table            = 'proveedor_contacto';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ["proveedorId", "nombre", "puesto", "telefono", "correo"];

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

    public function getAll()
    {
        return $this->findAll();
    }

    public function getContactoById1($contactoId)
    {
        $builder = $this->where('id', $contactoId)
            ->where('aprobaciones.area', $area)
            ->where('aprobaciones.comentario !=', 'empty');

        return $builder->get()->getRowArray();
    }

    public function getContactoById($contactoId)
    {
        return $this->find($contactoId); 
    }

}
