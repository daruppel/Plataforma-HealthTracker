<?php

/**
 * Devuelve las reglas de validación comunes para usuarios.
 * 
 * @param string $modo  'create' o 'edit'
 * @param bool   $requerirPassConfirm  Si se requiere confirmar la contraseña
 */
function reglasUsuario($modo = 'create', $requerirPassConfirm = true)
{
    $reglas = [
        'name' => [
            'rules' => 'required|min_length[3]|max_length[50]',
            'errors' => [
                'required' => 'El nombre es obligatorio',
                'min_length' => 'El nombre debe tener al menos 3 caracteres',
                'max_length' => 'El nombre no puede exceder 50 caracteres',
            ],
        ],
        'lastname' => [
            'rules' => 'required|min_length[3]|max_length[50]',
            'errors' => [
                'required' => 'El apellido es obligatorio',
                'min_length' => 'El apellido debe tener al menos 3 caracteres',
                'max_length' => 'El apellido no puede exceder 50 caracteres',
            ],
        ],
        'email' => [
            'rules' => $modo === 'edit'
                ? 'required|valid_email'
                : 'required|valid_email|is_unique[usuario.email]',
            'errors' => [
                'required' => 'El email es obligatorio',
                'valid_email' => 'Debe ingresar un email válido',
                'is_unique' => 'Este email ya está registrado',
            ],
        ],
    ];

    // Validación de contraseña
    if ($modo === 'create') {
        $reglas['password'] = [
            'rules' => 'required|min_length[8]',
            'errors' => [
                'required' => 'La contraseña es obligatoria',
                'min_length' => 'La contraseña debe tener al menos 8 caracteres',
            ],
        ];
    } else {
        $reglas['password'] = [
            'rules' => 'permit_empty|min_length[8]',
            'errors' => [
                'min_length' => 'La contraseña debe tener al menos 8 caracteres',
            ],
        ];
    }

    // Confirmación de contraseña
    if ($requerirPassConfirm) {
        $reglas['passconf'] = [
            'rules' => $modo === 'create'
                ? 'required|matches[password]'
                : 'permit_empty|matches[password]',
            'errors' => [
                'required' => 'Debe confirmar la contraseña',
                'matches'  => 'Las contraseñas no coinciden',
            ],
        ];
    }

    return $reglas;
}
