<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>HealthTracker</title>

    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="<?= base_url('adminlte/dist/css/adminlte.min.css'); ?>">

    <style>
        .hero-section {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }
        .hero-content {
            max-width: 600px;
        }
        .hero-title {
            font-size: 42px;
            font-weight: bold;
            color: #003a70;
        }
        .hero-description {
            font-size: 18px;
            color: #3a3a3a;
        }
        .hero-image {
            max-width: 500px;
            border-radius: 50px;
        }
        .login-btn {
            position: absolute;
            top: 20px;
            right: 20px;
        }
    </style>
</head>

<body class="hold-transition">

<!-- Botón Login arriba -->
<a href="<?= base_url('login'); ?>" class="btn btn-primary login-btn">Iniciar sesión</a>

<div class="hero-section container">
    <div class="row align-items-center">
        
        <!-- Texto -->
        <div class="col-md-6 hero-content">
            <h1 class="hero-title">HealthTracker</h1>
            <p class="hero-description mt-3">
                Plataforma para la gestión eficiente de planes de cuidado, 
                seguimiento clínico y monitoreo del bienestar de los pacientes.
            </p>
            <p class="mt-2 text-muted">
                Registro, control, comunicación y trazabilidad en un solo sistema.
            </p>

        </div>

        <!-- Imagen -->
        <div class="col-md-6 text-center">
            <img src="..\public\assets\img\home.png" 
                 alt="Salud"
                 class="img-fluid hero-image">
        </div>
    </div>
</div>

</body>
</html>
