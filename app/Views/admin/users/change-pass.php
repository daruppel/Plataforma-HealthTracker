<!-- Modal Nuevo Usuario -->
<div class="modal fade" id="modalChangePass" tabindex="-1" role="dialog" aria-labelledby="modalChangePassLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalChangePassLabel">Cambiar Contraseña</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

   <form id="formChangePass" action="<?= base_url('admin/usuarios/store'); ?>" method="post">
        <div class="modal-body">

            <div class="form-group">
                <label>Contraseña</label>
                <input type="password" id="passwordNew" name="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Repetir Contraseña</label>
                <input type="password" id="passconfNew" name="passconf" class="form-control" required>
            </div>

            <div id="alertaPassword" class="alert alert-danger d-none" role="alert">
            Las contraseñas no coinciden. Por favor, verifícalas.
            </div>

            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Cambiar Contraseña</button>
            </div>
      </form>
    </div>
  </div>
</div>