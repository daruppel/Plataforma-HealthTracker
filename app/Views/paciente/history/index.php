<section class="content">
    <div class="container-fluid">

        <div class="card card-primary">

            <div class="card-header">
                <h3 class="card-title">
                    Historial de Planes de Cuidado
                </h3>
            </div>

            <div class="card-body">

                <?php if (empty($history)): ?>

                    <div class="alert alert-info">
                        No posee diagnósticos registrados.
                    </div>

                <?php else: ?>

                    <table class="table table-bordered table-striped datatable">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Diagnóstico</th>
                                <th>Profesional</th>
                                <th>Estado</th>
                                <th>Acción</th>
                            </tr>
                        </thead>

                        <tbody>

                        <?php foreach ($history as $diagnosis): ?>

                            <tr>

                                <td>
                                    <?= date('d/m/Y', strtotime($diagnosis['fecha'])) ?>
                                </td>

                                <td>
                                    <?= esc($diagnosis['tipo_diagnostico']) ?>
                                </td>

                                <td>
                                    <?= esc($diagnosis['medico_nombre'] . ' ' . $diagnosis['medico_apellido']) ?>
                                </td>

                                <td>

                                    <?php if ($diagnosis['estado'] == 'Activo'): ?>

                                        <span class="badge badge-success">
                                            <?= esc($diagnosis['estado']) ?>
                                        </span>

                                    <?php else: ?>

                                        <span class="badge badge-secondary">
                                            <?= esc($diagnosis['estado']) ?>
                                        </span>

                                    <?php endif; ?>

                                </td>

                                <td>

                                    <a href="<?= base_url('paciente/care-plan-history/' . $diagnosis['diagnostico_id']) ?>"
                                    class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye"></i> Ver Plan
                                    </a>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                        </tbody>

                    </table>

                <?php endif; ?>

            </div>

        </div>

    </div>
</section>