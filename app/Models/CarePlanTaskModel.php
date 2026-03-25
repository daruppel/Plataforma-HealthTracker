<?php

namespace App\Models;
use CodeIgniter\Model;

class CarePlanTaskModel extends Model
{
    protected $table = 'meta_plan_cuidado';
    protected $primaryKey = 'meta_plan_cuidado_id';
    protected $returnType = 'array'; // 'array', 'object' o nombre de clase
    //Activa el borrado logico cuando se utiliza '$modelo->delete($id);'
    protected $useSoftDeletes = true;
    
    // Campos que se pueden insertar/actualizar
    protected $allowedFields = [
        'plan_cuidado_id',
        'tipo_meta_id',
        'meta_cumplida',
        'descripcion'
    ];

    // Timestamps automáticos
    protected $useTimestamps = true;
    protected $createdField = 'created_at';  // Campo para fecha de creación
    protected $updatedField = 'updated_at';  // Campo para fecha de actualización
    protected $deletedField = 'deleted_at';  // Campo para fecha de eliminación
    
    // Validaciones del modelo
    protected $validationRules = [
        'descripcion' => 'required|min_length[3]|max_length[100]',
        //'comentario_paciente' => 'required|min_length[3]|max_length[100]'
    ];
    
    protected $validationMessages = [
       'descripcion' => [
            'required' => 'La descripción es obligatoria',
            'min_length' => 'La descripción debe tener al menos 3 caracteres',
            'max_length' => 'La descripción no puede exceder 100 caracteres'
        ],
    ];
    
    // Validación solo en insert (no en update)
    protected $skipValidation = false;
}