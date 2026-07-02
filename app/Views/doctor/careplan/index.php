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
                    <span class="badge badge-info"><?= esc($cp['diagnostico_tipo']) ?></span>
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
                  </td>
                  <td>
                    <div class="btn-group">
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