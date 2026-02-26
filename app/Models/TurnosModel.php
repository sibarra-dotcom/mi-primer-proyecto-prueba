<?php

namespace App\Models;

use CodeIgniter\Model;

class TurnosModel extends Model
{
    protected $table            = '_turnos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
			'plantaId',
			'name',
			'start_time',
			'end_time',
			'type'
		];

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

    public function getAllTurnos()
    {
        return $this->findAll();
    }

		public function getTurnosWithPlanta()
    {
        return $this->select('
                _turnos.id,
                CONCAT(_plantas.name, " - ", _turnos.name) AS label
            ')
            ->join('_plantas', '_plantas.id = _turnos.plantaId')
            ->orderBy('_plantas.name')
            ->findAll();
    }

		public function getPlantaTurnoById($turnoId)
    {
        return $this->select('
                _turnos.id,
                CONCAT(_plantas.name, " - ", _turnos.name) AS label
            ')
            ->join('_plantas', '_plantas.id = _turnos.plantaId')
            ->where('_turnos.id', $turnoId)
            ->first();
    }

		

}
