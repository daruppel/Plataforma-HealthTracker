<section class="content">
  <div class="container-fluid">

    <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
      </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
      </div>
    <?php endif; ?>

    <?php if (isset($errors)): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
          <?php foreach ($errors as $e): ?><li><?= esc($e) ?></li><?php endforeach; ?>
        </ul>
        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
      </div>
    <?php endif; ?>

    <div class="row mb-3 align-items-center">
      <div class="col">
        <h4 class="mb-0">
          <i class="fas fa-check-double mr-2"></i>Validación de Cumplimientos
          <?php if (!empty($pendientes)): ?>
            <span class="badge badge-warning ml-2"><?= count($pendientes) ?> pendiente<?= count($pendientes) !== 1 ? 's' : '' ?></span>
          <?php endif; ?>
        </h4>
      </div>
    </div>

    <?php if (empty($pendientes)): ?>
      <div class="callout callout-success">
        <h5><i class="fas fa-check-circle mr-1"></i>Todo al día</h5>
        <p>No hay cumplimientos pendientes de validación.</p>
      </div>
    <?php else: ?>
      <div class="card">
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover mb-0">
              <thead class="thead-light">
                <tr>
                  <th>Paciente</th>
                  <th>Meta</th>
                  <th>Fecha</th>
                  <th>Duración</th>
                  <th>Comentario paciente</th>
                  <th class="text-center">Acción</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($pendientes as $c): ?>
                  <tr>
                    <td><?= esc($c['paciente_apellido'] . ', ' . $c['paciente_nombre']) ?></td>
                    <td>
                      <small class="text-muted d-block"><?= esc($c['tipo_nombre']) ?></small>
                      <?= esc($c['meta_descripcion']) ?>
                    </td>
                    <td><?= date('d/m/Y', strtotime($c['fecha'])) ?></td>
                    <td><?= (int)$c['duracion_minutos'] ?> min</td>
                    <td><?= esc($c['comentario'] ?? '—') ?></td>
                    <td class="text-center">
                      <button
                        class="btn btn-sm btn-primary btn-validar"
                        data-id="<?= $c['cumplimiento_meta_id'] ?>"
                        data-toggle="modal"
                        data-target="#modalValidar"
                      >
                        <i class="fas fa-check mr-1"></i>Validar
                      </button>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    <?php endif; ?>

  </div>
</section>

<!-- Modal de validación — un único modal, JS inyecta el cumplimiento_id -->
<div class="modal fade" id="modalValidar" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="<?= base_url('medical_staff/validacion/validar') ?>" method="POST">
        <input type="hidden" name="cumplimiento_id" id="hiddenCumplimientoId">

        <div class="modal-header">
          <h5 class="modal-title"><i class="fas fa-check-double mr-1"></i>Validar cumplimiento</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>

        <div class="modal-body">

          <div class="form-group">
            <label class="d-block">Resultado <span class="text-danger">*</span></label>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="accion" id="accionAprobado" value="validado" required>
              <label class="form-check-label text-success" for="accionAprobado">
                <i class="fas fa-check-circle mr-1"></i>Aprobar
              </label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="accion" id="accionRechazado" value="rechazado">
              <label class="form-check-label text-danger" for="accionRechazado">
                <i class="fas fa-times-circle mr-1"></i>Rechazar
              </label>
            </div>
          </div>

          <div class="form-group" id="puntuacionGroup" style="display:none;">
            <label>Puntuación <span class="text-danger">*</span></label>
            <div class="star-widget">
              <input type="radio" name="puntuacion" id="star5" value="5"><label for="star5"><i class="fas fa-star"></i></label>
              <input type="radio" name="puntuacion" id="star4" value="4"><label for="star4"><i class="fas fa-star"></i></label>
              <input type="radio" name="puntuacion" id="star3" value="3"><label for="star3"><i class="fas fa-star"></i></label>
              <input type="radio" name="puntuacion" id="star2" value="2"><label for="star2"><i class="fas fa-star"></i></label>
              <input type="radio" name="puntuacion" id="star1" value="1"><label for="star1"><i class="fas fa-star"></i></label>
            </div>
          </div>

          <div class="form-group">
            <label for="comentario_medico">Comentario (opcional)</label>
            <textarea name="comentario_medico" id="comentario_medico" rows="3"
              class="form-control" placeholder="Observaciones, recomendaciones..."></textarea>
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-save mr-1"></i>Guardar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<style>
/* Star rating — CSS puro con FontAwesome, sin librería */
.star-widget { display: flex; flex-direction: row-reverse; width: fit-content; gap: 4px; }
.star-widget input { display: none; }
.star-widget label { font-size: 1.6rem; color: #ccc; cursor: pointer; transition: color .15s; }
.star-widget input:checked ~ label,
.star-widget label:hover,
.star-widget label:hover ~ label { color: #f39c12; }
</style>

<script>
// Inyecta el ID del cumplimiento al abrir el modal
document.querySelectorAll('.btn-validar').forEach(function(btn) {
  btn.addEventListener('click', function() {
    document.getElementById('hiddenCumplimientoId').value = this.dataset.id;
    // Resetea el modal al abrir
    document.querySelectorAll('[name="accion"]').forEach(r => r.checked = false);
    document.querySelectorAll('[name="puntuacion"]').forEach(r => r.checked = false);
    document.getElementById('comentario_medico').value = '';
    document.getElementById('puntuacionGroup').style.display = 'none';
  });
});

// Muestra/oculta estrellas según acción seleccionada
document.querySelectorAll('[name="accion"]').forEach(function(radio) {
  radio.addEventListener('change', function() {
    document.getElementById('puntuacionGroup').style.display =
      this.value === 'validado' ? 'block' : 'none';
    if (this.value === 'rechazado') {
      document.querySelectorAll('[name="puntuacion"]').forEach(r => r.checked = false);
    }
  });
});
</script>
