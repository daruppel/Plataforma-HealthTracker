<section class="content">
  <div class="container-fluid">
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Gestión de entidades médicas</h3>
        <button class="btn btn-success float-right" data-toggle="modal" data-target="#modalNuevaEntidad">
          <i class="fas fa-plus"></i> Nuevo
        </button>
      </div>
      <div class="card-body">
        <table class="table table-bordered table-striped datatable">
          <thead>
            <tr>
              <th>Nombre</th><th>Descripcion</th><th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($entities as $e): ?>
            <tr>
              <td><?= $e['nombre']; ?></td>
              <td><?= $e['descripcion']; ?></td>
              <td>
                <a href="#" class="btn btn-sm btn-warning btn-edit">
                <i class="fas fa-pen"></i></a>
                
                <a href="#" class="btn btn-sm btn-danger btn-delete">
                <i class="fas fa-trash"></i></a>

                <a href="#" class="btn btn-sm btn-info btn-change-pass">
                <i class="fas fa-key"></i></a>
              <!-- <a href="#" class="btn btn-sm btn-info" data-toggle="modal" data-target="#modalChangePass"><i class="fas fa-key"></i></a> -->
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <?= view('admin/medical_entities/create'); ?>
  <?php if (session()->get('errors')): ?>
<script>
    $(document).ready(function () {
        $('#modalNuevaEntidad').modal('show');
    });
</script>
<?php endif; ?>
</section>
