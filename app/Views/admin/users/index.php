<section class="content">
  <div class="container-fluid">
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Gestión de Usuarios</h3>
        <a href="<?= base_url('admin/usuarios/create'); ?>" class="btn btn-light float-right">Nuevo</a>
      </div>
      <div class="card-body">
        <table class="table table-bordered table-striped datatable">
          <thead>
            <tr>
              <th>ID</th><th>Nombre</th><th>Email</th><th>Activo</th><th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($users as $u): ?>
            <tr>
              <td><?= $u['nombre']; ?></td>
              <td><?= $u['apellido']; ?></td>
              <td><?= $u['email']; ?></td>
              <td><?= $u['activo']; ?></td>
              <td>
                <a href="#" class="btn btn-sm btn-warning">Editar</a>
                <a href="#" class="btn btn-sm btn-danger">Eliminar</a>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>

