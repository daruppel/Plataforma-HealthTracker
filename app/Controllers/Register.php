<?php

namespace App\Controllers;

use App\Models\UserModel;

class Register extends BaseController
{
    public function index(): string
    {
        return view('auth/register');
    }

    public function registrar()
    {

        helper(['user']);

        // Validar datos
        if (!$this->validate(reglasUsuario('create'))) {
            log_message('debug', 'Falló la validación: ' . json_encode($this->validator->getErrors()));

            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Si pasa la validación, guardar
        $modelo = new UserModel();

        // Mapeo de nombres del formulario -> nombres de la base
        $datos = [
            'nombre' => $this->request->getPost('name'),
            'apellido' => $this->request->getPost('lastname'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password')
        ];

        if ($modelo->insert($datos)) {
            $userID = $modelo->getInsertID();
            $esMedico = $this->request->getPost('profesional');
            //Voy a setear los ids, pero debería recuperarlos de la BD? medico=2 , paciente=3
            $rolID = $esMedico ? 2 : 3;

            //Insertar a la BD relación usuario/rol
            $db = \Config\Database::connect();
            $db->table('usuario_rol')->insert([
                'usuario_id' => $userID,
                'rol_id' => $rolID
            ]);
            
            if($rolID == 2){ // Si es doctor, crear entrada en tabla medico
                $db->table('medico')->insert([
                    'usuario_id' => $userID,
                        'especialidad_id' => 1 // Especialidad por defecto
                ]);
            }

            return redirect()->to('/login')->with('success', 'Usuario registrado exitosamente');
        }

        return redirect()->back()
            ->withInput()
            ->with('error', 'Error al registrar usuario');
    }

}