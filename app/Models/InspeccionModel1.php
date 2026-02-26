<?php

namespace App\Models;

use CodeIgniter\Model;

class InspeccionModel1 extends Model
{
    protected $table            = 'inspecciones';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['userId', 'formatoId', 'calidadId', 'almacenId', 'firma_calidad', 'fecha_firma_calidad', 'firma_almacen', 'fecha_firma_almacen'];

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

		public function getAll1($formatoId)
    {
        $builder = $this->select('
						users.name, 
            users.last_name, 
            inspecciones.*
						')
            ->join('users', 'users.id = inspecciones.userId')
            ->where('inspecciones.formatoId', $formatoId)
            ->orderBy('inspecciones.id', 'DESC');

        return $builder->get()->getResultArray();
    }


public function getAll($formatoId, $searchParams = [])
{
    // Step 1: Get the sectionId for 'datos-generales' (titulo) in the inspecciones_section table
    $sectionQuery = $this->db->table('inspecciones_section')
        ->select('id')
        ->where('formatoId', $formatoId)
        ->where('titulo', 'datos-generales')
        ->get();

    // If no section found, return an empty result
    if ($sectionQuery->getNumRows() == 0) {
        return [];
    }

    $sectionId = $sectionQuery->getRow()->id;  // Get sectionId for 'datos-generales'

    // Step 2: Fetch items from inspecciones_items table for this sectionId
    $itemsQuery = $this->db->table('inspecciones_items')
        ->select('id, item_number, description')
        ->where('sectionId', $sectionId)
        ->get();

    // Prepare an associative array of item_number => [item_id, description]
    $itemDescriptions = [];
    foreach ($itemsQuery->getResultArray() as $item) {
        $itemDescriptions[$item['item_number']] = [
            'id' => $item['id'],  // item_id
            'description' => $item['description']
        ];
    }


    // Start building the main query with necessary joins
    $builder = $this->db->table('inspecciones')
        ->select('
            users.name, 
            users.last_name, 
            inspecciones.id AS inspeccion_id,
            inspecciones.created_at, 
            inspecciones_formato.slug, 
            inspecciones_formato.titulo AS formato_titulo,
            GROUP_CONCAT(DISTINCT inspecciones_items.item_number ORDER BY inspecciones_items.item_number) AS item_numbers,  
            GROUP_CONCAT(DISTINCT inspecciones_registros.observacion ORDER BY inspecciones_items.item_number) AS observaciones')  
        ->join('users', 'users.id = inspecciones.userId')
        ->join('inspecciones_formato', 'inspecciones_formato.id = inspecciones.formatoId')
        ->join('inspecciones_section', 'inspecciones_section.formatoId = inspecciones.formatoId')
        ->join('inspecciones_items', 'inspecciones_items.sectionId = inspecciones_section.id')
        ->join('inspecciones_registros', 'inspecciones_registros.itemId = inspecciones_items.id')
        ->where('inspecciones.formatoId', $formatoId)
        ->groupBy('inspecciones.id, users.name, users.last_name, inspecciones.created_at, inspecciones_formato.slug, inspecciones_formato.titulo')
        ->orderBy('inspecciones.id', 'DESC');

    // If search params are provided, filter the query based on the 'observacion' field
    if (!empty($searchParams)) {

        if (!empty($searchParams['lote_interno'])) {
            $builder->like('inspecciones_registros.observacion', '%' . $searchParams['lote_interno'] . '%');
        }

        if (!empty($searchParams['materia'])) {
            $builder->like('inspecciones_registros.observacion', '%' . $searchParams['materia'] . '%');
        }

        if (!empty($searchParams['proveedor'])) {
            $builder->like('inspecciones_registros.observacion', '%' . $searchParams['proveedor'] . '%');
        }


				if (!empty($searchParams['fecha_arribo'])) {
            $date = \DateTime::createFromFormat('Y-m-d', $searchParams['fecha_arribo'])->format('d-m-Y');
            $builder->like('inspecciones_registros.observacion', $date);
        }

        if (!empty($searchParams['fecha_caducidad'])) {
            $date = \DateTime::createFromFormat('Y-m-d', $searchParams['fecha_caducidad'])->format('d-m-Y');
            $builder->like('inspecciones_registros.observacion', $date);
        }

        // Lote Interno filter (exact match or partial match based on your needs)
        if (!empty($searchParams['lote_interno'])) {
            $builder->where('inspecciones_registros.observacion', $searchParams['lote_interno']);
        }


    }

    // Execute the query
    $result = $builder->get()->getResultArray();

    // Step 3: Process the results to filter the observations based on item numbers and inspeccion_id
    foreach ($result as $index => $row) {
        $itemNumbers = explode(",", $row['item_numbers']);
        $observations = explode(",", $row['observaciones']);

        // Map item numbers to their corresponding observations using item descriptions
        $mappedObservations = $this->mapObservations($row['inspeccion_id'], $itemNumbers, $observations, $itemDescriptions);

        // Replace the `observaciones` field with the correctly mapped observations
        $row['observaciones'] = $mappedObservations;

        // Update the result with the processed observations
        $result[$index] = $row;
    }

    return $result;
}

/**
 * Maps the item numbers to their corresponding observations based on the inspeccion_id.
 */
private function mapObservations($inspeccionId, $itemNumbers, $observations, $itemDescriptions)
{
    $mappedObservations = [];

    // Define which item numbers correspond to which fields
    $mapping = [
        '1.1' => 'lote_interno',         
        '1.2' => 'materia_prima',      
        '1.7' => 'proveedor',     
        '1.8' => 'fecha_arribo',     
        '1.5' => 'fecha_caducidad',   
    ];

    // Iterate over the item numbers and map the corresponding observations
    foreach ($itemNumbers as $index => $itemNumber) {
        // Check if the item number exists in the itemDescriptions array
        if (isset($itemDescriptions[$itemNumber])) {
            $itemId = $itemDescriptions[$itemNumber]['id'];
            // Query for the correct observation for this item_id and inspeccion_id
            $observacionQuery = $this->db->table('inspecciones_registros')
                ->select('observacion')
                ->where('inspeccionId', $inspeccionId)
                ->where('itemId', $itemId)
                ->get();

            $observacion = $observacionQuery->getRow();

            // Check if there's a valid observation
            if (isset($observacion)) {
                $mappedObservations[$mapping[$itemNumber] ?? $itemNumber] = $observacion->observacion;
            } else {
                $mappedObservations[$mapping[$itemNumber] ?? $itemNumber] = 'N/A';  // Default to 'N/A' if no observation
            }
        } else {
            // If the itemNumber doesn't exist in itemDescriptions, map it as 'N/A'
            $mappedObservations[$mapping[$itemNumber] ?? $itemNumber] = 'N/A';
        }

        // Optionally, map descriptions as well, in case you need to display them somewhere else
        if (isset($itemDescriptions[$itemNumber])) {
            $mappedObservations['description_' . $itemNumber] = $itemDescriptions[$itemNumber]['description'];
        }
    }

    // Ensure all fields are set to 'N/A' if they're missing
    foreach ($mapping as $key => $field) {
        if (!isset($mappedObservations[$field])) {
            $mappedObservations[$field] = 'N/A';
        }
    }

    return $mappedObservations;
}






    public function countComments($articleId)
    {
        return $this->where('articuloId', $articleId)
                ->where('comentario !=', 'empty')
                ->countAllResults();
    }

}
