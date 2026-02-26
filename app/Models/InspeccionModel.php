<?php

namespace App\Models;

use CodeIgniter\Model;

class InspeccionModel extends Model
{
    protected $table            = 'inspecciones';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['userId', 'formatoId', 'calidadId', 'almacenId', 'firma_calidad', 'fecha_firma_calidad', 'firma_almacen', 'fecha_firma_almacen', 'upload_SAP'];

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



public function getAll($formatoId, $searchParams = [])
{
    // Step 1: Get sectionId for 'datos-generales'
    $sectionId = $this->db->table('inspecciones_section')
        ->select('id')
        ->where('formatoId', $formatoId)
        ->where('titulo', 'datos-generales')
        ->get()
        ->getRow('id');

    if (!$sectionId) {
        return [];
    }

    // Step 2: Fetch item descriptions
    $itemsQuery = $this->db->table('inspecciones_items')
        ->select('id, item_number, description')
        ->where('sectionId', $sectionId)
        ->get();

    $itemDescriptions = [];
    foreach ($itemsQuery->getResultArray() as $item) {
        $itemDescriptions[$item['item_number']] = [
            'id' => $item['id'],
            'description' => $item['description']
        ];
    }

    // Step 3: Fetch all inspecciones (only one row per inspeccion)
    $builder = $this->db->table('inspecciones')
        ->select('
            users.name, 
            users.last_name, 
            inspecciones.id AS inspeccion_id,
            inspecciones.created_at, 
            inspecciones.upload_SAP, 
            inspecciones_formato.slug, 
            inspecciones_formato.titulo AS formato_titulo
        ')
        ->join('users', 'users.id = inspecciones.userId')
        ->join('inspecciones_formato', 'inspecciones_formato.id = inspecciones.formatoId')
        ->where('inspecciones.formatoId', $formatoId)
        ->orderBy('inspecciones.id', 'DESC');

    $inspecciones = $builder->get()->getResultArray();

    // Step 4: Attach registros per inspeccion
    foreach ($inspecciones as $index => $row) {
        $inspeccionId = $row['inspeccion_id'];

        $registros = $this->db->table('inspecciones_registros')
            ->select('itemId, observacion')
            ->where('inspeccionId', $inspeccionId)
            ->get()
            ->getResultArray();

        $mappedObservations = $this->mapObservations($inspeccionId, $registros, $itemDescriptions);

        $row['observaciones'] = $mappedObservations;
        $inspecciones[$index] = $row;
    }

    // Step 5: Apply filters AFTER mapping
    if (!empty($searchParams)) {
        $inspecciones = array_filter($inspecciones, function ($row) use ($searchParams) {
            $obs = $row['observaciones'];


						// fecha_arribo (POST comes as Y-m-d, DB is d-m-Y)
						if (!empty($searchParams['fecha_arribo'])) {
								$date = \DateTime::createFromFormat('Y-m-d', $searchParams['fecha_arribo']);
								if ($date) {
										$formatted = $date->format('d-m-Y');
										if (stripos($obs['fecha_arribo'], $formatted) === false) {
												return false;
										}
								}
						}

						// fecha_caducidad (same logic)
						if (!empty($searchParams['fecha_caducidad'])) {
								$date = \DateTime::createFromFormat('Y-m-d', $searchParams['fecha_caducidad']);
								if ($date) {
										$formatted = $date->format('d-m-Y');
										if (stripos($obs['fecha_caducidad'], $formatted) === false) {
												return false;
										}
								}
						}

						// lote_interno (plain string)
						if (!empty($searchParams['lote_interno']) &&
								stripos($obs['lote_interno'], $searchParams['lote_interno']) === false) {
								return false;
						}

						if (!empty($searchParams['materia_prima']) &&
								stripos($obs['materia_prima'], $searchParams['materia_prima']) === false) {
								return false;
						}

						if (!empty($searchParams['proveedor']) &&
								stripos($obs['proveedor'], $searchParams['proveedor']) === false) {
								return false;
						}

            return true;
        });
    }

    return array_values($inspecciones);
}



public function getInspeccionesBySlug(string $slug, array $searchParams = []): array
{
    /*
     * 1. Resolve all formatos (all versions) for the slug
     */
    $formatos = $this->db->table('inspecciones_formato')
        ->select('id')
        ->where('slug', $slug)
        ->get()
        ->getResultArray();

    if (empty($formatos)) {
        return [];
    }

    $formatoIds = array_column($formatos, 'id');

    /*
     * 2. Resolve "datos-generales" sections per formato
     */
    $sections = $this->db->table('inspecciones_section')
        ->select('id, formatoId')
        ->whereIn('formatoId', $formatoIds)
        ->where('titulo', 'datos-generales')
        ->get()
        ->getResultArray();

    if (empty($sections)) {
        return [];
    }

    /*
     * Build sectionId → formatoId map
     */
    $sectionToFormato = [];
    foreach ($sections as $s) {
        $sectionToFormato[$s['id']] = $s['formatoId'];
    }

    $sectionIds = array_keys($sectionToFormato);

    /*
     * 3. Fetch items and group them by formatoId
     */
    $items = $this->db->table('inspecciones_items')
        ->select('id, sectionId, item_number, description')
        ->whereIn('sectionId', $sectionIds)
        ->get()
        ->getResultArray();

    if (empty($items)) {
        return [];
    }

    /*
     * Items grouped by formatoId and itemId
     */
    $itemsByFormato = [];

    foreach ($items as $item) {
        $formatoId = $sectionToFormato[$item['sectionId']];

        $itemsByFormato[$formatoId][$item['id']] = [
            'item_number' => $item['item_number'],
            'description' => $item['description'],
        ];
    }

    /*
     * 4. Fetch inspecciones (include formatoId!)
     */
    $inspecciones = $this->db->table('inspecciones')
        ->select([
            'users.name',
            'users.last_name',
            'inspecciones.id AS inspeccion_id',
            'inspecciones.created_at',
            'inspecciones.upload_SAP',
            'inspecciones.formatoId',
            'inspecciones_formato.slug',
            'inspecciones_formato.titulo AS formato_titulo',
            'inspecciones_formato.version',
        ])
        ->join('users', 'users.id = inspecciones.userId')
        ->join('inspecciones_formato', 'inspecciones_formato.id = inspecciones.formatoId')
        ->whereIn('inspecciones.formatoId', $formatoIds)
        ->orderBy('inspecciones.id', 'DESC')
        ->get()
        ->getResultArray();

    if (empty($inspecciones)) {
        return [];
    }

    /*
     * 5. Attach registros and map observaciones per inspección version
     */
    foreach ($inspecciones as &$row) {

        $registros = $this->db->table('inspecciones_registros')
            ->select('itemId, observacion')
            ->where('inspeccionId', $row['inspeccion_id'])
            ->get()
            ->getResultArray();

        $formatoId = $row['formatoId'];

        $itemDescriptions = $itemsByFormato[$formatoId] ?? [];

        if ($slug === 'materias-primas') {
            $row['observaciones'] = $this->mapObservations(
                $registros,
                $itemDescriptions,
                $this->getMateriasMapping()
            );
        }

        if ($slug === 'materiales') {
            $row['observaciones'] = $this->mapObservations(
                $registros,
                $itemDescriptions,
                $this->getMaterialesMapping()
            );
        }
    }
    unset($row);

    /*
     * 6. Apply filters
     */
    if ($slug === 'materias-primas') {
        return $this->filterMaterias($inspecciones, $searchParams);
    }

    if ($slug === 'materiales') {
        return $this->filterMateriales($inspecciones, $searchParams);
    }

    return $inspecciones;
}




private function mapObservations(
    array $registros,
    array $itemDescriptions,
    array $mapping
): array {
    $observaciones = [];

    foreach ($itemDescriptions as $itemId => $desc) {
        $value = 'N/A';

        foreach ($registros as $r) {
            if ((int)$r['itemId'] === (int)$itemId) {
                $value = $r['observacion'];
                break;
            }
        }

        $itemNumber = $desc['item_number'];
        $field = $mapping[$itemNumber] ?? $itemNumber;

        $observaciones[$field] = $value;
        $observaciones['description_' . $itemNumber] = $desc['description'];
    }

    return $observaciones;
}

private function getMateriasMapping(): array
{
    return [
        '1.1' => 'lote_interno',
        '1.2' => 'materia_prima',
        '1.7' => 'proveedor',
        '1.8' => 'fecha_caducidad',
        '1.5' => 'fecha_arribo',
    ];
}

private function getMaterialesMapping(): array
{
    return [
        '1.1' => 'lote_interno',
        '1.2' => 'materia_prima',
        '1.8' => 'proveedor',
        '1.9' => 'fecha_caducidad',
        '1.6' => 'fecha_arribo',
    ];
}


private function filterMaterias(array $rows, array $searchParams): array
{
    if (empty($searchParams)) {
        return $rows;
    }

    return array_values(array_filter($rows, function ($row) use ($searchParams) {
        $obs = $row['observaciones'];

        $dateFields = ['fecha_arribo', 'fecha_caducidad'];

        foreach ($dateFields as $field) {
            if (!empty($searchParams[$field])) {
                $date = \DateTime::createFromFormat('Y-m-d', $searchParams[$field]);
                if ($date) {
                    if (stripos($obs[$field] ?? '', $date->format('d-m-Y')) === false) {
                        return false;
                    }
                }
            }
        }

        foreach (['lote_interno', 'materia_prima', 'proveedor'] as $field) {
            if (!empty($searchParams[$field]) &&
                stripos($obs[$field] ?? '', $searchParams[$field]) === false) {
                return false;
            }
        }

        return true;
    }));
}


private function filterMateriales(array $rows, array $searchParams): array
{
    if (empty($searchParams)) {
        return $rows;
    }

    return array_values(array_filter($rows, function ($row) use ($searchParams) {
        $obs = $row['observaciones'];

        $dateFields = ['fecha_arribo', 'fecha_caducidad'];

        foreach ($dateFields as $field) {
            if (!empty($searchParams[$field])) {
                $date = \DateTime::createFromFormat('Y-m-d', $searchParams[$field]);
                if ($date) {
                    if (stripos($obs[$field] ?? '', $date->format('d-m-Y')) === false) {
                        return false;
                    }
                }
            }
        }

        foreach (['lote_interno', 'materia_prima', 'proveedor'] as $field) {
            if (!empty($searchParams[$field]) &&
                stripos($obs[$field] ?? '', $searchParams[$field]) === false) {
                return false;
            }
        }

        return true;
    }));
}






    public function countComments($articleId)
    {
        return $this->where('articuloId', $articleId)
                ->where('comentario !=', 'empty')
                ->countAllResults();
    }

}
