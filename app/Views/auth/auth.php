<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>HealthTracker</title>
    <link rel="icon" href="<?= base_url('/assets/img/icono.png'); ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?= base_url('adminlte/dist/css/adminlte.min.css'); ?>">
</head>
<body class="hold-transition login-page">

<div class="login-box">
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <h2>Login</h2>
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

            <form action="<?= base_url('login'); ?>" method="post">
                <div class="input-group mb-3">
                    <input name="email" type="email" class="form-control" placeholder="Email">
                </div>

                <div class="input-group mb-3">
                    <input name="password" type="password" class="form-control" placeholder="Contraseña">
                </div>

                <button class="btn btn-primary btn-block">Ingresar</button>
                <p class="mt-3 text-center">
                    ¿No tenés cuenta? <a href="<?= base_url('register'); ?>">Registrate</a>
                </p>
            </form>
        </div>
    </div>
</div>

</body>
</html>
