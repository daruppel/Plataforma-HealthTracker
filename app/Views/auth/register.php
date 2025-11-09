<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HealthTracker</title>
    <link rel="icon" href="<?= base_url('/assets/img/icono.png'); ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?= base_url('adminlte/dist/css/adminlte.min.css'); ?>">
</head>
<body class="hold-transition login-page">

<div class="register-box">
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <h3><b>Crear cuenta</b></h3>
        </div>

        <div class="card-body">
            
            <!-- Mostrar errores de validación -->
            <?php if (session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form  action="<?= base_url('/register/registrar') ?>" method="post">

                <div class="input-group mb-3">
                    <input type="text" name="name" class="form-control" placeholder="Nombre" required>
                </div>

                <div class="input-group mb-3">
                    <input type="text" name="lastname" class="form-control" placeholder="Apellido" required>
                </div>

                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Correo electrónico" required>
                </div>

                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
                </div>

                <div class="input-group mb-3">
                    <input type="password" name="passconf" class="form-control" placeholder="Repetir contraseña" required>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Registrarme</button>

            </form>

            <p class="mt-3 text-center">
                ¿Ya tenés cuenta? <a href="<?= base_url('login'); ?>">Iniciar sesión</a>
            </p>

        </div>
    </div>
</div>

</body>
</html>