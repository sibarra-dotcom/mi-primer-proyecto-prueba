<?php

namespace App\Models;

use CodeIgniter\Model;

class ArticuloCondModel extends Model
{
    protected $table            = 'articulo_condicion';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['userId', 'articuloId', 'condicion'];

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

    public function countComments($articleId)
    {
        return $this->where('articuloId', $articleId)
                ->where('comentario !=', 'empty')
                ->countAllResults();
    }


}
