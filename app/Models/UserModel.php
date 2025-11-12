<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    // Datos de usuario
    protected $table = 'usuario';
    protected $primaryKey = 'usuario_id';
    protected $returnType = 'array'; // 'array', 'object' o nombre de clase
    //Activa el borrado logico cuando se utiliza '$modelo->delete($id);'
    protected $useSoftDeletes = true;
    
    // Campos que se pueden insertar/actualizar
    protected $allowedFields = [
        'nombre',
        'apellido',
        'email',
        'password',
        'activo'
    ];

    // Timestamps automáticos
    protected $useTimestamps = true;
    protected $createdField = 'created_at';  // Campo para fecha de creación
    protected $updatedField = 'updated_at';  // Campo para fecha de actualización
    protected $deletedField = 'deleted_at';  // Campo para fecha de eliminación
    
    // Validaciones del modelo
    protected $validationRules = [
        'nombre' => 'required|min_length[3]|max_length[50]',
        'apellido' => 'required|min_length[3]|max_length[50]',
        'email' => 'required|valid_email|max_length[100]',
        'password' => 'required|min_length[8]'
    ];
    
    protected $validationMessages = [
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
    ];
    
    // Validación solo en insert (no en update)
    protected $skipValidation = false;
    
    // Callbacks para antes y después de operaciones
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];
    
    //Hashea la contraseña antes de guardarla
    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password']) && !empty($data['data']['password'])) {
            // Solo hashear si no está ya hasheada
            if (strpos($data['data']['password'], '$2y$') !== 0) {
                $data['data']['password'] = password_hash(
                    $data['data']['password'], PASSWORD_DEFAULT
                );
            }
        } else {
            // Si viene vacío, no actualizar el campo password
            unset($data['data']['password']);
        }
        return $data;
    }
    
    //Buscar usuario por email
    public function buscarPorEmail($email)
    {
        return $this->where('email', $email)->first();
    }
    
    //Verificar si el email ya existe
    public function emailExiste($email, $exceptoId = null)
    {
        $builder = $this->where('email', $email);
        
        if ($exceptoId) {
            $builder->where('usuario_id !=', $exceptoId);
        }
        
        return $builder->countAllResults() > 0;
    }
    
    //Obtener usuarios activos
    public function obtenerActivos()
    {
        return $this->where('activo', 1)->findAll();
    }
    
    //Cambiar estado del usuario
    public function cambiarEstado($id, $activo)
    {
        return $this->update($id, ['activo' => $activo]);
    }

    public function obtenerUsuariosConRol(){
        return $this->select('usuario.*, rol.descripcion as rol_desc, rol.nombre as rol_nombre, rol.rol_id')
                    ->join('usuario_rol', 'usuario_rol.usuario_id=usuario.usuario_id', 'left')
                    ->join('rol','usuario_rol.rol_id=rol.rol_id','left')
                    ->findAll();
    }
}