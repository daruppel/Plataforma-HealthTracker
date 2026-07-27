<?php

namespace App\Models;
use CodeIgniter\Model;

class CarePlanTemplateModel extends Model
{
    // Datos de plan de cuidado estandarizado
    protected $table      = 'plan_cuidado_estandar';
    protected $primaryKey = 'plan_cuidado_estandar_id';
    protected $returnType = 'array';
    // Activa el borrado lógico cuando se utiliza '$modelo->delete($id);'
    protected $useSoftDeletes = true;

    // Campos que se pueden insertar/actualizar
    protected $allowedFields = [
        'tipo_diagnostico_id',
        'nombre',
        'descripcion',
        'activo',
    ];

    // Timestamps automáticos
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validaciones del modelo
    protected $validationRules = [
        'tipo_diagnostico_id' => 'required|integer',
        'nombre'              => 'required|min_length[3]|max_length[100]',
        'descripcion'         => 'permit_empty|max_length[255]',
        'activo'              => 'permit_empty|in_list[0,1]',
    ];

    protected $validationMessages = [
        'tipo_diagnostico_id' => [
            'required' => 'El tipo de diagnóstico es obligatorio.',
            'integer'  => 'El tipo de diagnóstico no es válido.',
        ],
        'nombre' => [
            'required'   => 'El nombre es obligatorio.',
            'min_length' => 'El nombre debe tener al menos 3 caracteres.',
            'max_length' => 'El nombre no puede exceder 100 caracteres.',
        ],
        'descripcion' => [
            'max_length' => 'La descripción no puede exceder 255 caracteres.',
        ],
    ];

    protected $skipValidation = false;

    /**
     * Retorna todos los planes con JOIN a tipo_diagnostico y conteo de tareas.
     */
    public function getAllWithDetails(): array
    {
        return $this->db->table('plan_cuidado_estandar pce')
            ->select([
                'pce.*',
                'td.nombre AS tipo_diagnostico_nombre',
                'COUNT(pcet.plan_cuidado_estandar_tarea_id) AS cantidad_tareas',
            ])
            ->join('tipo_diagnostico td', 'td.tipo_diagnostico_id = pce.tipo_diagnostico_id', 'left')
            ->join('plan_cuidado_estandar_tarea pcet', 'pcet.plan_cuidado_estandar_id = pce.plan_cuidado_estandar_id AND pcet.deleted_at IS NULL', 'left')
            ->where('pce.deleted_at', null)
            ->groupBy('pce.plan_cuidado_estandar_id')
            ->orderBy('pce.nombre', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Retorna planes activos filtrados por tipo_diagnostico_id (para precargar en Doctor\CarePlan).
     */
    public function getActiveByDiagnosisType(int $tipoDiagnosticoId): array
    {
        return $this->where('tipo_diagnostico_id', $tipoDiagnosticoId)
            ->where('activo', 1)
            ->findAll();
    }
}
