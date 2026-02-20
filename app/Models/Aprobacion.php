<?php

namespace App\Models;

use CodeIgniter\Model;

class Aprobacion extends Model
{
    protected $table            = 'aprobaciones';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['userId', 'articuloId', 'area', 'status', 'comentario'];

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

    public function getCommentsByArtId($art_id)
    {
        $builder = $this->select('users.name, 
            users.last_name, 
            articulo_comment.*')
            ->join('users', 'users.id = articulo_comment.userId')
            ->where('articulo_comment.articuloId', $art_id)
            ->where('articulo_comment.comentario !=', 'empty');

        return $builder->get()->getResultArray();
    }

    public function getAprobacionByArtId($art_id, $area)
    {
        $builder = $this->select('users.name, 
            users.last_name, 
            aprobaciones.*')
            ->join('users', 'users.id = aprobaciones.userId')
            ->where('aprobaciones.articuloId', $art_id)
            ->where('aprobaciones.area', $area)
            ->where('aprobaciones.comentario !=', 'empty');

        return $builder->get()->getRowArray();

        // getResultArray() → Returns multiple rows as an array of arrays.
        // getRowArray() → Returns only one row as an associative array.
        // getRow() → Returns one row as an object (instead of an array).

    }

    public function countComments($art_id)
    {
        return $this->where('articuloId', $art_id)
                ->where('comentario !=', 'empty')
                ->countAllResults();
    }

}
