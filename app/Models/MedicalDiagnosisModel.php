<?php

namespace App\Models;
use CodeIgniter\Model;

class MedicalDiagnosisModel extends Model
{
    // Datos de tipo de diagnosticos
    protected $table = 'tipo_diagnostico';
    protected $primaryKey = 'tipo_diagnostico_id';
    protected $returnType = 'array'; // 'array', 'object' o nombre de clase
    //Activa el borrado logico cuando se utiliza '$modelo->delete($id);'
    protected $useSoftDeletes = true;
    
    // Campos que se pueden insertar/actualizar
    protected $allowedFields = [
        'nombre',
        'descripcion'
    ];

    // Timestamps automáticos
    protected $useTimestamps = true;
    protected $createdField = 'created_at';  // Campo para fecha de creación
    protected $updatedField = 'updated_at';  // Campo para fecha de actualización
    protected $deletedField = 'deleted_at';  // Campo para fecha de eliminación
    
    // Validaciones del modelo
    protected $validationRules = [
        'nombre' => 'required|min_length[3]|max_length[100]',
        'descripcion' => 'required|min_length[3]|max_length[100]',
    ];
    
    protected $validationMessages = [
        'nombre' => [
            'required' => 'El nombre es obligatorio',
            'min_length' => 'El nombre debe tener al menos 3 caracteres',
            'max_length' => 'El nombre no puede exceder 100 caracteres'
        ],
        'descripcion' => [
            'required' => 'El descripcion es obligatoria',
            'min_length' => 'El descripcion debe tener al menos 3 caracteres'
        ],
    ];
    
    // Validación solo en insert (no en update)
    protected $skipValidation = false;
}