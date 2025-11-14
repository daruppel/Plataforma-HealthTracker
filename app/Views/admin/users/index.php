<section class="content">
  <div class="container-fluid">
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Gestión de Usuarios</h3>
        <button class="btn btn-success float-right" data-toggle="modal" data-target="#modalNuevoUsuario">
          <i class="fas fa-plus"></i> Nuevo
        </button>
      </div>
      <div class="card-body">
        <table class="table table-bordered table-striped datatable">
          <thead>
            <tr>
              <th>Nombre</th><th>Apellido</th><th>Email</th><th>Activo</th><th>Rol</th><th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($users as $u): ?>
            <tr>
              <td><?= $u['nombre']; ?></td>
              <td><?= $u['apellido']; ?></td>
              <td><?= $u['email']; ?></td>
              <td><?= $u['activo']; ?></td>
              <td><?= $u['rol_desc']; ?></td>
              <td>
                <a href="#" class="btn btn-sm btn-warning btn-edit"
                  data-id="<?= $u['usuario_id']; ?>"
                  data-nombre="<?= $u['nombre']; ?>"
                  data-apellido="<?= $u['apellido']; ?>"
                  data-email="<?= $u['email']; ?>"
                  data-rol="<?= $u['rol_id']; ?>">
                <i class="fas fa-pen"></i></a>
                <a href="#" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modalDeleteUser"><i class="fas fa-trash"></i></a>
                <a href="#" class="btn btn-sm btn-info" data-toggle="modal" data-target="#modalChangePass"><i class="fas fa-key"></i></a>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <?= view('admin/users/create', ['roles'=>$roles]); ?>
  <?= view('admin/users/update'); ?>
  <?= view('admin/users/delete'); ?>
  <?= view('admin/users/change-pass'); ?>

  <script>
  $(document).ready(function() {
    $('.btn-edit').on('click', function(e) {
      e.preventDefault();
      const id = $(this).data('id');
      const nombre = $(this).data('nombre');
      const apellido = $(this).data('apellido');
      const email = $(this).data('email');
      const rol = $(this).data('rol');
      console.log({ id, nombre, apellido, email, rol }); // para testear

      $('#usuario_id').val(id);
      $('[name="name"]').val(nombre);
      $('[name="surname"]').val(apellido);
      $('[name="email"]').val(email);
      $('[name="role_id"]').val(rol);

      $('#modalActualizacionUsuario').modal('show');
    });
  });
</script>

<?php if (session()->get('errors')): ?>
<script>
    $(document).ready(function () {
        $('#modalNuevoUsuario').modal('show');
    });
</script>
<?php endif; ?>
</section>



