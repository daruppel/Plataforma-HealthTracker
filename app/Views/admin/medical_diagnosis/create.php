<!-- Modal Nueva entidad medica -->
<div class="modal fade" id="modalNuevoTipoDiagnostico" tabindex="-1" role="dialog" aria-labelledby="modalNuevaEntidadLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalNuevoTipoDiagnosticoLabel">Crear nuevo tipo de diagnostico</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form id="formNuevoTipoDiagnostico" action="<?= base_url('/admin/medical-diagnosis/create'); ?>" method="post">
        <div class="modal-body">

          <!-- Nombre -->
          <div class="form-row">
            <div class="form-group col-md-6">
              <label>Nombre</label>
              <input type="text" name="name" class="form-control" value="<?= old('name') ?>" required>
            </div>
            <div class="form-group col-md-6">
              <label>Descripción</label>
              <input type="text" name="description" class="form-control" value="<?= old('description') ?>" required>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>