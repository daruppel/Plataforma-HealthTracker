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
        $data['users'] = $this->userModel->findAll();
        return view('templates/header')
            . view('templates/sidebar')
            . view('admin/users/index', $data)
            . view('templates/footer');
    }

    public function create()
    {
        helper('usuario');

        // Si la solicitud es GET, mostrar el formulario
        if ($this->request->getMethod() === 'get') {
            return view('admin/users/create');
        }

        // Si la solicitud es POST, procesar el formulario
        if ($this->request->getMethod() === 'post') {
            // Validar usando las reglas definidas en el helper
            if (!$this->validate(reglasUsuario('create'))) {
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
                $rolID = $this->request->getPost('rol_id');
                if ($rolID) {
                    $db = \Config\Database::connect();
                    $db->table('usuario_rol')->insert([
                        'usuario_id' => $userID,
                        'rol_id'     => $rolID
                    ]);
                }

                //TODO ver donde redirigir previo al registro exitoso
                return redirect()->to('/users')
                    ->with('success', 'Usuario creado exitosamente');
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'No se pudo crear el usuario');
        }
    }
    
}