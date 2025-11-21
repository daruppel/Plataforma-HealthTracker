<?php

if (!function_exists('render_toasts')) {

    function render_toasts()
    {
        $session = session();
        $html = "";

        // Éxito
        if ($session->get('success')) {
            $msg = $session->get('success');
            $html .= "
            <script>
            $(document).Toasts('create', {
                class: 'bg-success',
                title: 'Éxito',
                body: `{$msg}`,
                autohide: true,
                delay: 3000,
                icon: 'fas fa-check'
            });
            </script>";
        }

        // Error simple
        if ($session->get('error')) {
            $msg = $session->get('error');
            $html .= "
            <script>
            $(document).Toasts('create', {
                class: 'bg-danger',
                title: 'Error',
                body: `{$msg}`,
                autohide: true,
                delay: 4000,
                icon: 'fas fa-exclamation-triangle'
            });
            </script>";
        }

        // Errores múltiples (validación)
        if ($session->get('errors')) {
            $errors = $session->get('errors');

            // Armar el cuerpo en HTML
            $body = "";
            foreach ($errors as $e) {
                $body .= "- {$e}<br>";
            }

            $html .= "
            <script>
            $(document).Toasts('create', {
                class: 'bg-warning',
                title: 'Errores de validación',
                body: `{$body}`,
                autohide: false,
                icon: 'fas fa-bug'
            });
            </script>";
        }

        return $html;
    }
}