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

        // Validación en el controlador (incluye password_confirm)
        $reglas = [
            'name' => [
                'rules' => 'required|min_length[3]|max_length[50]',
                'errors' => [
                    'required' => 'El nombre es obligatorio',
                    'min_length' => 'El nombre debe tener al menos 3 caracteres'
                ]
            ],
            'lastname' => [
                'rules' => 'required|min_length[3]|max_length[50]',
                'errors' => [
                    'required' => 'El apellido es obligatorio',
                    'min_length' => 'El apellido debe tener al menos 3 caracteres'
                ]
            ],
            'email' => [
                'rules' => 'required|valid_email|is_unique[usuario.email]',
                'errors' => [
                    'required' => 'El email es obligatorio',
                    'valid_email' => 'Debe ingresar un email válido',
                    'is_unique' => 'Este email ya está registrado'
                ]
            ],
            'password' => [
                'rules' => 'required|min_length[8]',
                'errors' => [
                    'required' => 'La contraseña es obligatoria',
                    'min_length' => 'La contraseña debe tener al menos 8 caracteres'
                ]
            ],
            'passconf' => [
                'rules' => 'required|matches[password]',
                'errors' => [
                    'required' => 'Debe confirmar la contraseña',
                    'matches' => 'Las contraseñas no coinciden'
                ]
            ]
        ];

        // Validar
        if (!$this->validate($reglas)) {
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
            return redirect()->to('/login')->with('success', 'Usuario registrado exitosamente');
        }

        return redirect()->back()
            ->withInput()
            ->with('error', 'Error al registrar usuario');
    }

}