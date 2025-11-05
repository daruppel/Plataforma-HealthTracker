<div class="row">
  <div class="col-md-12">
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Bienvenido a HealthTracker v2</h3>
      </div>
      <div class="card-body">
        Bienvenido 👋 <?= session()->get('user_email'); ?>
        <br><br>
        <a class="btn btn-danger" href="<?= base_url('logout'); ?>">Cerrar sesión</a>
        <p>Desde este panel vas a poder gestionar los planes de cuidado, pacientes y estadísticas del sistema.</p>
    </div>
    </div>
  </div>
</div>

<div class="card card-primary">
    <div class="card-header"><h3 class="card-title">Dashboard</h3></div>
    <div class="card-body">
        Bienvenido 👋 <?= session()->get('user_email'); ?>
        <br><br>
        <a class="btn btn-danger" href="<?= base_url('logout'); ?>">Cerrar sesión</a>
    </div>
</div>
