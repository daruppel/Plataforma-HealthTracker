<?php

namespace App\Models;
use CodeIgniter\Model;

class CarePlanTaskModel extends Model
{
    protected $table = 'metas_plan_cuidado';
    protected $primaryKey = 'metas_plan_cuidado_id';
    protected $returnType = 'array'; // 'array', 'object' o nombre de clase
    //Activa el borrado logico cuando se utiliza '$modelo->delete($id);'
    protected $useSoftDeletes = true;
    
    // Campos que se pueden insertar/actualizar
    protected $allowedFields = [
        'plan_cuidado_id',
        'tipo_meta_id',
        'meta_cumplida',
        'descripcion',
    ];

    // Timestamps automáticos
    protected $useTimestamps = true;
    protected $createdField = 'created_at';  // Campo para fecha de creación
    protected $updatedField = 'updated_at';  // Campo para fecha de actualización
    protected $deletedField = 'deleted_at';  // Campo para fecha de eliminación
    
    // Validaciones del modelo
    protected $validationRules = [
        'plan_cuidado_id' => 'required|integer',
        'tipo_meta_id'    => 'required|integer',
        'meta_cumplida'   => 'permit_empty|in_list[0,1]',
        'descripcion'     => 'required|min_length[3]',
    ];
    
    protected $validationMessages = [
       'plan_cuidado_id' => [
            'required'     => 'El plan de cuidado es obligatorio.',
        ],
        'meta_cumplida' => [
            'in_list' => 'El estado de cumplimiento no es válido.',
        ],
        'descripcion' => [
            'required'   => 'La descripción es obligatoria.',
            'min_length' => 'La descripción debe tener al menos 3 caracteres.',
        ],
    ];
    // Validación solo en insert (no en update)
    protected $skipValidation = false;
}