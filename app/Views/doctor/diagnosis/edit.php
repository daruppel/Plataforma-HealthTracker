<section class="content">
  <div class="container-fluid">

    <?php if (session()->getFlashdata('errors')): ?>
      <div class="alert alert-danger">
        <ul class="mb-0">
          <?php foreach (session()->getFlashdata('errors') as $error): ?>
            <li><?= esc($error) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-edit mr-2"></i>Editar diagnóstico</h3>
      </div>

      <div class="card-body">
        <form method="post" action="<?= base_url('/medical_staff/diagnosis/update') ?>">
          <input type="hidden" name="diagnosis_id" value="<?= $diagnosis['diagnostico_id'] ?>">

          <div class="form-row">

            <!-- FECHA -->
            <div class="col-md-3">
              <div class="form-group">
                <label for="fecha">Fecha</label>
                <input type="date" class="form-control" id="fecha" name="fecha"
                       value="<?= old('fecha', $diagnosis['fecha']) ?>" required>
              </div>
            </div>

            <!-- TIPO DIAGNÓSTICO -->
            <div class="col-md-3">
              <div class="form-group">
                <label for="tipo_diagnostico_id">Tipo de diagnóstico</label>
                <select class="form-control" id="tipo_diagnostico_id" name="tipo_diagnostico_id" required>
                  <option value="">Seleccione un tipo</option>
                  <?php foreach ($medicalDiagnosis as $type): ?>
                    <option value="<?= $type['tipo_diagnostico_id'] ?>"
                      <?= old('tipo_diagnostico_id', $diagnosis['tipo_diagnostico_id']) == $type['tipo_diagnostico_id'] ? 'selected' : '' ?>>
                      <?= esc($type['nombre']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <!-- PACIENTE -->
            <div class="col-md-6">
              <div class="form-group">
                <label for="paciente_id">Paciente</label>
                <select class="form-control" id="paciente_id" name="paciente_id" required>
                  <option value="">Seleccione un paciente</option>
                  <?php foreach ($patients as $p): ?>
                    <option value="<?= $p['usuario_id'] ?>"
                      <?= old('paciente_id', $diagnosis['paciente_id']) == $p['usuario_id'] ? 'selected' : '' ?>>
                      <?= esc($p['nombre'] . ' ' . $p['apellido']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

          </div>

          <!-- DESCRIPCIÓN -->
          <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion"
                      rows="3" required><?= esc(old('descripcion', $diagnosis['descripcion'])) ?></textarea>
          </div>

          <button type="submit" class="btn btn-warning">
            <i class="fas fa-save mr-1"></i>Guardar cambios
          </button>
          <a href="<?= base_url('/medical_staff/diagnosis') ?>" class="btn btn-secondary ml-2">Cancelar</a>

        </form>
      </div>
    </div>

  </div>
</section>
