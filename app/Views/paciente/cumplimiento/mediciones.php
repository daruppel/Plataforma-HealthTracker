<section class="content">
  <div class="container-fluid">
    <div class="row mb-3">
      <div class="col-12">
        <h4><i class="fas fa-chart-bar mr-2"></i>Estadísticas</h4>
      </div>
    </div>

    <?php if (!empty($planes)): ?>
    <!-- Selector de plan (igual al selector de paciente del médico) -->
    <div class="card card-outline card-info">
      <div class="card-header">
        <h3 class="card-title">Filtrar por Plan de Cuidado</h3>
      </div>
      <div class="card-body">
        <div class="form-group mb-0">
          <label for="planSelect">Plan de Cuidado</label>
          <select id="planSelect" class="form-control">
            <option value="all">— Todos mis planes (vista global) —</option>
            <?php foreach ($planes as $plan): ?>
              <option value="<?= esc($plan['plan_cuidado_id']) ?>">
                <?= esc($plan['tipo_diagnostico']) ?> &nbsp;|&nbsp;
                <?= date('d/m/Y', strtotime($plan['fec_inicio'])) ?> al <?= date('d/m/Y', strtotime($plan['fec_fin'])) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <!-- KPIs Cards (se actualizan con JS) -->
    <div class="row">
      <div class="col-md-3">
        <div class="small-box bg-gradient-success">
          <div class="inner">
            <h3 id="kpiPorcentaje"><?= esc($estadisticas['porcentaje_global']) ?>%</h3>
            <p>Cumplimiento Global</p>
          </div>
          <div class="icon"><i class="fas fa-percent"></i></div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="small-box bg-gradient-info">
          <div class="inner">
            <h3 id="kpiLogs"><?= esc($estadisticas['total_logs']) ?></h3>
            <p>Registros Realizados</p>
          </div>
          <div class="icon"><i class="fas fa-file-medical"></i></div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="small-box bg-gradient-primary">
          <div class="inner">
            <h3 id="kpiMinutos"><?= esc($estadisticas['total_minutos']) ?> <sup style="font-size: 20px">min</sup></h3>
            <p>Tiempo de Actividad Total</p>
          </div>
          <div class="icon"><i class="fas fa-clock"></i></div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="small-box bg-gradient-warning text-white">
          <div class="inner">
            <h3 id="kpiPromedio" class="text-white"><?= esc($estadisticas['promedio_minutos']) ?> <sup style="font-size: 20px">min</sup></h3>
            <p class="text-white">Promedio por Registro</p>
          </div>
          <div class="icon"><i class="fas fa-stopwatch text-white-50"></i></div>
        </div>
      </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
      <div class="col-md-6">
        <div class="card card-outline card-primary">
          <div class="card-header">
            <h3 class="card-title"><i class="fas fa-chart-pie mr-2"></i>Estado de Metas</h3>
          </div>
          <div class="card-body">
            <canvas id="patientMetasDonutChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card card-outline card-info">
          <div class="card-header">
            <h3 class="card-title"><i class="fas fa-chart-bar mr-2"></i>Cumplimiento por Categoría</h3>
          </div>
          <div class="card-body">
            <canvas id="categoryBarChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
          </div>
        </div>
      </div>
    </div>

    <!-- Historical Log Table -->
    <div class="card card-outline card-secondary">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-history mr-2"></i>Historial de Cumplimientos</h3>
      </div>
      <div class="card-body">
        <table id="historialTable" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>Fecha</th>
              <th>Categoría</th>
              <th>Meta / Tarea</th>
              <th>Duración</th>
              <th>Mi Comentario</th>
              <th>Evaluación Médica</th>
              <!-- Columna oculta para filtrar por plan -->
              <th class="d-none">plan_id</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($historial as $item): ?>
              <tr>
                <td><?= date('d/m/Y', strtotime($item['fecha'])) ?></td>
                <td>
                  <span class="badge badge-light border"><?= esc($item['tipo_meta_nombre']) ?></span>
                </td>
                <td><?= esc($item['meta_descripcion']) ?></td>
                <td><?= esc($item['duracion_minutos']) ?> minutos</td>
                <td>
                  <span class="text-muted"><?= esc($item['comentario'] ?: '-') ?></span>
                </td>
                <td>
                  <?php if (!empty($item['validado_at'])): ?>
                    <div class="mb-1">
                      <span class="badge badge-success"><i class="fas fa-check-circle mr-1"></i>Validado</span>
                      <?php if ($item['puntuacion'] !== null): ?>
                        <span class="badge badge-info">Puntaje: <?= esc($item['puntuacion']) ?>/5</span>
                      <?php endif; ?>
                    </div>
                    <?php if ($item['comentario_medico']): ?>
                      <small class="d-block text-muted"><strong>Dr. <?= esc($item['medico_nombre'] . ' ' . $item['medico_apellido']) ?>:</strong> <?= esc($item['comentario_medico']) ?></small>
                    <?php endif; ?>
                  <?php else: ?>
                    <span class="badge badge-warning"><i class="fas fa-clock mr-1"></i>Pendiente de revisión</span>
                  <?php endif; ?>
                </td>
                <!-- Valor oculto para filtro por plan -->
                <td class="d-none"><?= esc($item['plan_cuidado_id'] ?? '') ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

  </div>
