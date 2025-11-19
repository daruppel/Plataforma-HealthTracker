<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MedicalEntityModel;

class MedicalEntities extends BaseController
{
    protected $entityModel;

    public function __construct()
    {
        $this->entityModel = new MedicalEntityModel();
    }

    public function index()
    {
        $data['entities'] = $this->entityModel->findAll();
        return view('templates/header')
            . view('templates/sidebar')
            . view('admin/medical_entities/index', $data)
            . view('templates/footer');
    }

    public function create()
    {
        //helper('user');
        // Si la solicitud es GET, mostrar el formulario
        if ($this->request->getMethod() === 'GET') {
            return view('admin/medical_entities/create');
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
}