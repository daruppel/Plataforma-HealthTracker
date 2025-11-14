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

        log_message('debug', "Intentando login con email: {$email}");

        $user = $userModel->select('usuario.*, usuario_rol.rol_id, rol.descripcion as rol_descripcion')
                          ->join('usuario_rol', 'usuario_rol.usuario_id = usuario.usuario_id', 'left')
                          ->join('rol','usuario_rol.rol_id = rol.rol_id', 'left')
                          ->where('usuario.email', $email)
                          ->first();

        log_message('debug', 'usercreado');
        //Buscar por email
        if(!$user){
            log_message('debug', "No se encontró usuario con email: {$email}");
            return redirect()->back()->with('error', 'Correo incorrecto');
        }

        //Verificación de contraseña
        if(!password_verify($password, $user['password'])){
            log_message('debug', "Contraseña incorrecta para usuario ID {$user['usuario_id']}");
            return redirect()->back()->with('error', 'Contraseña incorrecta');
        }

        //Verificar si el user está activo
        if(isset($user['activo']) && !$user['activo']){
            log_message('debug', "Usuario ID {$user['usuario_id']} está inactivo");
            return redirect()->back()->with('error', 'Usuario eliminado');
        }

         // Si pasa todas las validaciones
        log_message('debug', "Login exitoso para usuario ID {$user['usuario_id']}");

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
   
        /*    // 🧪 Hardcode para pruebas — luego reemplazás por DB
        if ($email === 'admin@demo.com' && $password === '123456') {
            session()->set([
                'user_name'  => 'Admin Demo',
                'user_email' => $email,
                'user_role'  => 'Administrador', 
                'isLoggedIn' => true
            ]);
    
            return redirect()->to('/dashboard');
        }
    */
        return redirect()->back()->with('error', 'Credenciales incorrectas');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
