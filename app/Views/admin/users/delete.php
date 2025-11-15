<div class="modal fade" id="modalDeleteUser" tabindex="-1" aria-labelledby="logoutLabel" aria-hidden="true">    
    <div class="modal-dialog">
      <div class="modal-content">
        
        <div class="modal-header">
          <h5 class="modal-title" id="logoutLabel">Eliminar usuario</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <form action="<?= base_url('admin/users/delete'); ?>" method="post">
        <div class="modal-body">
          <p>¿Está seguro de que desea eliminar al usuario <b id="deleteUserName"></b>?</p>
          <input type="hidden" name="user_id" id="delete_user_id">
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-danger">Eliminar</button>
        </div>
      </form>

      </div>
    </div>
  </div>