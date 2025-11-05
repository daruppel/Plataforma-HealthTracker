        </div> <!-- /.container-fluid -->
      </section> <!-- /.content -->
    </div> <!-- /.content-wrapper -->

    <footer class="main-footer text-center">
      <strong>HealthTracker v2 © 2025</strong> — Proyecto de Software UNRN.
    </footer>
</div> <!-- ./wrapper -->

<!-- JS -->
  <!-- jQuery -->
  <script src="<?= base_url('adminlte/plugins/jquery/jquery.min.js'); ?>"></script>
  <!-- Bootstrap 4 -->
  <script src="<?= base_url('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
  <!-- overlayScrollbars -->
  <script src="<?= base_url('adminlte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js'); ?>"></script>
  <!-- AdminLTE App -->
  <script src="<?= base_url('adminlte/dist/js/adminlte.min.js'); ?>"></script>

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



</body>
</html>