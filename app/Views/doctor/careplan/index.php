<section class="content">
  <div class="container-fluid">
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Planes de Cuidado</h3>
      </div>
      <div class="card-body">
        
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <div class="table-responsive">
          <table class="table table-bordered table-striped datatable">
            <thead>
              <tr>
                <th>Paciente</th>
                <th>Diagnóstico</th>
                <th>Fecha Inicio</th>
                <th>Fecha Fin</th>
                <th>Comentario Paciente</th>
                <th>Tareas / Metas</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($carePlans as $cp): ?>
                <tr>
                  <td>
                    <strong><?= esc($cp['paciente_nombre'] . ' ' . $cp['paciente_apellido']) ?></strong>
                  </td>
                  <td>
                    <span class="badge badge-info mb-1 d-block"><?= esc($cp['diagnostico_tipo']) ?></span>
                    <?php
                      $estado = $cp['diagnostico_estado'] ?? 'pendiente';
                      switch ($estado) {
                        case 'Pendiente':
                          $badge = 'badge-warning';
                          $label = 'Pendiente';
                          break;
                        case 'en_proceso':
                          $badge = 'badge-primary';
                          $label = 'En proceso';
                          break;
                        case 'finalizado':
                          $badge = 'badge-success';
                          $label = 'Finalizado';
                          break;
                        case 'cancelado':
                          $badge = 'badge-danger';
                          $label = 'Cancelado';
                          break;
                        default:
                          $badge = 'badge-secondary';
                          $label = ucfirst($estado);
                      }
                    ?>
                    <span class="badge <?= $badge ?> d-block"><?= $label ?></span>
                  </td>
                  <td><?= date('d/m/Y', strtotime($cp['fec_inicio'])) ?></td>
                  <td><?= date('d/m/Y', strtotime($cp['fec_fin'])) ?></td>
                  <td>
                    <span class="text-muted" title="<?= esc($cp['comentario_paciente'] ?? '') ?>">
                      <?= esc(mb_strimwidth($cp['comentario_paciente'] ?? '', 0, 40, '...')) ?>
                    </span>
                  </td>
                  <td>
                    <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#tasksModal<?= $cp['plan_cuidado_id'] ?>">
                      <i class="fas fa-list-ul"></i> Ver Tareas 
                      <span class="badge badge-light ml-1"><?= count($cp['tasks']) ?></span>
                    </button>

                    <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#pendingModal<?= $cp['plan_cuidado_id'] ?>">
                      <i class="fas fa-check-double"></i> Validar Metas
                      <span class="badge badge-danger ml-1"><?= count($cp['cumplimientos_pendientes']) ?></span>
                    </button>

                    <!-- Tasks Modal -->
                    <div class="modal fade" id="tasksModal<?= $cp['plan_cuidado_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="tasksModalLabel<?= $cp['plan_cuidado_id'] ?>" aria-hidden="true">
                      <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="tasksModalLabel<?= $cp['plan_cuidado_id'] ?>">
                              Tareas para: <?= esc($cp['paciente_nombre'] . ' ' . $cp['paciente_apellido']) ?>
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <div class="mb-3">
                              <strong>Diagnóstico:</strong> <?= esc($cp['diagnostico_tipo']) ?><br>
                              <strong>Período:</strong> <?= date('d/m/Y', strtotime($cp['fec_inicio'])) ?> al <?= date('d/m/Y', strtotime($cp['fec_fin'])) ?>
                            </div>
                            
                            <table class="table table-sm table-bordered">
                              <thead>
                                <tr>
                                  <th>Tipo de Tarea</th>
                                  <th>Descripción / Indicaciones</th>
                                  <th>Estado</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php if (empty($cp['tasks'])): ?>
                                  <tr>
                                    <td colspan="3" class="text-center text-muted">No hay tareas cargadas para este plan.</td>
                                  </tr>
                                <?php else: ?>
                                  <?php foreach ($cp['tasks'] as $task): ?>
                                    <tr>
                                      <td>
                                        <span class="badge badge-secondary"><?= esc($task['tipo_nombre']) ?></span>
                                      </td>
                                      <td><?= esc($task['descripcion']) ?></td>
                                      <td>
                                        <?php if ($task['meta_cumplida']): ?>
                                          <span class="badge badge-success"><i class="fas fa-check"></i> Cumplida</span>
                                        <?php else: ?>
                                          <span class="badge badge-warning"><i class="fas fa-clock"></i> Pendiente</span>
                                        <?php endif; ?>
                                      </td>
                                    </tr>
                                  <?php endforeach; ?>
                                <?php endif; ?>
                              </tbody>
                            </table>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Pending Compliances Modal -->
                    <div class="modal fade" id="pendingModal<?= $cp['plan_cuidado_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="pendingModalLabel<?= $cp['plan_cuidado_id'] ?>" aria-hidden="true">
                      <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                          <div class="modal-header bg-warning">
                            <h5 class="modal-title" id="pendingModalLabel<?= $cp['plan_cuidado_id'] ?>">
                              <i class="fas fa-check-double mr-1"></i> Validaciones pendientes: <?= esc($cp['paciente_nombre'] . ' ' . $cp['paciente_apellido']) ?>
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <?php if (empty($cp['cumplimientos_pendientes'])): ?>
                              <div class="text-center text-muted p-3">
                                <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                <p class="mb-0">No hay cumplimientos pendientes de validación para este plan.</p>
                              </div>
                            <?php else: ?>
                              <table class="table table-sm table-bordered">
                                <thead>
                                  <tr>
                                    <th>Meta / Tarea</th>
                                    <th>Fecha Registro</th>
                                    <th>Duración</th>
                                    <th>Comentario Paciente</th>
                                    <th class="text-center">Acción</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php foreach ($cp['cumplimientos_pendientes'] as $c): ?>
                                    <tr>
                                      <td>
                                        <small class="text-muted d-block"><?= esc($c['tipo_nombre']) ?></small>
                                        <?= esc($c['meta_descripcion']) ?>
                                      </td>
                                      <td><?= date('d/m/Y', strtotime($c['fecha'])) ?></td>
                                      <td><?= (int)$c['duracion_minutos'] ?> min</td>
                                      <td><?= esc($c['comentario'] ?: '—') ?></td>
                                      <td class="text-center">
                                        <button
                                          class="btn btn-xs btn-primary btn-validar"
                                          data-id="<?= $c['cumplimiento_meta_id'] ?>"
                                          data-toggle="modal"
                                          data-target="#modalValidar"
                                          data-parent-modal="#pendingModal<?= $cp['plan_cuidado_id'] ?>"
                                        >
                                          <i class="fas fa-check mr-1"></i>Validar
                                        </button>
                                      </td>
                                    </tr>
                                  <?php endforeach; ?>
                                </tbody>
                              </table>
                            <?php endif; ?>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="btn-group">
                      <?php if ($cp['puede_finalizar']): ?>
                        <form action="<?= base_url('medical_staff/care-plan/finalizar') ?>" method="POST" class="d-inline mr-1" onsubmit="return confirm('¿Está seguro de que desea finalizar este plan de cuidado? El diagnóstico asociado se marcará como finalizado.');">
                          <input type="hidden" name="plan_cuidado_id" value="<?= $cp['plan_cuidado_id'] ?>">
                          <button type="submit" class="btn btn-sm btn-outline-success" title="Finalizar plan de cuidado">
                            <i class="fas fa-check-circle"></i> Finalizar
                          </button>
                        </form>
                      <?php endif; ?>
                      <a href="<?= base_url('medical_staff/care-plan/edit/' . $cp['plan_cuidado_id']) ?>" class="btn btn-sm btn-outline-primary" title="Editar plan de cuidado">
                        <i class="fas fa-edit"></i>
                      </a>
                      <form action="<?= base_url('medical_staff/care-plan/delete') ?>" method="POST" class="d-inline" onsubmit="return confirm('¿Está seguro de que desea eliminar este plan de cuidado y todas sus tareas asociadas?');">
                        <input type="hidden" name="care_plan_id" value="<?= $cp['plan_cuidado_id'] ?>">
                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar plan de cuidado">
                          <i class="fas fa-trash"></i>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>
