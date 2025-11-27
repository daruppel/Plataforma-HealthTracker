<section class="content">
  <div class="container-fluid">
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Gestión de tipos de diagnosticos</h3>
        <button class="btn btn-success float-right" data-toggle="modal" data-target="#modalNuevoTipoDiagnostico">
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
          <tbody> <!--TODO-->
            <?php foreach($diagnosisTypes as $dt): ?>
            <tr>
              <td><?= $dt['nombre']; ?></td>
              <td><?= $dt['descripcion']; ?></td>
              <td>
                <a href="#" class="btn btn-sm btn-warning btn-edit"
                  data-id="<?= $dt['tipo_diagnostico_id']; ?>"
                  data-nombre="<?= $dt['nombre']; ?>"
                  data-descripcion="<?= $dt['descripcion']; ?>">
                <i class="fas fa-pen"></i></a>
                
                <a href="#" class="btn btn-sm btn-danger btn-delete"
                  data-id="<?= $dt['tipo_diagnostico_id']; ?>"
                  data-nombre="<?= $dt['nombre']; ?>">
                <i class="fas fa-trash"></i></a>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div> 
  <?= view('admin/medical_diagnosis/create'); ?>
  <?= view('admin/medical_diagnosis/delete'); ?>
  <?= view('admin/medical_diagnosis/update'); ?>

  <!--Script para volver a abrir el modal luego de detectar errores-->
  <?php if (session()->get('errors_create')): ?>
<script>
    $(document).ready(function () {
        $('#modalNuevoTipoDiagnostico').modal('show');
    });
</script>
<?php endif; ?>
      <!--Script que carga los datos para la eliminación -->
<script>
$(document).ready(function() {
    $('.btn-delete').on('click', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        const nombre = $(this).data('nombre');
        
        $('#delete_diagnosis_id').val(id);
        $('#deleteDiagnosisName').text(nombre);

        $('#modalDeleteDiagnosis').modal('show');
    });
});
</script>
          <!--Script que carga los datos para la actualización -->
  <script>
  $(document).ready(function() {
    $('.btn-edit').on('click', function(e) {
      e.preventDefault();
      const id = $(this).data('id');
      const nombre = $(this).data('nombre');
      const descripcion = $(this).data('descripcion');
      
      $('#medical_entitie_update_id').val(id);
      $('[name="name"]').val(nombre);
      $('[name="description"]').val(descripcion);

      $('#modalActualizacionEntidad').modal('show');
    });
  });
</script>
<!--Script que carga los datos antiguos para actualizar, cuando retorna con error -->
<script>
$(document).ready(function () {
    <?php if (session()->get('errors_update')): ?>
        console.log("cargando datos...");
        $('#medical_entitie_update_id').val('<?= old('medical_entitie_update_id') ?>');
        $('[name="name"]').val('<?= old('name') ?>');
        $('[name="description"]').val('<?= old('description') ?>');

        $('#modalActualizacionEntidad').modal('show');

    <?php endif; ?>
});
</script>
</section>
