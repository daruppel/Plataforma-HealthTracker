        </div> <!-- /.container-fluid -->
      </section> <!-- /.content -->
    </div> <!-- /.content-wrapper -->

    <footer class="main-footer text-center">
      <strong>HealthTracker © 2025</strong> — Proyecto de Software UNRN.
    </footer>
</div> <!-- ./wrapper -->

<!-- JS -->
  

  <!-- Bootstrap 4 -->
  <script src="<?= base_url('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>

  <!-- DataTables -->
  <script src="<?= base_url('adminlte/plugins/datatables/jquery.dataTables.min.js'); ?>"></script>
  <script src="<?= base_url('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js'); ?>"></script>
  <script src="<?= base_url('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js'); ?>"></script>
  <script src="<?= base_url('adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js'); ?>"></script>
  <script src="<?= base_url('adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js'); ?>"></script>
  <script src="<?= base_url('adminlte/plugins/jszip/jszip.min.js'); ?>"></script>
  <script src="<?= base_url('adminlte/plugins/pdfmake/pdfmake.min.js'); ?>"></script>
  <script src="<?= base_url('adminlte/plugins/pdfmake/vfs_fonts.js'); ?>"></script>
  <script src="<?= base_url('adminlte/plugins/datatables-buttons/js/buttons.html5.min.js'); ?>"></script>
  <script src="<?= base_url('adminlte/plugins/datatables-buttons/js/buttons.print.min.js'); ?>"></script>
  <script src="<?= base_url('adminlte/plugins/datatables-buttons/js/buttons.colVis.min.js'); ?>"></script>

  <!-- AdminLTE -->
  <script src="<?= base_url('adminlte/dist/js/adminlte.min.js'); ?>"></script>


  <script>
  $(function () {
    $(".datatable").DataTable({
      "responsive": true,
      "lengthChange": true,
      "autoWidth": false,
      "language": {
        "url": "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json"
      } 
    });
  });
</script>


  <!-- Logout Modal -->
  <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        
        <div class="modal-header">
          <h5 class="modal-title" id="logoutLabel">Cerrar sesión</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          ¿Estás seguro que querés cerrar tu sesión?
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <a href="<?= base_url('logout'); ?>" class="btn btn-danger">Cerrar sesión</a>
        </div>

      </div>
    </div>
  </div>

  <?php if (session()->get('success')): ?>
  <script>
  $(document).Toasts('create', {
      class: 'bg-success',
      title: 'Éxito',
      body: '<?= session()->get("success") ?>',
      autohide: true,
      delay: 3000,
      icon: 'fas fa-check'
  });
  </script>
  <?php endif; ?>

  <?php if (session()->get('error')): ?>
  <script>
  $(document).Toasts('create', {
      class: 'bg-danger',
      title: 'Error',
      body: '<?= session()->get("error") ?>',
      autohide: true,
      delay: 4000,
      icon: 'fas fa-exclamation-triangle'
  });
  </script>
  <?php endif; ?>

  <?php if (session()->get('errors')): ?>
  <script>
  $(document).Toasts('create', {
      class: 'bg-warning',
      title: 'Errores de validación',
      body: `<?php foreach(session()->get('errors') as $e){ echo "- $e<br>"; } ?>`,
      autohide: false,
      icon: 'fas fa-bug'
  });
  </script>
  <?php endif; ?>



</body>
</html>