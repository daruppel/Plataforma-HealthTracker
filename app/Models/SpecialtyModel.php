<?php

namespace App\Models;

use CodeIgniter\Model;

class SpecialtyModel extends Model
{
    // Datos de especialidad
    protected $table = 'especialidad';
    protected $primaryKey = 'especialidad_id';
    protected $returnType = 'array'; // 'array', 'object' o nombre de clase
    //Activa el borrado logico cuando se utiliza '$modelo->delete($id);'
    protected $useSoftDeletes = true;
    
    // Campos que se pueden insertar/actualizar
    protected $allowedFields = [
        'nombre'
    ];

    
    // Validaciones del modelo
    protected $validationRules = [
        'nombre' => 'required|min_length[3]|max_length[100]'
    ];
    
    protected $validationMessages = [
        'nombre' => [
            'required' => 'El nombre de la especialidad es obligatorio',
            'min_length' => 'El nombre debe tener al menos 3 caracteres',
            'max_length' => 'El nombre no puede exceder 100 caracteres'
        ]
    ];
    
    // Validación solo en insert (no en update)
    protected $skipValidation = false;
}