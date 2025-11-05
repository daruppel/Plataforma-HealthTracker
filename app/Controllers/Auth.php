<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Auth extends Controller
{
    public function login()
    {
        helper(['form', 'url']);
        return view('auth/auth');
    }

    public function doLogin()
    {
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');

        // 🧪 Hardcode para pruebas — luego reemplazás por DB
        if ($email === 'admin@demo.com' && $password === '123456') {
            session()->set([
                'user_email' => $email,
                'isLoggedIn' => true
            ]);

            return redirect()->to('/dashboard');
        }

        return redirect()->back()->with('error', 'Credenciales incorrectas');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }
}
