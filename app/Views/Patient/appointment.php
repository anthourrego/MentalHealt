<!-- Contenedor del calendario -->
<div class="row mb-4">
  <div class="col-8">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title">Mi Calendario</h5>
      </div>
      <div class="card-body">
        <div id="calendar-loading" class="text-center" style="display: none;">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Cargando...</span>
          </div>
        </div>
        <div id="calendar"></div>
      </div>
    </div>
  </div>
  <div class="col-4">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title">Mis Citas</h5>
      </div>
      <div class="card-body"></div>
    </div>
  </div>
  <div class="col-12">
    <?= view("Patient/DiaryJournal/card.php"); ?>
  </div>
</div>

<!-- Modal para Crear Cita -->
<div class="modal fade" id="appointmentModal" tabindex="-1" aria-labelledby="appointmentModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="appointmentModalLabel">Agendar Nueva Cita</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="formCreateAppointment">
        <div class="modal-body">
          <input type="hidden" id="appointmentDate" name="date">
          
          <div class="mb-3">
            <label for="therapistSelect" class="form-label">Terapeuta</label>
            <select class="form-select" id="therapistSelect" name="therapist_id" required>
              <option value="">Seleccione un terapeuta...</option>
            </select>
          </div>
          
          <div class="mb-3">
            <label for="timeSelect" class="form-label">Horario</label>
            <select class="form-select" id="timeSelect" name="start_time" required disabled>
              <option value="">Seleccione un horario...</option>
            </select>
          </div>
          
          <div class="mb-3">
            <label for="modalitySelect" class="form-label">Modalidad</label>
            <select class="form-select" id="modalitySelect" name="modality" required>
              <option value="in_person">Presencial</option>
              <option value="video_call">Videollamada</option>
              <option value="phone_call">Llamada telefónica</option>
            </select>
          </div>
          
          <div class="mb-3">
            <label for="reasonText" class="form-label">Motivo de la consulta</label>
            <textarea class="form-control" id="reasonText" name="reason" rows="3" required></textarea>
          </div>
          
          <div class="mb-3">
            <label for="notesText" class="form-label">Notas adicionales</label>
            <textarea class="form-control" id="notesText" name="notes" rows="2"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary">Agendar Cita</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal para Ver Detalles de Cita -->
<div class="modal fade" id="appointmentDetailModal" tabindex="-1" aria-labelledby="appointmentDetailModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="appointmentDetailModalLabel">Detalles de la Cita</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <dl class="row">
          <dt class="col-sm-4">Terapeuta:</dt>
          <dd class="col-sm-8" id="appointmentTherapist"></dd>
          
          <dt class="col-sm-4">Fecha y hora:</dt>
          <dd class="col-sm-8" id="appointmentDateTime"></dd>
          
          <dt class="col-sm-4">Modalidad:</dt>
          <dd class="col-sm-8" id="appointmentModality"></dd>
          
          <dt class="col-sm-4">Estado:</dt>
          <dd class="col-sm-8" id="appointmentStatus"></dd>
          
          <dt class="col-sm-4">Notas:</dt>
          <dd class="col-sm-8" id="appointmentNotes"></dd>
        </dl>
      </div>
      <div class="modal-footer">
        <a id="btnJoinVideoCall" href="#" target="_blank" class="btn btn-success d-none">
          <i class="fas fa-video"></i> Unirse a la videollamada
        </a>
        <button type="button" id="btnCancelAppointment" class="btn btn-danger">Cancelar Cita</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<?= view("Patient/DiaryJournal/modals.php"); ?>