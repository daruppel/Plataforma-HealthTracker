<section class="content">
  <div class="container-fluid">

    <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success alert-dismissible">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
      </div>
    <?php endif; ?>

    <!-- Encabezado paciente -->
    <div class="d-flex align-items-center mb-3">
      <a href="<?= base_url('medical_staff/patients') ?>" class="btn btn-sm btn-outline-secondary mr-3">
        <i class="fas fa-arrow-left"></i>
      </a>
      <div>
        <h4 class="mb-0">
          <i class="fas fa-file-medical-alt mr-2"></i>Historial médico: 
          <strong><?= esc($paciente['apellido'] . ', ' . $paciente['nombre']) ?></strong>
        </h4>
        <small class="text-muted"><?= esc($paciente['email']) ?></small>
      </div>
    </div>

    <?php if (empty($diagnosticos)): ?>
      <div class="callout callout-info">
        <p class="mb-0">Este paciente no tiene diagnósticos registrados.</p>
      </div>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <thead class="thead-light">
            <tr>
              <th>Fecha</th>
              <th>Tipo de Diagnóstico</th>
              <th>Descripción</th>
              <th>Estado</th>
              <th>Plan de Cuidado</th>
              <th class="text-center">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($diagnosticos as $d): ?>
              <?php
                $estado = $d['estado'] ?? 'pendiente';
                switch ($estado) {
                  case 'Pendiente':  $badge = 'badge-warning';  $label = 'Pendiente'; break;
                  case 'en_proceso': $badge = 'badge-primary';  $label = 'En proceso'; break;
                  case 'finalizado': $badge = 'badge-success';  $label = 'Finalizado'; break;
                  case 'cancelado':  $badge = 'badge-danger';   $label = 'Cancelado'; break;
                  default:           $badge = 'badge-secondary'; $label = ucfirst($estado);
                }
              ?>
              <tr>
                <td><?= date('d/m/Y', strtotime($d['fecha'])) ?></td>
                <td><span class="badge badge-info"><?= esc($d['tipo_diagnostico']) ?></span></td>
                <td><?= esc($d['descripcion']) ?></td>
                <td><span class="badge <?= $badge ?>"><?= $label ?></span></td>
                <td>
                  <?php if (!empty($d['fec_inicio'])): ?>
                    <small>
                      <?= date('d/m/Y', strtotime($d['fec_inicio'])) ?>
                      &rarr;
                      <?= date('d/m/Y', strtotime($d['fec_fin'])) ?>
                    </small>
                    <?php if (!empty($d['comentario_paciente'])): ?>
                      <br><small class="text-muted" title="<?= esc($d['comentario_paciente']) ?>">
                        <?= esc(mb_strimwidth($d['comentario_paciente'], 0, 50, '...')) ?>
                      </small>
                    <?php endif; ?>
                  <?php else: ?>
                    <span class="text-muted">—</span>
                  <?php endif; ?>
                </td>
                <td class="text-center">
                  <?php if ($estado === 'Pendiente'): ?>
                    <a href="<?= base_url('medical_staff/care-plan/create/' . $d['diagnostico_id']) ?>"
                       class="btn btn-sm btn-outline-success" title="Crear plan de cuidado">
                      <i class="fas fa-notes-medical"></i>
                    </a>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>

  </div>
</section>
