<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        helper(['form', 'url']);
        return view('auth/auth');
    }

    public function doLogin()
    {
        $session = session();
        $userModel = new UserModel();

        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');

        $user = $userModel->getUsersWithRoleByEmail($email);

        //Buscar por email
        if(!$user){
            return redirect()->back()->with('error', 'Correo incorrecto');
        }

        //Verificación de contraseña
        if(!password_verify($password, $user['password'])){
            return redirect()->back()->with('error', 'Contraseña incorrecta');
        }

        //Verificar si el user está activo
        if(isset($user['activo']) && !$user['activo']){
            return redirect()->back()->with('error', 'Usuario eliminado');
        }

        // Si pasa todas las validaciones
        $sessionData = [
            'user_id' => $user['usuario_id'],
            'name' => $user['nombre'],
            'lastname' => $user['apellido'],
            'email' => $user['email'],
            'isLoggedIn' => true,
            'role_id' => $user['rol_id'],
            'role_desc' => $user['rol_descripcion']
        ];

        $session->set($sessionData);
        return redirect()->to('/dashboard');

    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
