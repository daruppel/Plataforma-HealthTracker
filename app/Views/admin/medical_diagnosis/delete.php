<div class="modal fade" id="modalDeleteDiagnosis" tabindex="-1" aria-labelledby="logoutLabel" aria-hidden="true">    
    <div class="modal-dialog">
      <div class="modal-content">
        
        <div class="modal-header">
          <h5 class="modal-title" id="logoutLabel">Eliminar tipo de diagnostico</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <form action="<?= base_url('staff/medical-diagnosis/delete'); ?>" method="post">
        <div class="modal-body">
          <p>¿Está seguro de que desea el tipo de diagnostico <b id="deleteDiagnosisName"></b>?</p>
          <input type="hidden" name="medical_diagnosis_id" id="delete_diagnosis_id">
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-danger">Eliminar</button>
        </div>
      </form>

      </div>
    </div>
  </div>