<section class="content">
  <div class="container-fluid">

    <div class="row">
      <div class="col-md-3">
        <div class="small-box bg-info">
          <div class="inner">
            <h3><?= esc($summary['total_pacientes']) ?></h3>
            <p>Pacientes con seguimiento</p>
          </div>
          <div class="icon">
            <i class="fas fa-users"></i>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="small-box bg-primary">
          <div class="inner">
            <h3><?= esc($summary['total_metas']) ?></h3>
            <p>Metas totales</p>
          </div>
          <div class="icon">
            <i class="fas fa-tasks"></i>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="small-box bg-success">
          <div class="inner">
            <h3><?= esc($summary['metas_cumplidas']) ?></h3>
            <p>Metas cumplidas</p>
          </div>
          <div class="icon">
            <i class="fas fa-check-circle"></i>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="small-box bg-warning">
          <div class="inner">
            <h3><?= esc($summary['porcentaje_global']) ?>%</h3>
            <p>Cumplimiento global</p>
          </div>
          <div class="icon">
            <i class="fas fa-chart-line"></i>
          </div>
        </div>
      </div>
    </div>

    <?php if (empty($patients)): ?>
      <div class="card">
        <div class="card-body">
          <p class="mb-0">No hay datos suficientes para mostrar estadísticas de cumplimiento.</p>
        </div>
      </div>
    <?php else: ?>

      <div class="card card-outline card-info">
        <div class="card-header">
          <h3 class="card-title">Seleccionar paciente</h3>
        </div>
        <div class="card-body">
          <div class="form-group mb-0">
            <label for="patientSelect">Paciente</label>
            <select id="patientSelect" class="form-control">
              <?php foreach ($patients as $index => $patient): ?>
                <option value="<?= esc($index) ?>">
                  <?= esc($patient['paciente']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-8">
          <div class="card card-outline card-primary">
            <div class="card-header">
              <h3 class="card-title">Cumplimiento por paciente</h3>
            </div>
            <div class="card-body">
              <canvas id="complianceBarChart" style="min-height: 320px;"></canvas>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card card-outline card-success">
            <div class="card-header">
              <h3 class="card-title">Cumplidas vs pendientes</h3>
            </div>
            <div class="card-body">
              <canvas id="complianceDonutChart" style="min-height: 320px;"></canvas>
            </div>
          </div>
        </div>
      </div>

      <div class="card card-outline card-secondary">
        <div class="card-header">
          <h3 class="card-title">Detalle por paciente</h3>
        </div>
        <div class="card-body">
          <table class="table table-bordered table-striped datatable">
            <thead>
              <tr>
                <th>Paciente</th>
                <th>Diagnósticos</th>
                <th>Planes</th>
                <th>Metas cumplidas</th>
                <th>Metas pendientes</th>
                <th>Total metas</th>
                <th>% cumplimiento</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($patients as $patient): ?>
                <tr>
                  <td><?= esc($patient['paciente']) ?></td>
                  <td><?= esc($patient['cantidad_diagnosticos']) ?></td>
                  <td><?= esc($patient['cantidad_planes']) ?></td>
                  <td><?= esc($patient['metas_cumplidas']) ?></td>
                  <td><?= esc($patient['metas_pendientes']) ?></td>
                  <td><?= esc($patient['total_metas']) ?></td>
                  <td><?= esc(number_format((float) $patient['porcentaje_cumplimiento'], 2)) ?>%</td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>

    <?php endif; ?>

  </div>
</section>

<script src="<?= base_url('adminlte/plugins/chart.js/Chart.min.js'); ?>"></script>

<?php if (!empty($patients)): ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const patients = <?= json_encode($patients, JSON_UNESCAPED_UNICODE) ?>;
  const patientSelect = document.getElementById('patientSelect');

  const barCtx = document.getElementById('complianceBarChart').getContext('2d');
  const complianceChart = new Chart(barCtx, {
    type: 'bar',
    data: {
      labels: ['Cumplimiento'],
      datasets: [{
        label: '% de cumplimiento',
        data: [0],
        backgroundColor: 'rgba(54, 162, 235, 0.7)',
        borderColor: 'rgba(54, 162, 235, 1)',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        yAxes: [{
          ticks: {
            beginAtZero: true,
            max: 100
          }
        }]
      }
    }
  });

  const donutCtx = document.getElementById('complianceDonutChart').getContext('2d');
  const donutChart = new Chart(donutCtx, {
    type: 'doughnut',
    data: {
      labels: ['Cumplidas', 'Pendientes'],
      datasets: [{
        data: [0, 0],
        backgroundColor: [
          'rgba(40, 167, 69, 0.8)',
          'rgba(255, 193, 7, 0.8)'
        ],
        borderColor: [
          'rgba(40, 167, 69, 1)',
          'rgba(255, 193, 7, 1)'
        ],
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false
    }
  });

  function updateCharts(patientIndex) {
    const patient = patients[patientIndex];

    if (!patient) {
      return;
    }

    complianceChart.data.datasets[0].data = [patient.porcentaje_cumplimiento];
    complianceChart.data.datasets[0].label = '% de cumplimiento - ' + patient.paciente;
    complianceChart.update();

    donutChart.data.datasets[0].data = [
      patient.metas_cumplidas,
      patient.metas_pendientes
    ];
    donutChart.update();
  }

  patientSelect.addEventListener('change', function () {
    updateCharts(this.value);
  });

  updateCharts(patientSelect.value);
});
</script>
<?php endif; ?>
