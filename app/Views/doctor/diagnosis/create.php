<section class="content">
  <div class="container-fluid">

    <!-- Mensaje éxito -->
    <?php if(session()->getFlashdata('success')): ?>
      <div class="alert alert-success">
        <?= session()->getFlashdata('success'); ?>
      </div>
    <?php endif; ?>

    <!-- Mensajes de error -->
    <?php if(session()->getFlashdata('errors')): ?>
      <div class="alert alert-danger">
        <ul>
          <?php foreach(session()->getFlashdata('errors') as $error): ?>
            <li><?= $error ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <div class="card card-success">
      <div class="card-header">
        <h3 class="card-title">Carga de diagnóstico</h3>
      </div>

      <div class="card-body">
        
        <form id="formDiagnostico" method="post" action="<?= base_url('/medical_staff/diagnosis/create'); ?>">

          <div class="form-row">
            
            <!-- FECHA -->
            <div class="col-md-3">
              <div class="form-group">
                <label for="fecha">Fecha</label>
                <input 
                  type="date" 
                  class="form-control" 
                  id="fecha" 
                  name="fecha" 
                  value="<?= old('fecha') ?>"
                  required
                >
                <small id="errorFecha" class="error"></small>
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
                      <?= old('tipo_diagnostico_id') == $type['tipo_diagnostico_id'] ? 'selected' : '' ?>>
                      <?= $type['nombre'] ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <small id="errorTipo" class="error"></small>
              </div>
            </div>

            <!-- PACIENTE -->
            <div class="col-md-6">
              <div class="form-group">
                <label for="paciente">Paciente</label>
                <select class="form-control" id="paciente" name="paciente_id" required>
                  <option value="">Seleccione un paciente</option>
                  <?php foreach ($patients as $p): ?>
                    <option value="<?= $p['usuario_id'] ?>"
                      <?= old('paciente_id') == $p['usuario_id'] ? 'selected' : '' ?>>
                      <?= $p['nombre'] . ' ' . $p['apellido'] ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <small id="errorPaciente" class="error"></small>
              </div>
            </div>

          </div>

          <div class="form-row">
            <!-- DESCRIPCIÓN -->
            <div class="col-md-12">
            <div class="form-group">
              <label for="descripcion">Descripción</label>
              <textarea 
                class="form-control" 
                id="descripcion" 
                name="descripcion" 
                rows="3"
                placeholder="Ingrese una breve descripción..."
                required
              ><?= old('descripcion') ?></textarea>
              <small id="errorDescripcion" class="error"></small>
            </div>
            </div>

          <button type="submit" class="btn btn-success">
            Guardar diagnóstico
          </button>

          <a href="<?= base_url('/medical_staff/diagnosis'); ?>" class="btn btn-secondary">
            Volver
          </a>

        </form>

      </div>
    </div>

  </div>
</section>

<script>
document.addEventListener("DOMContentLoaded", function () {

  const paciente = document.getElementById("paciente");
  const tipo = document.getElementById("tipo_diagnostico_id");
  const fecha = document.getElementById("fecha");
  const form = document.getElementById("formDiagnostico");
  const descripcion = document.getElementById("descripcion");

  function validarSelect(campo, errorId, mensaje) {
    if (campo.value === "") {
      campo.classList.add("invalido");
      campo.classList.remove("valido");
      document.getElementById(errorId).textContent = mensaje;
      return false;
    } else {
      campo.classList.remove("invalido");
      campo.classList.add("valido");
      document.getElementById(errorId).textContent = "";
      return true;
    }
  }

  function validarFecha(campo, errorId) {
    if (campo.value === "") {
      document.getElementById(errorId).textContent = "La fecha es obligatoria";
      return false;
    }

    const hoy = new Date().toISOString().split("T")[0];
    if (campo.value > hoy) {
      document.getElementById(errorId).textContent = "La fecha no puede ser futura";
      return false;
    }

    document.getElementById(errorId).textContent = "";
    return true;
  }

  function validarTexto(campo, errorId, mensaje) {
  if (campo.value.trim() === "") {
    campo.classList.add("invalido");
    campo.classList.remove("valido");
    document.getElementById(errorId).textContent = mensaje;
    return false;
  } else {
    campo.classList.remove("invalido");
    campo.classList.add("valido");
    document.getElementById(errorId).textContent = "";
    return true;
  }
}

  // Tiempo real
  paciente.addEventListener("change", () => validarSelect(paciente, "errorPaciente", "Seleccione un paciente"));
  tipo.addEventListener("change", () => validarSelect(tipo, "errorTipo", "Seleccione un tipo"));
  fecha.addEventListener("change", () => validarFecha(fecha, "errorFecha"));
  descripcion.addEventListener("input", () => {
    validarTexto(descripcion, "errorDescripcion", "La descripción es obligatoria");
  });

  // Submit
  form.addEventListener("submit", function (e) {
    const v1 = validarSelect(paciente, "errorPaciente", "Seleccione un paciente");
    const v2 = validarSelect(tipo, "errorTipo", "Seleccione un tipo");
    const v4 = validarFecha(fecha, "errorFecha");
    const v5 = validarTexto(descripcion, "errorDescripcion", "La descripción es obligatoria");


    if (!v1 || !v2 || !v3 || !v4 || !v5) {
      e.preventDefault();
    }
  });

});
</script>