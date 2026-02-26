<?php

namespace App\Models;

use CodeIgniter\Model;

class FormatosDocsModel extends Model
{
    protected $table            = 'formatos_docs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['slug', 'titulo', 'clave', 'version', 'paginas', 'revision', 'vigencia'];

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

		public function getCurrentFormatoBySlug($slug = null)
    {
			return $this->select('formatos_docs.*')
					->where('slug', $slug)
					->where('vigencia >= NOW()', null, false)
					->orderBy('vigencia', 'DESC')
					->get()
					->getRowArray();
		}

		public function getCommentsByMantId($mant_id)
    {
        $builder = $this->select('users.name, 
            users.last_name, 
            mant_comment.*')
            ->join('users', 'users.id = mant_comment.userId')
            ->where('mant_comment.mantId', $mant_id)
            ->where('mant_comment.comentario !=', 'empty');

        return $builder->get()->getResultArray();
    }

    public function countComments($articleId)
    {
        return $this->where('articuloId', $articleId)
                ->where('comentario !=', 'empty')
                ->countAllResults();
    }

}
