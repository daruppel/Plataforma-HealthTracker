<?php

namespace App\Models;
use CodeIgniter\Model;

class CarePlanModel extends Model
{
    // Datos de plan de cuidados
    protected $table = 'plan_cuidado';
    protected $primaryKey = 'plan_cuidado_id';
    protected $returnType = 'array'; // 'array', 'object' o nombre de clase
    //Activa el borrado logico cuando se utiliza '$modelo->delete($id);'
    protected $useSoftDeletes = true;
    
    // Campos que se pueden insertar/actualizar
    protected $allowedFields = [
        'fec_inicio',
        'fec_fin',
        'comentario_paciente',
        'diagnostico_id'
    ];

    // Timestamps automáticos
    protected $useTimestamps = true;
    protected $createdField = 'created_at';  // Campo para fecha de creación
    protected $updatedField = 'updated_at';  // Campo para fecha de actualización
    protected $deletedField = 'deleted_at';  // Campo para fecha de eliminación
    
    // Validaciones del modelo
    protected $validationRules = [
        //'nombre' => 'required|min_length[3]|max_length[100]',
        //'comentario_paciente' => 'required|min_length[3]|max_length[100]',
        'fec_inicio' => 'required|valid_date[d/m/Y]',
        'fec_fin' => 'required|valid_date[d/m/Y]'
    ];
    
    protected $validationMessages = [
      /*  'nombre' => [
            'required' => 'El nombre es obligatorio',
            'min_length' => 'El nombre debe tener al menos 3 caracteres',
            'max_length' => 'El nombre no puede exceder 100 caracteres'
        ], */
        'fec_inicio' => [
            'required' => 'El descripcion es obligatoria',
            'min_length' => 'El descripcion debe tener al menos 3 caracteres'
        ],
        'fec_fin' => [
            'required' => 'El descripcion es obligatoria',
            'min_length' => 'El descripcion debe tener al menos 3 caracteres'
        ],
    ];
    
    // Validación solo en insert (no en update)
    protected $skipValidation = false;
}