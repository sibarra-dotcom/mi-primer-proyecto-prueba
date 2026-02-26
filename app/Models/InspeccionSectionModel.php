<?php

namespace App\Models;

use CodeIgniter\Model;

class InspeccionSectionModel extends Model
{
    protected $table            = 'inspecciones_section';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['formatoId', 'titulo', 'section_number'];

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

		public function getSections($formatoId, array $excludeSections = [])
    {
			$builder = $this->select('
					inspecciones_items.id, 
					inspecciones_section.section_number, 
					inspecciones_section.titulo, 
					inspecciones_items.item_number, 
					inspecciones_items.description
					')
					->join('inspecciones_items', 'inspecciones_items.sectionId = inspecciones_section.id')
					->where('inspecciones_section.formatoId', $formatoId);

					if (!empty($excludeSections)) {
							$builder->whereNotIn('inspecciones_section.titulo', $excludeSections);
					}


					$builder->orderBy('inspecciones_section.section_number');
					$builder->orderBy('inspecciones_items.item_number');	

			return $builder->get()->getResultArray();

    }

		public function getSectionByTitle($formatoId, $title)
    {
			$builder = $this->select('
					inspecciones_items.id, 
					inspecciones_section.section_number, 
					inspecciones_section.titulo, 
					inspecciones_items.item_number, 
					inspecciones_items.description
					')
					->join('inspecciones_items', 'inspecciones_items.sectionId = inspecciones_section.id')
					->where('inspecciones_section.formatoId', $formatoId)
					->where('inspecciones_section.titulo', $title)
					->orderBy('inspecciones_section.section_number')
					->orderBy('inspecciones_items.item_number');	

			return $builder->get()->getResultArray();

    }

		public function getTitlesByFormatId($formatoId)
    {
			$builder = $this->select('
					inspecciones_section.id, 
					inspecciones_section.section_number, 
					inspecciones_section.titulo
					')
					->where('inspecciones_section.formatoId', $formatoId)
					->orderBy('CAST(section_number AS UNSIGNED)', 'ASC');

			return $builder->get()->getResultArray();
    }




}
