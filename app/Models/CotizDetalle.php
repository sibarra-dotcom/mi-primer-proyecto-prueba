<?php

namespace App\Models;

use CodeIgniter\Model;

class CotizDetalle extends Model
{
    protected $table            = 'cotizacion_detalle';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['cotizacionId', 'nombreDelArticulo', 'costoPorUnidad', 'divisa', 'impuesto', 'medicion', 'minimo', 'importe', 'diasDeEnvio', 'cantidadPer', 'periodo', 'tipoDia'];

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

    
    // Use get() + getResultArray() if you're using $this->db->table().
    // return $this->db->table($this->table)
    //     ->select('users.name as user_name, orders.id as order_id, products.name as product_name, categories.name as category_name, orders.quantity, orders.order_date')
    //     ->join('users', 'users.id = orders.user_id') // Join users table
    //     ->join('products', 'products.id = orders.product_id') // Join products table
    //     ->join('categories', 'categories.id = products.category_id') // Join categories table
    //     ->get()
    //     ->getResultArray(); // Fetch results as an array



}
