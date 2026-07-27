<section class="content">
  <div class="container-fluid">
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Gestión de Planes de Cuidado Estandarizados</h3>
        <a href="<?= base_url('admin/care-plan-templates/create') ?>" class="btn btn-success float-right">
          <i class="fas fa-plus"></i> Nuevo
        </a>
      </div>
      <div class="card-body">

        <?php if (session()->getFlashdata('success')): ?>
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        <?php endif; ?>

        <table class="table table-bordered table-striped datatable">
          <thead>
            <tr>
              <th>Nombre</th>
              <th>Tipo de Diagnóstico</th>
              <th class="text-center"># Metas</th>
              <th class="text-center">Activo</th>
              <th class="text-center">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($templates as $tpl): ?>
            <tr>
              <td><?= esc($tpl['nombre']) ?></td>
              <td><?= esc($tpl['tipo_diagnostico_nombre'] ?? '—') ?></td>
              <td class="text-center"><?= (int) $tpl['cantidad_tareas'] ?></td>
              <td class="text-center">
                <?php if ($tpl['activo']): ?>
                  <span class="badge badge-success">Sí</span>
                <?php else: ?>
                  <span class="badge badge-secondary">No</span>
                <?php endif; ?>
              </td>
              <td class="text-center">
                <a href="<?= base_url('admin/care-plan-templates/edit/' . $tpl['plan_cuidado_estandar_id']) ?>"
                   class="btn btn-sm btn-warning">
                  <i class="fas fa-pen"></i>
                </a>
                <a href="#" class="btn btn-sm btn-danger btn-delete-tpl"
                   data-id="<?= $tpl['plan_cuidado_estandar_id'] ?>"
                   data-nombre="<?= esc($tpl['nombre']) ?>">
                  <i class="fas fa-trash"></i>
                </a>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Modal Confirmar Eliminación -->
  <div class="modal fade" id="modalDeleteTemplate" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Eliminar Plantilla</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          ¿Está seguro que desea eliminar la plantilla <strong id="deleteTplName"></strong>?
          <br><small class="text-muted">Esta acción es reversible (borrado lógico).</small>
        </div>
        <div class="modal-footer">
          <form id="formDeleteTemplate" action="" method="POST">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-danger">Eliminar</button>
          </form>
        </div>
      </div>
    </div>
  </div>

</section>

<script>
$(document).ready(function() {
    $('.btn-delete-tpl').on('click', function(e) {
        e.preventDefault();
        const id     = $(this).data('id');
        const nombre = $(this).data('nombre');

        $('#deleteTplName').text(nombre);
        $('#formDeleteTemplate').attr('action', '<?= base_url('admin/care-plan-templates/delete/') ?>' + id);

        $('#modalDeleteTemplate').modal('show');
    });
});
</script>
