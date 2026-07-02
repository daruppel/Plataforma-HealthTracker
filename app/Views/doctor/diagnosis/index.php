
<section class="content">
  <div class="container-fluid">
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Diagnosticos</h3>
        <a href="<?= base_url('/medical_staff/diagnosis/create') ?>" class="btn btn-success float-right">
          <i class="fas fa-plus"></i> Nuevo diagnóstico
        </a>
      </div>
      <div class="card-body">
        <div class="card-body">

          <!-- BUSCADOR -->
          <div class="form-group">
            <input 
              type="text" 
              id="buscadorPaciente" 
              class="form-control" 
              placeholder="Buscar paciente..."
            >
          </div>

          <!-- LISTADO -->
          <?php foreach ($patients as $p): ?>
            
            <div class="card mb-3 paciente-card">

              <!-- HEADER PACIENTE -->
              <div class="card-header d-flex justify-content-between align-items-center">
                <strong class="nombre-paciente"><?= $p['nombre']; ?></strong>

                <div>
                  <span class="badge badge-secondary">
                    <?= count($p['diagnosticos']); ?> diagnósticos
                  </span>

                </div>
              </div>

              <!-- DIAGNÓSTICOS -->
              <div class="card-body p-2">
                <?php foreach ($p['diagnosticos'] as $d): ?>
                  
                  <div class="d-flex justify-content-between align-items-center border-bottom p-2">

                    <div>
                      <span>• <?= $d['tipo']; ?></span>
                    </div>

                    <div>
                      <?php
                        $estado = $d['estado'] ?? 'pendiente';

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
                      <span class="badge <?= $badge; ?> ml-2">
                          <?= $label; ?>
                        </span>
                      <small class="text-muted"><?= date('d/m/Y', strtotime($d['fecha'])); ?></small>

                      <button class="btn btn-sm btn-outline-primary ml-2">
                        <i class="fas fa-eye"></i>
                      </button>
                       <?php if ($estado === 'Pendiente'): ?>
                        <a title="Crear plan de cuidado" href="<?= base_url('/medical_staff/care-plan/create/'. $d['diagnostico_id']); ?>" 
                          class="btn btn-sm btn-outline-success ml-2">
                          <i class="fas fa-notes-medical"></i>
                        </a>
                      <?php endif; ?>
                    </div>
                  </div>

                <?php endforeach; ?>
              </div>

            </div>

          <?php endforeach; ?>

        </div>
      </div>
    </div>
  </div> 

</section>

<script>
document.getElementById("buscadorPaciente").addEventListener("input", function() {

  let filtro = this.value.toLowerCase();
  let pacientes = document.querySelectorAll(".paciente-card");

  pacientes.forEach(card => {
    let nombre = card.querySelector(".nombre-paciente").textContent.toLowerCase();

    if (nombre.includes(filtro)) {
      card.style.display = "";
    } else {
      card.style.display = "none";
    }
  });

});
</script>