</section>

<!-- Modal de validación — un único modal, JS inyecta el cumplimiento_id -->
<div class="modal fade" id="modalValidar" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="<?= base_url('medical_staff/care-plan/validar') ?>" method="POST">
        <input type="hidden" name="cumplimiento_id" id="hiddenCumplimientoId">

        <div class="modal-header">
          <h5 class="modal-title"><i class="fas fa-check-double mr-1"></i>Validar cumplimiento</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>

        <div class="modal-body">

          <div class="form-group">
            <label class="d-block">Resultado <span class="text-danger">*</span></label>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="accion" id="accionAprobado" value="validado" required>
              <label class="form-check-label text-success" for="accionAprobado">
                <i class="fas fa-check-circle mr-1"></i>Aprobar
              </label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="accion" id="accionRechazado" value="rechazado">
              <label class="form-check-label text-danger" for="accionRechazado">
                <i class="fas fa-times-circle mr-1"></i>Rechazar
              </label>
            </div>
          </div>

          <div class="form-group" id="puntuacionGroup" style="display:none;">
            <label>Puntuación <span class="text-danger">*</span></label>
            <div class="star-widget">
              <input type="radio" name="puntuacion" id="star5" value="5"><label for="star5"><i class="fas fa-star"></i></label>
              <input type="radio" name="puntuacion" id="star4" value="4"><label for="star4"><i class="fas fa-star"></i></label>
              <input type="radio" name="puntuacion" id="star3" value="3"><label for="star3"><i class="fas fa-star"></i></label>
              <input type="radio" name="puntuacion" id="star2" value="2"><label for="star2"><i class="fas fa-star"></i></label>
              <input type="radio" name="puntuacion" id="star1" value="1"><label for="star1"><i class="fas fa-star"></i></label>
            </div>
          </div>

          <div class="form-group">
            <label for="comentario_medico">Comentario (opcional)</label>
            <textarea name="comentario_medico" id="comentario_medico" rows="3"
              class="form-control" placeholder="Observaciones, recomendaciones..."></textarea>
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-save mr-1"></i>Guardar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<style>
/* Star rating — CSS puro con FontAwesome, sin librería */
.star-widget { display: flex; flex-direction: row-reverse; width: fit-content; gap: 4px; }
.star-widget input { display: none; }
.star-widget label { font-size: 1.6rem; color: #ccc; cursor: pointer; transition: color .15s; }
.star-widget input:checked ~ label,
.star-widget label:hover,
.star-widget label:hover ~ label { color: #f39c12; }
</style>

<script>
// Stacked modal management
let activeParentModal = null;

// Inyecta el ID del cumplimiento al abrir el modal
document.querySelectorAll('.btn-validar').forEach(function(btn) {
  btn.addEventListener('click', function() {
    document.getElementById('hiddenCumplimientoId').value = this.dataset.id;
    if (this.dataset.parentModal) {
      activeParentModal = this.dataset.parentModal;
      $(activeParentModal).modal('hide');
    }
    // Resetea el modal al abrir
    document.querySelectorAll('[name="accion"]').forEach(r => r.checked = false);
    document.querySelectorAll('[name="puntuacion"]').forEach(r => r.checked = false);
    document.getElementById('comentario_medico').value = '';
    document.getElementById('puntuacionGroup').style.display = 'none';
  });
});

// Muestra/oculta estrellas según acción seleccionada
document.querySelectorAll('[name="accion"]').forEach(function(radio) {
  radio.addEventListener('change', function() {
    document.getElementById('puntuacionGroup').style.display =
      this.value === 'validado' ? 'block' : 'none';
    if (this.value === 'rechazado') {
      document.querySelectorAll('[name="puntuacion"]').forEach(r => r.checked = false);
    }
  });
});

// Re-open parent modal when validation modal closes
$('#modalValidar').on('hidden.bs.modal', function () {
  if (activeParentModal) {
    $(activeParentModal).modal('show');
    activeParentModal = null;
  }
});
</script>