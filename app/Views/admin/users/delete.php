<div class="modal fade" id="modalDeleteUser" tabindex="-1" aria-labelledby="logoutLabel" aria-hidden="true">    
    <div class="modal-dialog">
      <div class="modal-content">
        
        <div class="modal-header">
          <h5 class="modal-title" id="logoutLabel">Eliminar usuario</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
            ¿Estás seguro de que deseas eliminar este usuario?
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <a href="<?= base_url(''); ?>" class="btn btn-danger"> Eliminar usuario</a>
        </div>

      </div>
    </div>
  </div>