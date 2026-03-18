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
        'medico_id',
        'fecha',
        'estado_id'
    ];

    // Timestamps automáticos
    protected $useTimestamps = true;
    protected $createdField = 'created_at';  // Campo para fecha de creación
    protected $updatedField = 'updated_at';  // Campo para fecha de actualización
    protected $deletedField = 'deleted_at';  // Campo para fecha de eliminación
    
    // Validaciones del modelo
   /* protected $validationRules = [
        'nombre' => 'required|min_length[3]|max_length[50]',
        'apellido' => 'required|min_length[3]|max_length[50]',
        'email' => 'required|valid_email|max_length[100]',
        'password' => 'required|min_length[8]'
    ]; */
    
   /* protected $validationMessages = [
        'nombre' => [
            'required' => 'El nombre es obligatorio',
            'min_length' => 'El nombre debe tener al menos 3 caracteres',
            'max_length' => 'El nombre no puede exceder 50 caracteres'
        ],
        'email' => [
            'required' => 'El email es obligatorio',
            'valid_email' => 'Debe ingresar un email válido',
            'max_length' => 'El email no puede exceder 100 caracteres'
        ],
        'password' => [
            'required' => 'La contraseña es obligatoria',
            'min_length' => 'La contraseña debe tener al menos 8 caracteres'
        ]
    ]; */
    
    // Validación solo en insert (no en update)
    protected $skipValidation = false;

   /* public function obtenerUsuariosConRol(){
        return $this->select('usuario.*, rol.descripcion as rol_desc, rol.nombre as rol_nombre, rol.rol_id')
                    ->join('usuario_rol', 'usuario_rol.usuario_id=usuario.usuario_id', 'left')
                    ->join('rol','usuario_rol.rol_id=rol.rol_id','left')
                    ->findAll();
    } */
}