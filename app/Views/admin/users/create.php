<!-- Modal Nuevo Usuario -->
<div class="modal fade" id="modalNuevoUsuario" tabindex="-1" role="dialog" aria-labelledby="modalNuevoUsuarioLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalNuevoUsuarioLabel">Crear nuevo usuario</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form id="formNuevoUsuario" action="<?= base_url('/admin/users/create'); ?>" method="post">
        <div class="modal-body">

          <!-- Nombre y Apellido -->
          <div class="form-row">
            <div class="form-group col-md-6">
              <label>Nombre</label>
              <input type="text" name="name" class="form-control" value="<?= old('name') ?>" required>
            </div>
            <div class="form-group col-md-6">
              <label>Apellido</label>
              <input type="text" name="lastname" class="form-control" value="<?= old('lastname') ?>" required>
            </div>
          </div>

          <div class="form-group">
            <label>Correo electrónico</label>
            <input type="email" name="email" class="form-control" value="<?= old('email') ?>" required>
          </div>

          <!-- Contraseña y Confirmación -->
          <div class="form-row">
            <div class="form-group col-md-6">
              <label>Contraseña</label>
              <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <div class="form-group col-md-6">
              <label>Repetir Contraseña</label>
              <input type="password" id="passconf" name="passconf" class="form-control" required>
            </div>
          </div>

          <div class="form-group">
            <label>Rol</label>
            <select name="role_id" class="form-control">

              <option value="">Seleccione un rol...</option>
              <?php foreach ($roles as $r): ?>
                <option value="<?= $r['rol_id']; ?>">
                    <?= $r['descripcion']; ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Alerta de error oculta -->
          <div id="alertaPassword" class="alert alert-danger d-none" role="alert">
            Las contraseñas no coinciden. Por favor, verifícalas.
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Guardar usuario</button>
        </div>
      </form>
    </div>
  </div>
</div>