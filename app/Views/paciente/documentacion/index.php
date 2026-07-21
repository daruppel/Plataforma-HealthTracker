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

    <?php if (session()->getFlashdata('errors')): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
          <?php foreach (session()->getFlashdata('errors') as $e): ?>
            <li><?= esc($e) ?></li>
          <?php endforeach; ?>
        </ul>
        <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>
      </div>
    <?php endif; ?>

    <div class="row mb-3">
      <div class="col-12">
        <h4><i class="fas fa-file-medical mr-2 text-primary"></i>Gestión de Documentación Médica</h4>
        <p class="text-muted">Subí y gestioná tus recetas, estudios clínicos y otros documentos médicos de forma segura.</p>
      </div>
    </div>

    <div class="row">
      <!-- Listado de Documentos -->
      <div class="col-lg-8">
        <div class="card card-outline card-primary">
          <div class="card-header">
            <h3 class="card-title"><i class="fas fa-folder-open mr-2"></i>Mis Archivos Guardados</h3>
          </div>
          <div class="card-body p-0">
            <?php if (empty($documentos)): ?>
              <div class="p-4 text-center">
                <i class="fas fa-file-pdf fa-3x text-muted mb-3"></i>
                <h5>No tenés documentos guardados</h5>
                <p class="text-muted mb-0">Utilizá el formulario de la derecha para subir tu primera receta o estudio.</p>
              </div>
            <?php else: ?>
              <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                  <thead class="thead-light">
                    <tr>
                      <th>Tipo</th>
                      <th>Descripción / Nota</th>
                      <th>Fecha Subida</th>
                      <th class="text-right">Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($documentos as $doc): ?>
                      <tr>
                        <td>
                          <?php
                            $badgeClass = 'badge-secondary';
                            if ($doc['tipo_nombre'] === 'Receta') $badgeClass = 'badge-info';
                            elseif ($doc['tipo_nombre'] === 'Estudio') $badgeClass = 'badge-success';
                          ?>
                          <span class="badge <?= $badgeClass ?> p-2" style="font-size: 0.9em;">
                            <i class="fas <?= $doc['tipo_nombre'] === 'Receta' ? 'fa-prescription-bottle-alt' : ($doc['tipo_nombre'] === 'Estudio' ? 'fa-heartbeat' : 'fa-file') ?> mr-1"></i>
                            <?= esc($doc['tipo_nombre']) ?>
                          </span>
                        </td>
                        <td>
                          <span class="font-weight-bold"><?= esc($doc['descripcion'] ?: 'Sin descripción') ?></span>
                        </td>
                        <td>
                          <span class="text-muted"><i class="far fa-calendar-alt mr-1"></i><?= date('d/m/Y H:i', strtotime($doc['created_at'])) ?></span>
                        </td>
                        <td class="text-right">
                          <a href="<?= base_url('paciente/documentacion/download/' . $doc['documento_medico_id']) ?>" class="btn btn-outline-primary btn-sm mr-1" title="Descargar">
                            <i class="fas fa-download mr-1"></i> Descargar
                          </a>
                          <button class="btn btn-outline-danger btn-sm btn-delete-doc" 
                                  data-id="<?= $doc['documento_medico_id'] ?>" 
                                  data-toggle="modal" 
                                  data-target="#modalDeleteConfirm" 
                                  title="Eliminar">
                            <i class="fas fa-trash-alt"></i>
                          </button>
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

      <!-- Formulario de Subida -->
      <div class="col-lg-4">
        <div class="card card-outline card-success">
          <div class="card-header">
            <h3 class="card-title"><i class="fas fa-upload mr-2"></i>Subir Nuevo Documento</h3>
          </div>
          <form action="<?= base_url('paciente/documentacion/store') ?>" method="POST" enctype="multipart/form-data">
            <div class="card-body">
              <div class="form-group">
                <label for="tipo_documento_id">Tipo de Documento <span class="text-danger">*</span></label>
                <select name="tipo_documento_id" id="tipo_documento_id" class="form-control" required>
                  <option value="">-- Seleccionar --</option>
                  <?php foreach ($tipos as $tipo): ?>
                    <option value="<?= $tipo['tipo_documento_id'] ?>"><?= esc($tipo['nombre']) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="form-group">
                <label for="descripcion">Descripción / Comentario</label>
                <textarea name="descripcion" id="descripcion" rows="3" class="form-control" placeholder="Ej: Receta de Amoxicilina / Análisis de sangre de control" required></textarea>
              </div>

              <div class="form-group">
                <label for="archivo">Archivo <span class="text-danger">*</span></label>
                <div class="custom-file">
                  <input type="file" class="custom-file-input" name="archivo" id="archivo" accept=".pdf,.png,.jpg,.jpeg,.doc,.docx" required>
                  <label class="custom-file-label" for="archivo">Elegir archivo...</label>
                </div>
                <small class="form-text text-muted">Formatos permitidos: PDF, PNG, JPG, DOC, DOCX. Máx 5MB.</small>
              </div>
            </div>
            <div class="card-footer">
              <button type="submit" class="btn btn-success btn-block">
                <i class="fas fa-cloud-upload-alt mr-1"></i> Subir Documento
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

  </div>
</section>

<!-- Modal Confirmación de Eliminación -->
<div class="modal fade" id="modalDeleteConfirm" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="formDeleteDoc" action="" method="POST">
        <div class="modal-header">
          <h5 class="modal-title text-danger"><i class="fas fa-exclamation-triangle mr-1"></i> Confirmar Eliminación</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          ¿Estás seguro de que querés eliminar este documento médico? Esta acción no se puede deshacer.
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt mr-1"></i> Eliminar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Mostrar nombre de archivo seleccionado en bootstrap custom-file-input
  const fileInput = document.querySelector('.custom-file-input');
  if (fileInput) {
    fileInput.addEventListener('change', function(e) {
      const fileName = e.target.files[0]?.name || 'Elegir archivo...';
      const label = e.target.nextElementSibling;
      if (label) {
        label.innerText = fileName;
      }
    });
  }

  // Setear acción del formulario del modal de eliminación al hacer clic
  document.querySelectorAll('.btn-delete-doc').forEach(function(btn) {
    btn.addEventListener('click', function() {
      const id = this.dataset.id;
      const form = document.getElementById('formDeleteDoc');
      if (form) {
        form.action = '<?= base_url('paciente/documentacion/delete') ?>/' + id;
      }
    });
  });
});
</script>
