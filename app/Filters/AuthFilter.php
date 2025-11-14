<?php namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Verifica si el usuario está logueado
        if (!$session->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }

        // Si el filtro tiene argumentos, son los roles permitidos
        if ($arguments) {
            $rolUsuario = $session->get('user_role'); // Ej: 'Administrador', 'Profesional', 'Paciente'
            $rolesPermitidos = $arguments;

            if (!in_array($rolUsuario, $rolesPermitidos)) {
                return redirect()->to(base_url('sin-permiso'))
                                 ->with('error', 'No tienes permisos para acceder a esta sección.');
            }
        }

        // Si pasa todo, continúa
        return;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No se hace nada después
    }
}