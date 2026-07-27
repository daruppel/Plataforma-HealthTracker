<?php

namespace App\Models;
use CodeIgniter\Model;

class CarePlanTemplateTaskModel extends Model
{
    protected $table      = 'plan_cuidado_estandar_tarea';
    protected $primaryKey = 'plan_cuidado_estandar_tarea_id';
    protected $returnType = 'array';
    // Activa el borrado lógico cuando se utiliza '$modelo->delete($id);'
    protected $useSoftDeletes = true;

    // Campos que se pueden insertar/actualizar
    protected $allowedFields = [
        'plan_cuidado_estandar_id',
        'tipo_meta_id',
        'descripcion',
    ];

    // Timestamps automáticos
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validaciones del modelo
    protected $validationRules = [
        'plan_cuidado_estandar_id' => 'required|integer',
        'tipo_meta_id'             => 'required|integer',
        'descripcion'              => 'required|min_length[3]|max_length[150]',
    ];

    protected $validationMessages = [
        'plan_cuidado_estandar_id' => [
            'required' => 'El plan de cuidado estandarizado es obligatorio.',
            'integer'  => 'El plan de cuidado estandarizado no es válido.',
        ],
        'tipo_meta_id' => [
            'required' => 'El tipo de meta es obligatorio.',
            'integer'  => 'El tipo de meta no es válido.',
        ],
        'descripcion' => [
            'required'   => 'La descripción es obligatoria.',
            'min_length' => 'La descripción debe tener al menos 3 caracteres.',
            'max_length' => 'La descripción no puede exceder 150 caracteres.',
        ],
    ];

    protected $skipValidation = false;

    /**
     * Retorna las tareas de una plantilla con JOIN a tipo_meta.
     */
    public function getByTemplate(int $templateId): array
    {
        return $this->select('plan_cuidado_estandar_tarea.*, tipo_meta.nombre AS tipo_meta_nombre')
            ->join('tipo_meta', 'tipo_meta.tipo_meta_id = plan_cuidado_estandar_tarea.tipo_meta_id', 'left')
            ->where('plan_cuidado_estandar_id', $templateId)
            ->findAll();
    }
}
