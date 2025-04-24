<!-- Contenedor del calendario -->
<div class="row mb-4">
  <div class="col-8">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title"><i class="fa-solid fa-calendar"></i> Mi Calendario</h5>
      </div>
      <div class="card-body">
        <div id="calendar-loading" class="text-center" style="display: none;">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden"></span>
          </div>
        </div>
        <div id="calendar"></div>
      </div>
    </div>
  </div>
  <div class="col-4">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title"><i class="fa-solid fa-list"></i> Citas Pendientes</h5>
      </div>
      <div class="card-body">
        <div class="list-group" id="listAppoinment"></div>
      </div>
    </div>
  </div>
</div>

<!-- Modal para Ver Detalles de Cita -->
<div class="modal fade" id="appointmentDetailModal" tabindex="-1" aria-labelledby="appointmentDetailModalLabel">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="appointmentDetailModalLabel">Detalles de la Cita</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <dl class="row">
          <dt class="col-sm-4">Paciente:</dt>
          <dd class="col-sm-8" id="appointmentTherapist"></dd>
          
          <dt class="col-sm-4">Fecha y hora:</dt>
          <dd class="col-sm-8" id="appointmentDateTime"></dd>
          
          <dt class="col-sm-4">Modalidad:</dt>
          <dd class="col-sm-8" id="appointmentModality"></dd>
          
          <dt class="col-sm-4">Estado:</dt>
          <dd class="col-sm-8" id="appointmentStatus"></dd>
          
          <dt class="col-sm-4">Notas:</dt>
          <dd class="col-sm-8" id="appointmentNotes"></dd>

          <dt class="col-sm-4 appointmentNotesAdd">Notas de la cita:</dt>
          <dd class="col-sm-8 appointmentNotesAdd" id="appointmentNotesAdd"></dd>
        </dl>
        <form id="formCompleted" class="formValid" enctype="multipart/form-data">
          <input type="hidden" id="appointmentIdCompleted" name="appointmentId">
          <div class="row mb-3">
            <div class="col-md-12">
              <div class="mb-3">
                <label for="appointmentAnotation" class="form-label">Anotaciones:</label>
                <textarea class="form-control" id="appointmentAnotation" name="appointmentAnotation" rows="5" required minlength="1" maxlength="1000" placeholder=""></textarea>
              </div>
            </div>
          </div>
        </form>
        <form id="formConfirmed" class="formValid" enctype="multipart/form-data">
          <input type="hidden" id="appointmentIdConfirm" name="appointmentId">
          <div class="row mb-3">
            <div class="col-md-12">
              <div class="mb-3">
                <label for="urlVideo" class="form-label">URL Videollamada:</label>
                <textarea class="form-control" id="urlVideo" name="urlVideo" rows="2" required minlength="1" maxlength="250" placeholder=""></textarea>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <a id="btnJoinVideoCall" href="#" target="_blank" class="btn btn-info d-none">
          <i class="fas fa-video"></i> Unirse a la videollamada
        </a>
        <button type="submit" id="btnCompleted" form="formCompleted" class="btn btn-success"><i class="fa-solid fa-check"></i> Completada</button>
        <button type="submit" id="btnConfirmed" form="formConfirmed" class="btn btn-success"><i class="fa-solid fa-check"></i> Confirmado</button>
        <button type="submit" id="btnNotes" class="btn btn-primary"><i class="fa-regular fa-address-book"></i> Ver Notas</button>
        <button type="button" id="btnNoPresented" class="btn btn-secondary"><i class="fa-solid fa-users-slash"></i> No Presentado</button>
        <button type="button" id="btnCancelAppointment" class="btn btn-danger"><i class="fa-regular fa-calendar-xmark"></i> Cancelar Cita</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Cerrar</button>
      </div>
    </div>
  </div>
</div>