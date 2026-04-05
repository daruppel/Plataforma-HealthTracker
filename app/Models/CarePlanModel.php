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
        'comentario_paciente'
    ];

    // Timestamps automáticos
    protected $useTimestamps = true;
    protected $createdField = 'created_at';  // Campo para fecha de creación
    protected $updatedField = 'updated_at';  // Campo para fecha de actualización
    protected $deletedField = 'deleted_at';  // Campo para fecha de eliminación
    
    // Validaciones del modelo
    protected $validationRules = [
        'fec_inicio' => 'required|valid_date[Y-m-d]',
        'fec_fin' => 'required|valid_date[Y-m-d]',
        'comentario_paciente' => 'permit_empty|min_length[3]'

    ];
    
    protected $validationMessages = [
        'fec_inicio' => [
            'required'   => 'La fecha de inicio es obligatoria.',
            'valid_date' => 'La fecha de inicio debe tener un formato válido.',
        ],
        'fec_fin' => [
            'required'   => 'La fecha de fin es obligatoria.',
            'valid_date' => 'La fecha de fin debe tener un formato válido.',
        ],
        'comentario_paciente' => [
            'min_length' => 'El comentario del paciente debe tener al menos 3 caracteres.',
            'max_length' => 'El comentario del paciente no puede superar los 1000 caracteres.',
        ],
    ];
    
    // Validación solo en insert (no en update)
    protected $skipValidation = false;

     //Devuelve los planes de cuidado asociados a un diagnostico 
    public function getCarePlanWithDiagnosis($diagnosticoId){
        return $this->select('*')
                    ->join('diagnostico', 'diagnostico.plan_cuidado_id = plan_cuidado.plan_cuidado_id', 'left')
                    ->where('diagnostico.diagnostico_id', $diagnosticoId)
                    ->first();
    }
}