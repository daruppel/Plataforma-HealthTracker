<?php

namespace App\Controllers\Admin;

use App\Models\UserModel;
use App\Models\RoleModel;

class Users extends \App\Controllers\BaseController
{
    protected $userModel;
    protected $roleModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->roleModel = new RoleModel();
    }

    public function index()
    {
        $data['users'] = $this->userModel->obtenerUsuariosConRol();
        $data['roles'] = $this->roleModel->findAll();

        return view('templates/header')
            . view('templates/sidebar')
            . view('admin/users/index', $data)
            . view('templates/footer');
    }

    public function create()
    {
        helper('user');
        // Si la solicitud es GET, mostrar el formulario
        if ($this->request->getMethod() === 'GET') {
            return view('admin/users/create');
        }

        // Si la solicitud es POST, procesar el formulario
        if ($this->request->getMethod() === 'POST') {
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
            // Insertar el usuario
            if ($model->insert($datos)) {
                $userID = $model->getInsertID();
                // Asignar rol (seleccionado desde un <select>) TODO (ver la implementacion en el register.php)
                $rolID = $this->request->getPost('role_id');
                if ($rolID) {
                    $db = \Config\Database::connect();
                    $db->table('usuario_rol')->insert([
                        'usuario_id' => $userID,
                        'rol_id'     => $rolID
                    ]);
                }

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
        if (!$this->validate(reglasUsuario('edit', $id))) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Mapeo de nombres del formulario -> campos de la BD
        $data = [
            'nombre'   => $this->request->getPost('name'),
            'apellido' => $this->request->getPost('lastname'),
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

        return redirect()->to(base_url('admin/users'))
            ->with('success', 'Usuario actualizado correctamente');
    }

    public function delete()
    {
        $id = $this->request->getPost('user_id');

        if (!$id) {
            return redirect()->back()->with('error', 'ID de usuario no especificado');
        }

        // Soft delete
        if (!$this->userModel->delete($id)) {
            return redirect()->back()->with('error', 'No se pudo eliminar el usuario');
        }

        return redirect()->to(base_url('admin/users'))
            ->with('success', 'Usuario eliminado correctamente');
    }

    public function changePassword()
    {
        helper(['form', 'user']);

        $id = $this->request->getPost('user_id');
        if (!$id) {
            return redirect()->back()->with('error', 'ID no recibido');
        }
        if (!$this->validate(reglasCambioPass())) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }
        $password = $this->request->getPost('password');

        // Actualizar en DB
        if (!$this->userModel->update($id, ['password' => $password])) {
            return redirect()->back()->with('error', 'No se pudo actualizar la contraseña');
        }

        return redirect()->to(base_url('admin/users'))
            ->with('success', 'Contraseña actualizada correctamente');
    }
}
