<section class="content">
  <div class="container-fluid">
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-users mr-2"></i>Mis Pacientes</h3>
      </div>
      <div class="card-body">

        <?php if (session()->getFlashdata('error')): ?>
          <div class="alert alert-danger alert-dismissible">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
          </div>
        <?php endif; ?>

        <div class="mb-3">
          <input type="text" id="buscador" class="form-control" placeholder="Buscar por nombre o apellido...">
        </div>

        <?php if (empty($patients)): ?>
          <div class="callout callout-info">
            <p class="mb-0">No tiene pacientes con diagnósticos registrados aún.</p>
          </div>
        <?php else: ?>
          <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped" id="tablaPacientes">
              <thead class="thead-light">
                <tr>
                  <th>Apellido, Nombre</th>
                  <th>Email</th>
                  <th class="text-center">Diagnósticos</th>
                  <th>Paciente desde</th>
                  <th class="text-center">Acciones</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($patients as $p): ?>
                  <tr class="fila-paciente">
                    <td>
                      <strong class="nombre-paciente"><?= esc($p['apellido'] . ', ' . $p['nombre']) ?></strong>
                    </td>
                    <td><?= esc($p['email']) ?></td>
                    <td class="text-center">
                      <span class="badge badge-secondary"><?= (int)$p['total_diagnosticos'] ?></span>
                    </td>
                    <td><?= date('d/m/Y', strtotime($p['created_at'])) ?></td>
                    <td class="text-center">
                      <a href="<?= base_url('medical_staff/patients/historial/' . $p['usuario_id']) ?>"
                         class="btn btn-sm btn-outline-primary" title="Ver historial médico">
                        <i class="fas fa-file-medical-alt mr-1"></i>Historial
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>

      </div>
    </div>
  </div>
</section>

<script>
document.getElementById('buscador').addEventListener('input', function () {
  const filtro = this.value.toLowerCase();
  document.querySelectorAll('.fila-paciente').forEach(function (fila) {
    const nombre = fila.querySelector('.nombre-paciente').textContent.toLowerCase();
    fila.style.display = nombre.includes(filtro) ? '' : 'none';
  });
});
</script>
