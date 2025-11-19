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
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <?= view('admin/medical_entities/create'); ?>
</section>
