<?php

namespace App\Models;

use CodeIgniter\Model;

class DiagnosisModel extends Model
{
    // Datos de diagnostico
    protected $table = 'diagnostico';
    protected $primaryKey = 'diagnostico_id';
    protected $returnType = 'array'; // 'array', 'object' o nombre de clase
    //Activa el borrado logico cuando se utiliza '$modelo->delete($id);'
    protected $useSoftDeletes = true;
    
    // Campos que se pueden insertar/actualizar
    protected $allowedFields = [
        'tipo_diagnostico_id',
        'paciente_id',
        'fecha',
        'estado_id'
    ];

    // Timestamps automáticos
    protected $useTimestamps = true;
    protected $createdField = 'created_at';  // Campo para fecha de creación
    protected $updatedField = 'updated_at';  // Campo para fecha de actualización
    protected $deletedField = 'deleted_at';  // Campo para fecha de eliminación
    
    // Validaciones del modelo
    protected $validationRules = [
        'role_id' => 'required|in_list[2]' //Verifica que el rol sea Medico.
    ];
    
   protected $validationMessages = [
        'role_id' => [
            'required' => 'El rol es obligatorio',
            'in_list' => 'Debe ser medico para cargar un diagnostico'
        ]
    ];
    
    // Validación solo en insert (no en update)
    protected $skipValidation = false;

   public function findAllByDoctor($doctorId){
        return $this->select('diagnostico.*, usuarios.nombre, usuarios.apellido, tipo_diagnostico.nombre as tipo_diagnostico, estado_diagnostico.estado')
                    ->join('usuario', 'usuario.usuario_id=diagnostico.paciente_id', 'left')
                    ->join('tipo_diagnostico','tipo_diagnostico.tipo_diagnostico_id=diagnostico.tipo_diagnostico_id','left')
                    ->join('estado_diagnostico','estado_diagnostico.estado_diagnostico_id=diagnostico.estado_id','left')
                    ->where('medico_id', $doctorId)
                    ->findAll();
    } 
}