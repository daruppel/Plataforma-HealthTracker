<?php

namespace App\Controllers;

use App\Models\UserModel;

class Profile extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function update()
    {
        if ($this->request->getMethod() === 'POST') {
            helper(['user']);

            $userId = session()->get('user_id');

            if (!$userId) {
                return redirect()->to('/login');
            }

            if (!$this->validate(reglasUsuario('edit', $userId))) {
                return redirect()
                    ->back()
                    ->with('errors', $this->validator->getErrors())
                    ->with('errors_update', 'Se encontraron errores en el formulario de actualización')
                    ->withInput();
            }

            $data = [
                'nombre'   => $this->request->getPost('name'),
                'apellido' => $this->request->getPost('lastname'),
                'email'    => $this->request->getPost('email'),
                'password' => $this->request->getPost('password'),
            ];

            if (!$this->userModel->update($userId, $data)) {
                return redirect()
                    ->back()
                    ->with('errors', $this->userModel->errors())
                    ->with('errors_update', 'No se pudo actualizar el perfil')
                    ->withInput();
            }

            $updatedUser = $this->userModel->find($userId);

            session()->set([
                'name'     => $updatedUser['nombre'],
                'lastname' => $updatedUser['apellido'],
                'email'    => $updatedUser['email'],
            ]);

            return redirect()
                ->back()
                ->with('success', 'Perfil actualizado correctamente');
        } else {
            return redirect()->back();
        }
    }
}
