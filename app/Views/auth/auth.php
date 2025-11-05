<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login - HealthTracker</title>
    <link rel="stylesheet" href="<?= base_url('adminlte/dist/css/adminlte.min.css'); ?>">
</head>
<body class="hold-transition login-page">

<div class="login-box">
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <h2>Login</h2>
        </div>
        <div class="card-body">

            <?php if(session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <?= session()->getFlashdata('error') ?>
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
            </form>
        </div>
    </div>
</div>

</body>
</html>
