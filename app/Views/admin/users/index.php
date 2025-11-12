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
              <td>---</td>
              <td>
                <a href="#" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#modalActualizacionUsuario"><i class="fas fa-pen"></i></a>
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
  <?= view('admin/users/create'); ?>
  <?= view('admin/users/update'); ?>
  <?= view('admin/users/delete'); ?>
  <?= view('admin/users/change-pass'); ?>
  
</section>



