<section class="content">
  <div class="container-fluid">

    <div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title">Editar Plan de Cuidado Estandarizado</h3>
      </div>
      <div class="card-body">

        <?php if (session()->getFlashdata('error')): ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('errors')): ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
              <?php foreach ((array) session()->getFlashdata('errors') as $err): ?>
                <li><?= esc($err) ?></li>
              <?php endforeach; ?>
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        <?php endif; ?>

        <form action="<?= base_url('admin/care-plan-templates/update/' . $template['plan_cuidado_estandar_id']) ?>"
              method="POST" id="formEditTemplate">

          <div class="form-row">
            <!-- Nombre -->
            <div class="form-group col-md-6">
              <label for="nombre">Nombre <span class="text-danger">*</span></label>
              <input type="text" id="nombre" name="nombre" class="form-control"
                     value="<?= esc(old('nombre', $template['nombre'])) ?>" required maxlength="100">
            </div>
            <!-- Tipo Diagnóstico -->
            <div class="form-group col-md-6">
              <label for="tipo_diagnostico_id">Tipo de Diagnóstico <span class="text-danger">*</span></label>
              <select id="tipo_diagnostico_id" name="tipo_diagnostico_id" class="form-control" required>
                <option value="">Seleccione...</option>
                <?php foreach ($diagnosisTypes as $dt): ?>
                  <?php $sel = old('tipo_diagnostico_id', $template['tipo_diagnostico_id']) == $dt['tipo_diagnostico_id'] ? 'selected' : ''; ?>
                  <option value="<?= $dt['tipo_diagnostico_id'] ?>" <?= $sel ?>>
                    <?= esc($dt['nombre']) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <div class="form-row">
            <!-- Descripción -->
            <div class="form-group col-md-9">
              <label for="descripcion">Descripción</label>
              <input type="text" id="descripcion" name="descripcion" class="form-control"
                     value="<?= esc(old('descripcion', $template['descripcion'])) ?>" maxlength="255">
            </div>
            <!-- Activo -->
            <div class="form-group col-md-3">
              <label for="activo">Estado</label>
              <select id="activo" name="activo" class="form-control">
                <?php $actVal = old('activo', $template['activo']); ?>
                <option value="1" <?= $actVal == '1' ? 'selected' : '' ?>>Activo</option>
                <option value="0" <?= $actVal == '0' ? 'selected' : '' ?>>Inactivo</option>
              </select>
            </div>
          </div>

          <!-- Sección dinámica de tareas -->
          <div class="card card-outline card-secondary mt-3">
            <div class="card-header d-flex justify-content-between align-items-center">
              <h5 class="card-title font-weight-bold mb-0">Tareas del Plan <span class="text-danger">*</span></h5>
              <button type="button" class="btn btn-sm btn-success" id="addTaskBtn">
                <i class="fas fa-plus mr-1"></i> Agregar Tarea
              </button>
            </div>
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table mb-0" id="tasksTable">
                  <thead>
                    <tr>
                      <th style="width:30%">Tipo de Meta <span class="text-danger">*</span></th>
                      <th>Descripción <span class="text-danger">*</span></th>
                      <th style="width:8%" class="text-center">Quitar</th>
                    </tr>
                  </thead>
                  <tbody id="tasksContainer">
                    <?php foreach ($tasks as $idx => $task): ?>
                    <tr class="task-row">
                      <td>
                        <select name="tasks[<?= $idx ?>][tipo_meta_id]" class="form-control" required>
                          <option value="">Seleccione tipo...</option>
                          <?php foreach ($taskTypes as $type): ?>
                            <option value="<?= $type['tipo_meta_id'] ?>"
                              <?= $task['tipo_meta_id'] == $type['tipo_meta_id'] ? 'selected' : '' ?>>
                              <?= esc($type['nombre']) ?>
                            </option>
                          <?php endforeach; ?>
                        </select>
                      </td>
                      <td>
                        <input type="text" name="tasks[<?= $idx ?>][descripcion]" class="form-control"
                               value="<?= esc($task['descripcion']) ?>"
                               placeholder="Ej: Tomar 1 comprimido cada 8 horas..." required>
                      </td>
                      <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm remove-task-btn"
                          <?= count($tasks) === 1 ? 'disabled' : '' ?>>
                          <i class="fas fa-trash-alt"></i>
                        </button>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($tasks)): ?>
                    <!-- Fila inicial vacía si no hay tareas guardadas -->
                    <tr class="task-row animate-fade-in">
                      <td>
                        <select name="tasks[0][tipo_meta_id]" class="form-control" required>
                          <option value="">Seleccione tipo...</option>
                          <?php foreach ($taskTypes as $type): ?>
                            <option value="<?= $type['tipo_meta_id'] ?>"><?= esc($type['nombre']) ?></option>
                          <?php endforeach; ?>
                        </select>
                      </td>
                      <td>
                        <input type="text" name="tasks[0][descripcion]" class="form-control"
                               placeholder="Ej: Tomar 1 comprimido cada 8 horas..." required>
                      </td>
                      <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm remove-task-btn" disabled>
                          <i class="fas fa-trash-alt"></i>
                        </button>
                      </td>
                    </tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Acciones -->
          <div class="mt-4 d-flex justify-content-end">
            <a href="<?= base_url('admin/care-plan-templates') ?>" class="btn btn-secondary mr-2">Cancelar</a>
            <button type="submit" class="btn btn-warning">
              <i class="fas fa-save mr-1"></i> Guardar Cambios
            </button>
          </div>

        </form>
      </div>
    </div>

  </div>
