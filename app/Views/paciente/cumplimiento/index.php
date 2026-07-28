<section class="content">
  <div class="container-fluid">

    <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>
      </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>
      </div>
    <?php endif; ?>

    <?php if (isset($errors)): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
          <?php foreach ($errors as $e): ?>
            <li><?= esc($e) ?></li>
          <?php endforeach; ?>
        </ul>
        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>
      </div>
    <?php endif; ?>

    <div class="row mb-3">
      <div class="col-12">
        <h4><i class="fas fa-clipboard-check mr-2"></i>Mi Plan de Cuidado</h4>
      </div>
    </div>

    <?php if (empty($metas)): ?>
      <div class="callout callout-info">
        <h5><i class="fas fa-info-circle mr-1"></i>Sin plan activo</h5>
        <p>No tenés un plan de cuidado activo en este momento.</p>
      </div>
    <?php else: ?>
      <div class="row">
        <?php foreach ($metas as $meta): ?>
          <?php $isCumplida = !empty($meta['registrado']) || (isset($meta['meta_cumplida']) && ($meta['meta_cumplida'] == 1 || $meta['meta_cumplida'] == '1' || $meta['meta_cumplida'] == 0x31)); ?>
          <div class="col-md-6 col-lg-4">
            <div class="card card-outline <?= $isCumplida ? 'card-success' : 'card-primary' ?>">
              <div class="card-header">
                <h3 class="card-title"><?= esc($meta['tipo_nombre']) ?></h3>
                <div class="card-tools">
                  <?php if ($isCumplida): ?>
                    <span class="badge badge-success">Cumplida: Sí</span>
                  <?php else: ?>
                    <span class="badge badge-secondary">Cumplida: No</span>
                  <?php endif; ?>
                </div>
              </div>
              <div class="card-body">
                <p class="text-muted mb-3"><?= esc($meta['descripcion']) ?></p>
                <?php if (!$isCumplida): ?>
                  <button
                    class="btn btn-primary btn-sm btn-registrar"
                    data-meta-id="<?= $meta['metas_plan_cuidado_id'] ?>"
                    data-toggle="modal"
                    data-target="#modalRegistrar"
                  >
                    <i class="fas fa-plus mr-1"></i>Registrar
                  </button>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

  </div>
</section>

<!-- Modal único compartido — JS inyecta el metas_plan_cuidado_id al abrirlo -->
<div class="modal fade" id="modalRegistrar" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="<?= base_url('paciente/cumplimiento/store') ?>" method="POST">
        <input type="hidden" name="metas_plan_cuidado_id" id="hiddenMetaId">
        <div class="modal-header">
          <h5 class="modal-title"><i class="fas fa-clipboard-check mr-1"></i>Registrar cumplimiento</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="fecha">Fecha <span class="text-danger">*</span></label>
            <input type="date" name="fecha" id="fecha" class="form-control"
              value="<?= date('Y-m-d') ?>" max="<?= date('Y-m-d') ?>" required>
          </div>
          <div class="form-group">
            <label for="duracion_minutos">Duración (minutos) <span class="text-danger">*</span></label>
            <input type="number" name="duracion_minutos" id="duracion_minutos"
              class="form-control" min="1" placeholder="Ej: 30" required>
          </div>
          <div class="form-group">
            <label for="comentario">Comentario</label>
            <textarea name="comentario" id="comentario" rows="3"
              class="form-control" placeholder="Opcional: cómo te sentiste, dificultades, etc."></textarea>
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

<script>
// Pasa el metas_plan_cuidado_id al modal cuando se abre
document.querySelectorAll('.btn-registrar').forEach(function(btn) {
  btn.addEventListener('click', function() {
    document.getElementById('hiddenMetaId').value = this.dataset.metaId;
  });
});
</script>
