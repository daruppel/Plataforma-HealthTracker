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
                  <td><?= esc($patient['porcentaje_cumplimiento']) ?>%</td>
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
  const labels = <?= json_encode($chart['labels']) ?>;
  const compliance = <?= json_encode($chart['compliance']) ?>;
  const donut = <?= json_encode($chart['donut']) ?>;

  const barCtx = document.getElementById('complianceBarChart').getContext('2d');
  new Chart(barCtx, {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [{
        label: '% de cumplimiento',
        data: compliance,
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
  new Chart(donutCtx, {
    type: 'doughnut',
    data: {
      labels: ['Cumplidas', 'Pendientes'],
      datasets: [{
        data: donut,
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
});
</script>
<?php endif; ?>
