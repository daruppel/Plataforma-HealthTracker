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
        if (session()->get('rol') !== 'Administrador') {
            return redirect()->to('/dashboard');
        }

        $roles = $this->db->table('rol')->get()->getResultArray();

        return view('admin/users/create', [
            'roles' => $roles,
            'validation' => \Config\Services::validation()
        ]);
    }

    // Nueva función para procesar el POST
    public function store()
    {
        if (session()->get('rol') !== 'Administrador') {
            return redirect()->to('/acceso-denegado');
        }

        $data = $this->request->getPost([
            'nombre', 'apellido', 'email', 'password', 'rol_id'
        ]);

        // Validación
        $rules = [
            'nombre'   => 'required|min_length[3]',
            'apellido' => 'required|min_length[3]',
            'email'    => 'required|valid_email|is_unique[usuario.email]',
            'password' => 'required|min_length[8]',
            'rol_id'   => 'required|integer'
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Insertar usuario
        try {
            $userId = $this->userModel->insert([
                'nombre'   => $data['nombre'],
                'apellido' => $data['apellido'],
                'email'    => $data['email'],
                'password' => password_hash($data['password'], PASSWORD_DEFAULT),
                'activo'   => 1
            ]);

            // Asignar rol
            $this->db->table('usuario_rol')->insert([
                'usuario_id' => $userId,
                'rol_id'     => $data['rol_id']
            ]);

            return redirect()->to(base_url('users'))->with('success', 'Usuario creado correctamente.');

        } catch (DataException $e) {
            log_message('error', 'Error al crear usuario: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocurrió un error al crear el usuario.');
        }
    }
}