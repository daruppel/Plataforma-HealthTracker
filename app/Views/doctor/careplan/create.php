<section class="content">
  <div class="container-fluid">
    
    <!-- Diagnóstico Context Card -->
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

    <!-- Care Plan Form Card -->
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Crear Nuevo Plan de Cuidado</h3>
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

        <form action="<?= base_url('medical_staff/care-plan/create/' . $diagnosis['diagnostico_id']) ?>" method="POST" id="carePlanForm">
          
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
                  value="<?= old('fec_inicio', date('Y-m-d')) ?>" 
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
                  value="<?= old('fec_fin') ?>" 
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
            ><?= old('comentario_paciente') ?></textarea>
          </div>

          <!-- Selector de Plantilla Estandarizada (opcional) -->
          <?php if (!empty($templates)): ?>
          <div class="card card-outline card-info mt-3" id="templateSelectorCard">
            <div class="card-header">
              <h5 class="card-title mb-0">
                <i class="fas fa-clipboard-list text-info mr-1"></i>
                Usar plantilla estandarizada <small class="text-muted">(opcional)</small>
              </h5>
            </div>
            <div class="card-body">
              <div class="form-row align-items-end">
                <div class="col-md-8">
                  <label for="template_select">Plantillas disponibles para este tipo de diagnóstico</label>
                  <select id="template_select" class="form-control">
                    <option value="">-- Sin plantilla (carga manual) --</option>
                    <?php foreach ($templates as $tpl): ?>
                      <option value="<?= $tpl['plan_cuidado_estandar_id'] ?>"><?= esc($tpl['nombre']) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-md-4">
                  <button type="button" id="loadTemplateBtn" class="btn btn-info btn-block" disabled>
                    <i class="fas fa-download mr-1"></i> Cargar tareas de plantilla
                  </button>
                </div>
              </div>
              <small class="text-muted d-block mt-2">
                <i class="fas fa-info-circle"></i>
                Las tareas se cargarán como punto de partida. Podes modificarlas, agregar o quitar filas antes de guardar.
              </small>
            </div>
          </div>
          <?php endif; ?>

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
                      <th style="width: 10%" class="text-center">Quitar</th>
                    </tr>
                  </thead>
                  <tbody id="tasksContainer">
                    <!-- Dynamic task rows go here -->
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
                        <button type="button" class="btn btn-danger btn-sm remove-task-btn" disabled>
                          <i class="fas fa-trash-alt"></i>
                        </button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Form Actions -->
          <div class="mt-4 d-flex justify-content-end">
            <a href="<?= base_url('medical_staff/diagnosis') ?>" class="btn btn-secondary mr-2">
              Cancelar
            </a>
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save mr-1"></i> Guardar Plan de Cuidado
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
    const container  = document.getElementById('tasksContainer');
    const addBtn     = document.getElementById('addTaskBtn');
    let   taskCounter = 1;

    // Load available task types from PHP array into JS template
    const taskTypes = [
        <?php foreach ($taskTypes as $type): ?>
        { id: '<?= $type['tipo_meta_id'] ?>', nombre: '<?= esc($type['nombre']) ?>' },
        <?php endforeach; ?>
    ];

    // ── Template selector logic ─────────────────────────────────────────────
    const templateSelect  = document.getElementById('template_select');
    const loadTemplateBtn = document.getElementById('loadTemplateBtn');

    if (templateSelect) {
        templateSelect.addEventListener('change', function() {
            loadTemplateBtn.disabled = !this.value;
        });

        loadTemplateBtn.addEventListener('click', function() {
            const templateId = templateSelect.value;
            if (!templateId) return;

            loadTemplateBtn.disabled = true;
            loadTemplateBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Cargando...';

            $.getJSON('<?= base_url('medical_staff/care-plan/template-tasks/') ?>' + templateId)
                .done(function(tasks) {
                    if (!tasks || tasks.length === 0) {
                        alert('La plantilla no tiene tareas cargadas.');
                        return;
                    }
                    // Limpiar filas actuales
                    container.innerHTML = '';
                    taskCounter = 0;

                    tasks.forEach(function(task) {
                        const row = buildTaskRow(taskCounter, task.tipo_meta_id, task.descripcion);
                        container.appendChild(row);
                        taskCounter++;
                    });
                    updateRemoveButtons();
                })
                .fail(function() {
                    alert('Error al cargar las tareas de la plantilla.');
                })
                .always(function() {
                    loadTemplateBtn.disabled = !templateSelect.value;
                    loadTemplateBtn.innerHTML = '<i class="fas fa-download mr-1"></i> Cargar tareas de plantilla';
                });
        });
    }
    // ── End template selector logic ────────────────────────────────────────

    /** Construye un <tr> de tarea reutilizable */
    function buildTaskRow(index, selectedTypeId, descripcionValue) {
        const row = document.createElement('tr');
        row.className = 'task-row animate-fade-in';

        let selectOptions = '<option value="">Seleccione tipo...</option>';
        taskTypes.forEach(function(type) {
            const sel = String(type.id) === String(selectedTypeId) ? 'selected' : '';
            selectOptions += `<option value="${type.id}" ${sel}>${type.nombre}</option>`;
        });

        row.innerHTML = `
            <td>
                <select name="tasks[${index}][tipo_meta_id]" class="form-control" required>
                    ${selectOptions}
                </select>
            </td>
            <td>
                <input
                    type="text"
                    name="tasks[${index}][descripcion]"
                    class="form-control"
                    placeholder="Ej: Tomar 1 comprimido cada 8 horas, 30 min de cinta..."
                    value="${descripcionValue || ''}"
                    required
                >
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm remove-task-btn">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </td>
        `;
        return row;
    }

    addBtn.addEventListener('click', function() {
        const row = buildTaskRow(taskCounter, '', '');
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