</section>

<!-- Chart.js -->
<script src="<?= base_url('adminlte/plugins/chart.js/Chart.min.js'); ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

  // ── Datos desde PHP ────────────────────────────────────────────────────────
  const globalStats = <?= json_encode($estadisticas, JSON_UNESCAPED_UNICODE) ?>;
  const statsByPlan = <?= json_encode($estadisticasPorPlan, JSON_UNESCAPED_UNICODE) ?>;

  // ── DataTable ──────────────────────────────────────────────────────────────
  // Agregar filtro personalizado por plan ANTES de inicializar DataTable
  let activePlanFilter = 'all';

  $.fn.dataTable.ext.search.push(function (settings, data) {
    if (settings.nTable.id !== 'historialTable') return true;
    if (activePlanFilter === 'all') return true;
    // data[6] es la columna oculta plan_id (índice 6)
    return data[6] == activePlanFilter;
  });

  const historialDT = $('#historialTable').DataTable({
    responsive: true,
    lengthChange: true,
    autoWidth: false,
    columnDefs: [
      { targets: 6, visible: false, searchable: true }
    ],
    language: { url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json' }
  });

  // ── Gráficos ───────────────────────────────────────────────────────────────
  const donutCtx = document.getElementById('patientMetasDonutChart').getContext('2d');
  const donutChart = new Chart(donutCtx, {
    type: 'doughnut',
    data: {
      labels: ['Cumplidas', 'Pendientes'],
      datasets: [{
        data: [globalStats.metas_cumplidas, globalStats.metas_pendientes],
        backgroundColor: ['rgba(40,167,69,0.8)', 'rgba(220,53,69,0.8)'],
        borderColor:     ['rgba(40,167,69,1)',   'rgba(220,53,69,1)'],
        borderWidth: 1
      }]
    },
    options: { responsive: true, maintainAspectRatio: false }
  });

  const barCtx = document.getElementById('categoryBarChart').getContext('2d');
  const barChart = new Chart(barCtx, {
    type: 'bar',
    data: { labels: [], datasets: [{ label: '% de Cumplimiento', data: [], backgroundColor: 'rgba(23,162,184,0.7)', borderColor: 'rgba(23,162,184,1)', borderWidth: 1 }] },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: { yAxes: [{ ticks: { beginAtZero: true, max: 100 } }] }
    }
  });

  // ── Función que actualiza todo con un objeto de estadísticas ───────────────
  function applyStats(stats) {
    // KPIs
    document.getElementById('kpiPorcentaje').innerHTML = stats.porcentaje_global + '%';
    document.getElementById('kpiLogs').textContent      = stats.total_logs;
    document.getElementById('kpiMinutos').innerHTML     = stats.total_minutos + ' <sup style="font-size:20px">min</sup>';
    document.getElementById('kpiPromedio').innerHTML    = stats.promedio_minutos + ' <sup style="font-size:20px">min</sup>';

    // Donut
    donutChart.data.datasets[0].data = [stats.metas_cumplidas, stats.metas_pendientes];
    donutChart.update();

    // Bar por categoría
    const cats   = stats.categorias ? Object.keys(stats.categorias) : [];
    const porcs  = cats.map(c => {
      const d = stats.categorias[c];
      return d.total > 0 ? Math.round((d.cumplidas / d.total) * 1000) / 10 : 0;
    });
    barChart.data.labels                    = cats;
    barChart.data.datasets[0].data          = porcs;
    barChart.update();
  }

  // Inicializar con estadísticas globales
  applyStats(globalStats);

  // ── Selector de plan ───────────────────────────────────────────────────────
  const planSelect = document.getElementById('planSelect');
  if (planSelect) {
    planSelect.addEventListener('change', function () {
      const val = this.value;
      activePlanFilter = val;

      if (val === 'all') {
        applyStats(globalStats);
      } else {
        const planStats = statsByPlan[val];
        if (planStats) {
          applyStats(planStats);
        }
      }

      // Redibujar DataTable con el filtro activo
      historialDT.draw();
    });
  }
});
</script>
