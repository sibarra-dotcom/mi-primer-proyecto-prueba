<?php

namespace App\Models;

use CodeIgniter\Model;

class Proveedor extends Model
{
    protected $table            = 'proveedor';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ["razon_social", "direccion", "pais", "tipo_prov", "sitio_web"];

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


    public function getProveedorContacto($id_proveedor)
    {
        $proveedor = $this->where('id_proveedor', $id_proveedor)->first();
        
        if (!$proveedor) {
            return null; // Return null if no provider found
        }

        $contactoModel = new ProveedorContactoModel();
        $contactos = $contactoModel->where('id_proveedor', $id_proveedor)->findAll();

        return [
            'proveedor' => $proveedor,
            'contactos' => $contactos
        ];
    }

    public function search(array $searchCriteria = [])
    {
        $builder = $this->select('
						proveedor.id,
						proveedor.razon_social,
						proveedor.direccion,
						proveedor.pais,
						proveedor_contacto.nombre,
						proveedor_contacto.puesto,
						proveedor_contacto.telefono
					')

					->join('proveedor_contacto', 'proveedor_contacto.proveedorId = proveedor.id', 'left')
					->groupBy('proveedor.id')
					->orderBy('proveedor.id', 'DESC');

        if (!empty($searchCriteria)) {
            if (!empty($searchCriteria['razon_social'])) {
                $builder->like('proveedor.razon_social', $searchCriteria['razon_social']);
            }

            if (!empty($searchCriteria['contacto'])) {
                $builder->like('proveedor_contacto.nombre', $searchCriteria['contacto']);
            }

            if (!empty($searchCriteria['direccion'])) {
								$builder->like('proveedor.direccion', $searchCriteria['direccion']);
						}
						if (!empty($searchCriteria['pais'])) {
								$builder->like('proveedor.pais', $searchCriteria['pais']);
						}

        }

        return $builder->findAll();
    }



}



