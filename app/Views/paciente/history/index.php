<section class="content">
    <div class="container-fluid">

        <div class="card card-primary">

            <div class="card-header">
                <h3 class="card-title">
                    Historial de Diagnosticos
                </h3>
            </div>

            <div class="card-body">

                <?php if (empty($history)): ?>

                    <div class="alert alert-info">
                        No posee diagnósticos registrados.
                    </div>

                <?php else: ?>

                    <table class="table table-bordered table-striped datatable">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Diagnóstico</th>
                                <th>Profesional</th>
                                <th>Estado</th>
                                <th>Acción</th>
                            </tr>
                        </thead>

                        <tbody>

                        <?php foreach ($history as $diagnosis): ?>

                            <tr>

                                <td>
                                    <?= date('d/m/Y', strtotime($diagnosis['fecha'])) ?>
                                </td>

                                <td>
                                    <?= esc($diagnosis['tipo_diagnostico']) ?>
                                </td>

                                <td>
                                    <?= esc($diagnosis['medico_nombre'] . ' ' . $diagnosis['medico_apellido']) ?>
                                </td>

                                <td>

                                    <?php if ($diagnosis['estado'] == 'Activo'): ?>

                                        <span class="badge badge-success">
                                            <?= esc($diagnosis['estado']) ?>
                                        </span>

                                    <?php else: ?>

                                        <span class="badge badge-secondary">
                                            <?= esc($diagnosis['estado']) ?>
                                        </span>

                                    <?php endif; ?>

                                </td>

                                <td>

                                    <button type="button"
                                            class="btn btn-primary btn-sm btn-ver-plan"
                                            data-id="<?= $diagnosis['diagnostico_id'] ?>">
                                        <i class="fas fa-eye"></i> Ver Plan
                                    </button>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                        </tbody>

                    </table>

                <?php endif; ?>

            </div>

        </div>

    </div>
</section>

<!-- Modal Ver Plan -->
<div class="modal fade" id="planModal" tabindex="-1" role="dialog" aria-labelledby="planModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="planModalLabel"><i class="fas fa-file-medical-alt mr-2"></i>Detalle del Plan de Cuidado</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-dark">
                <!-- Loading spinner -->
                <div id="modalLoading" class="text-center my-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Cargando...</span>
                    </div>
                    <p class="mt-2 text-muted">Obteniendo información del plan...</p>
                </div>

                <!-- Content -->
                <div id="modalContent" style="display: none;">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Diagnóstico:</strong>
                            <p id="detDiagnostico" class="text-muted"></p>
                        </div>
                        <div class="col-md-6">
                            <strong>Profesional:</strong>
                            <p id="detMedico" class="text-muted"></p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Fecha de Inicio:</strong>
                            <p id="detFechaInicio" class="text-muted"></p>
                        </div>
                        <div class="col-md-6">
                            <strong>Fecha de Fin:</strong>
                            <p id="detFechaFin" class="text-muted"></p>
                        </div>
                    </div>
                    <div class="mb-3">
                        <strong>Comentarios / Indicaciones:</strong>
                        <div id="detComentarios" class="text-muted bg-light p-3 rounded" style="min-height: 50px; white-space: pre-wrap;"></div>
                    </div>

                    <hr>

                    <h5 class="mb-3 text-primary"><i class="fas fa-tasks mr-2"></i>Metas y Tareas del Plan</h5>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <th>Categoría / Tipo</th>
                                    <th>Descripción de la Meta</th>
                                    <th class="text-center" style="width: 120px;">Estado</th>
                                </tr>
                            </thead>
                            <tbody id="metasList">
                                <!-- Se poblará vía AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('adminlte/plugins/jquery/jquery.min.js'); ?>"></script>
<script>
$(document).ready(function() {
    $('.btn-ver-plan').on('click', function() {
        var diagnosisId = $(this).data('id');
        var url = '<?= base_url('paciente/care-plan-history') ?>/' + diagnosisId;

        // Mostrar spinner, ocultar contenido
        $('#modalLoading').show();
        $('#modalContent').hide();
        $('#planModal').modal('show');

        $.ajax({
            url: url,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    var diag = response.diagnostico;
                    var plan = response.plan_cuidado;
                    var metas = response.metas;

                    $('#detDiagnostico').text(diag.tipo_diagnostico || 'No especificado');
                    $('#detMedico').text((diag.medico_nombre || '') + ' ' + (diag.medico_apellido || ''));
                    
                    if (plan) {
                        // Formatear fechas desde YYYY-MM-DD a DD/MM/YYYY localmente o usar la de la DB
                        var fInicio = plan.fec_inicio ? plan.fec_inicio.split('-').reverse().join('/') : 'No especificada';
                        var fFin = plan.fec_fin ? plan.fec_fin.split('-').reverse().join('/') : 'No especificada';
                        
                        $('#detFechaInicio').text(fInicio);
                        $('#detFechaFin').text(fFin);
                        $('#detComentarios').text(plan.comentario_paciente || 'Sin comentarios adicionales.');
                    } else {
                        $('#detFechaInicio').text('No asignado');
                        $('#detFechaFin').text('No asignado');
                        $('#detComentarios').text('No hay un plan de cuidado creado para este diagnóstico.');
                    }

                    // Limpiar metas
                    var metasBody = $('#metasList');
                    metasBody.empty();

                    if (metas && metas.length > 0) {
                        $.each(metas, function(index, meta) {
                            var isCompleted = meta.meta_cumplida == 1 || meta.meta_cumplida === true || meta.meta_cumplida === '1';
                            var badgeClass = isCompleted ? 'badge-success' : 'badge-warning';
                            var badgeText = isCompleted ? 'Cumplida' : 'Pendiente';
                            
                            var row = $('<tr>');
                            row.append($('<td>').text(meta.tipo_nombre || 'General'));
                            row.append($('<td>').text(meta.descripcion || ''));
                            row.append($('<td class="text-center">').html('<span class="badge ' + badgeClass + '">' + badgeText + '</span>'));
                            
                            metasBody.append(row);
                        });
                    } else {
                        metasBody.append('<tr><td colspan="3" class="text-center text-muted">No hay tareas o metas registradas para este plan.</td></tr>');
                    }

                    $('#modalLoading').hide();
                    $('#modalContent').show();
                } else {
                    alert('Error: ' + response.message);
                    $('#planModal').modal('hide');
                }
            },
            error: function(xhr, status, error) {
                alert('No se pudo obtener la información del plan de cuidado.');
                $('#planModal').modal('hide');
            }
        });
    });
});
</script>