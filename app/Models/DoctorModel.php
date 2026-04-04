<?php

namespace App\Models;

use CodeIgniter\Model;

class DoctorModel extends Model
{
    // Datos de doctor
    protected $table = 'medico';
    protected $primaryKey = 'medico_id';
    protected $returnType = 'array'; // 'array', 'object' o nombre de clase
    //Activa el borrado logico cuando se utiliza '$modelo->delete($id);'
    protected $useSoftDeletes = true;
    
    // Campos que se pueden insertar/actualizar
    protected $allowedFields = [
        'usuario_id',
        'especialidad_id',
        'matricula',
        'biografia'
    ];

    // Timestamps automáticos
    protected $useTimestamps = true;
    protected $createdField = 'created_at';  // Campo para fecha de creación
    protected $updatedField = 'updated_at';  // Campo para fecha de actualización
    protected $deletedField = 'deleted_at';  // Campo para fecha de eliminación
    
    // Validaciones del modelo
    protected $validationRules = [
        'usuario_id' => 'required',
        'especialidad_id' => 'required',
        'matricula' => 'required',
        'biografia' => 'required',
        'especialidad_id' => 'required|integer'
    ];
    /**TODO: Faltan poner los mensajes de validacion */
    protected $validationMessages = []

    // Validación solo en insert (no en update)
    protected $skipValidation = false;
}