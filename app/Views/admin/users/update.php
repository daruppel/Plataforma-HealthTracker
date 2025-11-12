<!-- Modal Nuevo Usuario -->
<div class="modal fade" id="modalActualizacionUsuario" tabindex="-1" role="dialog" aria-labelledby="modalActualizacionUsuarioLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalActualizacionUsuarioLabel">Actualizar usuario</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form id="formNuevoUsuario" action="<?= base_url('admin/usuarios/update'); ?>" method="post">
        <div class="modal-body">

          <!-- 🧍‍♂️ Nombre y Apellido -->
          <div class="form-row">
            <div class="form-group col-md-6">
              <label>Nombre</label>
              <input type="text" name="name" class="form-control" value="<?= old('name') ?>" required>
            </div>
            <div class="form-group col-md-6">
              <label>Apellido</label>
              <input type="text" name="surname" class="form-control" value="<?= old('surname') ?>" required>
            </div>
          </div>

          <div class="form-group">
            <label>Correo electrónico</label>
            <input type="email" name="email" class="form-control" value="<?= old('email') ?>" required>
          </div>

          <div class="form-group">
            <label>Rol</label>
            <select name="role_id" class="form-control">
              <option value="1">Administrador</option>
              <option value="2">Profesional</option>
              <option value="3" selected>Paciente</option>
            </select>
          </div

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar usuario</button>
        </div>
      </form>
    </div>
  </div>
</div>