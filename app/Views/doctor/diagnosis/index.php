
<section class="content">
  <div class="container-fluid">
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Diagnosticos</h3>
        <button class="btn btn-success float-right" data-toggle="modal" data-target="#modalNuevoTipoDiagnostico">
          <i class="fas fa-plus"></i> Nuevo
        </button>
      </div>
      <div class="card-body">
        <p>Acá tiene que venir la tabla</p>
          <table class="table table-bordered table-striped datatable">
          <thead>
            <tr>
              <th>Nombre</th><th>Descripcion</th><th>Acciones</th>
            </tr> 
          </thead>
          <tbody>
            <?php foreach($diagnosis as $d): ?>
            <tr>
              <td><?= $d['nombre']; ?></td>
              <td><?= $d['descripcion']; ?></td>
              <td>
                <a href="#" class="btn btn-sm btn-warning btn-edit"
                  data-id="<?= $d['diagnostico_id']; ?>"
                  data-nombre="<?= $d['nombre']; ?>"
                  data-descripcion="<?= $d['descripcion']; ?>">
                <i class="fas fa-pen"></i></a>
                
                <a href="#" class="btn btn-sm btn-danger btn-delete"
                  data-id="<?= $d['diagnostico_id']; ?>"
                  data-nombre="<?= $d['nombre']; ?>">
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