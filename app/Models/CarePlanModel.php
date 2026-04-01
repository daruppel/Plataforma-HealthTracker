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
        'fec_inicio' => 'required|valid_date[Y-m-d]',
        'fec_fin' => 'required|valid_date[Y-m-d]'
    ];
    
    protected $validationMessages = [
      /*  'nombre' => [
            'required' => 'El nombre es obligatorio',
            'min_length' => 'El nombre debe tener al menos 3 caracteres',
            'max_length' => 'El nombre no puede exceder 100 caracteres'
        ], */
        'fec_inicio' => [
            'required' => 'La fecha de inicio es obligatoria'
        ],
        'fec_fin' => [
            'required' => 'La fecha de fin es obligatoria'
        ],
    ];
    
    // Validación solo en insert (no en update)
    protected $skipValidation = false;

     //Devuelve los planes de cuidado asociados a un diagnostico 
    public function getCarePlanWithDiagnosis($diagnosticoId){
        return $this->select('*')
                    ->join('diagnostico', 'diagnostico.diagnostico_id = plan_cuidado.diagnostico_id', 'left')
                    ->where('diagnostico.diagnostico_id', $diagnosticoId)
                    ->first();
    }
}