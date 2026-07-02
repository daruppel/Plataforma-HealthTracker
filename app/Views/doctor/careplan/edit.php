<section class="content">
  <div class="container-fluid">
    
    <!-- Diagnóstico Context Card -->
    <?php if ($diagnosis): ?>
    <div class="card card-outline card-info mb-4">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fas fa-stethoscope text-info mr-2"></i>
          Diagnóstico de Base
        </h3>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-4">
            <strong>Paciente:</strong>
            <p class="text-muted"><?= esc($diagnosis['paciente_nombre'] . ' ' . $diagnosis['paciente_apellido']) ?></p>
          </div>
          <div class="col-md-4">
            <strong>Tipo de Diagnóstico:</strong>
            <p class="text-muted"><?= esc($diagnosis['tipo_diagnostico']) ?></p>
          </div>
          <div class="col-md-4">
            <strong>Fecha Diagnóstico:</strong>
            <p class="text-muted"><?= date('d/m/Y', strtotime($diagnosis['fecha'])) ?></p>
          </div>
          <div class="col-12 mt-2">
            <strong>Detalles/Descripción del Diagnóstico:</strong>
            <p class="text-muted bg-light p-2 rounded border"><?= esc($diagnosis['descripcion']) ?></p>
          </div>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <!-- Care Plan Form Card -->
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Editar Plan de Cuidado</h3>
      </div>
      <div class="card-body">

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <?php if (isset($errors)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                <?php foreach ($errors as $e): ?>
                    <li><?= esc($e) ?></li>
                <?php endforeach; ?>
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('medical_staff/care-plan/update') ?>" method="POST" id="carePlanForm">
          <input type="hidden" name="care_plan_update_id" value="<?= $carePlan['plan_cuidado_id'] ?>">
          
          <!-- Period Settings -->
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="fec_inicio">Fecha de Inicio <span class="text-danger">*</span></label>
                <input 
                  type="date" 
                  name="fec_inicio" 
                  id="fec_inicio" 
                  class="form-control" 
                  value="<?= old('fec_inicio', $carePlan['fec_inicio']) ?>" 
                  required
                >
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="fec_fin">Fecha de Finalización <span class="text-danger">*</span></label>
                <input 
                  type="date" 
                  name="fec_fin" 
                  id="fec_fin" 
                  class="form-control" 
                  value="<?= old('fec_fin', $carePlan['fec_fin']) ?>" 
                  required
                >
              </div>
            </div>
          </div>

          <div class="form-group">
            <label for="comentario_paciente">Instrucciones / Comentarios Generales para el Paciente</label>
            <textarea 
              name="comentario_paciente" 
              id="comentario_paciente" 
              rows="3" 
              class="form-control" 
              placeholder="Escriba indicaciones o comentarios globales sobre el tratamiento..."
            ><?= old('comentario_paciente', $carePlan['comentario_paciente']) ?></textarea>
          </div>

          <!-- Dynamic Tasks / Goals Section -->
          <div class="card card-outline card-secondary mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
              <h4 class="card-title font-weight-bold mb-0">Tareas y Metas del Plan</h4>
              <button type="button" class="btn btn-sm btn-success ml-auto" id="addTaskBtn">
                <i class="fas fa-plus mr-1"></i> Agregar Tarea
              </button>
            </div>
            <div class="card-body p-0">
              <div class="table-responsive">
                <table class="table mb-0" id="tasksTable">
                  <thead>
                    <tr>
                      <th style="width: 25%">Tipo de Meta <span class="text-danger">*</span></th>
                      <th>Descripción / Dosis / Frecuencia <span class="text-danger">*</span></th>
                      <th style="width: 15%" class="text-center">Cumplida</th>
                      <th style="width: 10%" class="text-center">Quitar</th>
                    </tr>
                  </thead>
                  <tbody id="tasksContainer">
                    <?php if (empty($tasks)): ?>
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
                          <input 
                            type="text" 
                            name="tasks[0][descripcion]" 
                            class="form-control" 
                            placeholder="Ej: Tomar 1 comprimido cada 8 horas, 30 min de cinta..." 
                            required
                          >
                        </td>
                        <td class="text-center">
                          <input type="checkbox" name="tasks[0][meta_cumplida]" value="1" style="transform: scale(1.3); margin-top: 10px;">
                        </td>
                        <td class="text-center">
                          <button type="button" class="btn btn-danger btn-sm remove-task-btn" disabled>
                            <i class="fas fa-trash-alt"></i>
                          </button>
                        </td>
                      </tr>
                    <?php else: ?>
                      <?php foreach ($tasks as $index => $task): ?>
                        <tr class="task-row">
                          <td>
                            <select name="tasks[<?= $index ?>][tipo_meta_id]" class="form-control" required>
                              <option value="">Seleccione tipo...</option>
                              <?php foreach ($taskTypes as $type): ?>
                                <option value="<?= $type['tipo_meta_id'] ?>" <?= $task['tipo_meta_id'] == $type['tipo_meta_id'] ? 'selected' : '' ?>>
                                  <?= esc($type['nombre']) ?>
                                </option>
                              <?php endforeach; ?>
                            </select>
                          </td>
                          <td>
                            <input 
                              type="text" 
                              name="tasks[<?= $index ?>][descripcion]" 
                              class="form-control" 
                              value="<?= esc($task['descripcion']) ?>" 
                              placeholder="Ej: Tomar 1 comprimido cada 8 horas, 30 min de cinta..." 
                              required
                            >
                          </td>
                          <td class="text-center">
                            <!-- We use hidden input combined with checkbox so that if unchecked, a 0 is still sent in POST -->
                            <input type="hidden" name="tasks[<?= $index ?>][meta_cumplida]" value="0">
                            <input type="checkbox" name="tasks[<?= $index ?>][meta_cumplida]" value="1" <?= $task['meta_cumplida'] ? 'checked' : '' ?> style="transform: scale(1.3); margin-top: 10px;">
                          </td>
                          <td class="text-center">
                            <button type="button" class="btn btn-danger btn-sm remove-task-btn" <?= count($tasks) === 1 ? 'disabled' : '' ?>>
                              <i class="fas fa-trash-alt"></i>
                            </button>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Form Actions -->
          <div class="mt-4 d-flex justify-content-end">
            <a href="<?= base_url('medical_staff/care-plan') ?>" class="btn btn-secondary mr-2">
              Cancelar
            </a>
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save mr-1"></i> Guardar Cambios
            </button>
          </div>

        </form>

      </div>
    </div>

  </div>
</section>

<style>
/* Micro-animation for dynamically added rows */
.animate-fade-in {
  animation: fadeIn 0.35s ease-out;
}
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(-10px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('tasksContainer');
    const addBtn = document.getElementById('addTaskBtn');
    let taskCounter = <?= max(1, count($tasks)) ?>;

    // Load available task types from PHP array into JS template
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
                <input 
                    type="text" 
                    name="tasks[${taskCounter}][descripcion]" 
                    class="form-control" 
                    placeholder="Ej: Tomar 1 comprimido cada 8 horas, 30 min de cinta..." 
                    required
                >
            </td>
            <td class="text-center">
                <input type="hidden" name="tasks[${taskCounter}][meta_cumplida]" value="0">
                <input type="checkbox" name="tasks[${taskCounter}][meta_cumplida]" value="1" style="transform: scale(1.3); margin-top: 10px;">
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
                row.style.opacity = '0';
                row.style.transform = 'translateY(-10px)';
                row.style.transition = 'all 0.25s ease-out';
                setTimeout(function() {
                    row.remove();
                    updateRemoveButtons();
                }, 250);
            }
        }
    });

    function updateRemoveButtons() {
        const rows = container.querySelectorAll('.task-row');
        const removeButtons = container.querySelectorAll('.remove-task-btn');
        if (rows.length === 1) {
            removeButtons[0].disabled = true;
        } else {
            removeButtons.forEach(btn => btn.disabled = false);
        }
    }
});
</script>