</section>

<style>
.animate-fade-in {
  animation: fadeIn 0.35s ease-out;
}
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(-10px); }
  to   { opacity: 1; transform: translateY(0); }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container  = document.getElementById('tasksContainer');
    const addBtn     = document.getElementById('addTaskBtn');
    let   taskCounter = <?= max(count($tasks), 1) ?>;

    const taskTypes = [
        <?php foreach ($taskTypes as $type): ?>
        { id: '<?= $type['tipo_meta_id'] ?>', nombre: '<?= esc($type['nombre']) ?>' },
        <?php endforeach; ?>
    ];

    addBtn.addEventListener('click', function() {
        const row = document.createElement('tr');
        row.className = 'task-row animate-fade-in';

        let selectOptions = '<option value="">Seleccione tipo...</option>';
        taskTypes.forEach(function(type) {
            selectOptions += `<option value="${type.id}">${type.nombre}</option>`;
        });

        row.innerHTML = `
            <td>
                <select name="tasks[${taskCounter}][tipo_meta_id]" class="form-control" required>
                    ${selectOptions}
                </select>
            </td>
            <td>
                <input type="text" name="tasks[${taskCounter}][descripcion]" class="form-control"
                       placeholder="Ej: Tomar 1 comprimido cada 8 horas..." required>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm remove-task-btn">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </td>
        `;

        container.appendChild(row);
        taskCounter++;
        updateRemoveButtons();
    });

    container.addEventListener('click', function(e) {
        if (e.target.closest('.remove-task-btn')) {
            const row = e.target.closest('.task-row');
            if (row) {
                row.style.opacity    = '0';
                row.style.transform  = 'translateY(-10px)';
                row.style.transition = 'all 0.25s ease-out';
                setTimeout(function() {
                    row.remove();
                    updateRemoveButtons();
                }, 250);
            }
        }
    });

    function updateRemoveButtons() {
        const rows          = container.querySelectorAll('.task-row');
        const removeButtons = container.querySelectorAll('.remove-task-btn');
        if (rows.length === 1) {
            removeButtons[0].disabled = true;
        } else {
            removeButtons.forEach(btn => btn.disabled = false);
        }
    }
});
</script>
