<?php

namespace App\Models;

use CodeIgniter\Model;

class MaquinariaModel extends Model
{
    protected $table            = 'maquinaria';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nombre', 'marca', 'modelo', 'serie', 'linea', 'planta', 'year', 'fechaAdqui'];

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

    public function search(array $searchCriteria = [])
    {
        $builder = $this->select('maquinaria.*');

        // Apply search filters dynamically
        if (!empty($searchCriteria)) {
            if (!empty($searchCriteria['maqId'])) {
                $builder->like('maquinaria.id', $searchCriteria['maqId']);
            }

            if (!empty($searchCriteria['nombre'])) {
                $builder->like('maquinaria.nombre', $searchCriteria['nombre']);
            }

            if (!empty($searchCriteria['marca'])) {
                $builder->like('maquinaria.marca', $searchCriteria['marca']);
            }


            if (!empty($searchCriteria['modelo'])) {
                $builder->like('maquinaria.modelo', $searchCriteria['modelo']);
            }

            if (!empty($searchCriteria['serie'])) {
                $builder->like('maquinaria.serie', $searchCriteria['serie']);
            }

            if (!empty($searchCriteria['planta'])) {
                $builder->like('maquinaria.planta', $searchCriteria['planta']);
            }

            if (!empty($searchCriteria['linea'])) {
                $builder->like('maquinaria.linea', $searchCriteria['linea']);
            }
        }

        // Fetch the filtered results
        return $builder->findAll();
    }

    public function getPlantas()
    {
        return $this->select('planta')->distinct()->findAll();
    }
}
