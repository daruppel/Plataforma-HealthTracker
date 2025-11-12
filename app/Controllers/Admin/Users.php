<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Users extends \App\Controllers\BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $data['users'] = $this->userModel->obtenerUsuariosConRol();
        return view('templates/header')
            . view('templates/sidebar')
            . view('admin/users/index', $data)
            . view('templates/footer');
    }

    public function create()
    {
        log_message('debug','antes del helper');
        helper('user');
        log_message('debug','despues del helper' . json_encode($this->request->getMethod()));
        // Si la solicitud es GET, mostrar el formulario
        if ($this->request->getMethod() === 'GET') {
            return view('admin/users/create');
        }

        // Si la solicitud es POST, procesar el formulario
        if ($this->request->getMethod() === 'POST') {
            log_message('debug','metodo post');
            // Validar usando las reglas definidas en el helper
            if (!$this->validate(reglasUsuario('create'))) {
                //log_message('debug', 'validacion: '. json_encode($this->validator->getErrors()));
                return redirect()->back()
                    ->withInput()
                    ->with('errors', $this->validator->getErrors());
            }

            $model = new UserModel();

            // Mapeo de campos
            $datos = [
                'nombre'   => $this->request->getPost('name'),
                'apellido' => $this->request->getPost('lastname'),
                'email'    => $this->request->getPost('email'),
                'password' => $this->request->getPost('password')
            ];
            log_message('debug','datos guardados');
            // Insertar el usuario
            if ($model->insert($datos)) {
                $userID = $model->getInsertID();
                            log_message('debug','entra a insertar datos');

                // Asignar rol (seleccionado desde un <select>) TODO (ver la implementacion en el register.php)
                $rolID = $this->request->getPost('role_id');
                
                            log_message('debug','rol con id' . $rolID);
                if ($rolID) {
                            log_message('debug','entra a insertar rol');
                    $db = \Config\Database::connect();
                    $db->table('usuario_rol')->insert([
                        'usuario_id' => $userID,
                        'rol_id'     => $rolID
                    ]);
                }

                //TODO ver donde redirigir previo al registro exitoso
                return redirect()->to('/admin/users')
                    ->with('success', 'Usuario creado exitosamente');
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'No se pudo crear el usuario');
        }
    }
    
   public function update()
    {
        helper(['user']);
        $id = $this->request->getPost('user_id');

        if (!$id) {
            return redirect()->back()->with('error', 'ID de usuario no especificado');
        }

        // Validación usando helper
        if (!$this->validate(reglasUsuario('edit'))) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Mapeo de nombres del formulario -> campos de la BD
        $data = [
            'nombre'   => $this->request->getPost('name'),
            'apellido' => $this->request->getPost('surname'),
            'email'    => $this->request->getPost('email'),
        ];

        // Actualizar usuario
        if (!$this->userModel->update($id, $data)) {
            return redirect()->back()
                ->with('error', 'No se pudo actualizar el usuario');
        }

        // Actualizar rol en tabla relación
        $rolId = $this->request->getPost('role_id');
        $db = \Config\Database::connect();

        $db->table('usuario_rol')
           ->where('usuario_id', $id)
           ->set(['rol_id' => $rolId])
           ->update();

        return redirect()->to(base_url('admin/usuarios'))
                         ->with('success', 'Usuario actualizado correctamente');
    }

}