<div class="col-12">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <button class="btn btn-primary ml-auto" id="btnNewDiaryEntry">
        <i class="fas fa-plus"></i> Nueva entrada
      </button>
    </div>
    <div class="card-body">
      <div id="diary-loading" class="text-center" style="display: none;">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden"></span>
        </div>
      </div>
      
      <div id="diary-empty" class="text-center p-4">
        <i class="fas fa-book fa-3x text-muted mb-3"></i>
        <p class="lead">Aún no has creado entradas en tu diario</p>
        <p class="text-muted">Registra tus emociones y pensamientos para llevar un seguimiento de tu bienestar emocional</p>
      </div>
      
      <div id="diary-list" class="row row-cols-1 row-cols-md-2 row-cols-xl-3"></div>
    </div>
  </div>
</div>

<!-- Modal para Nueva Entrada de Diario -->
<div class="modal fade" id="diaryEntryModal" tabindex="-1" aria-labelledby="diaryEntryModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="diaryEntryModalLabel">Nueva Entrada de Diario</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formDiaryEntry" class="formValid" enctype="multipart/form-data">
          <input type="hidden" id="diaryEntryId" name="id">
          
          <div class="row mb-4">
            <div class="col-6">
              <div class="form-group">
                <div class="input-group date" id="dateDiary" data-target-input="nearest">
                  <input type="text" name="dateDiary" class="form-control datetimepicker-input" required data-target="#dateDiary"/>
                  <div class="input-group-append" data-target="#dateDiary" data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="fa-solid fa-calendar"></i></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <div class="input-group date" id="hourDiary" data-target-input="nearest">
                  <input type="text" name="hourDiary" class="form-control datetimepicker-input" required data-target="#hourDiary"/>
                  <div class="input-group-append" data-target="#hourDiary" data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="fa-solid fa-clock"></i></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-12 text-center">
              <label class="form-label d-block">¿Cómo te sientes hoy?</label>
                <div class="mood-selector mb-3">
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                  <label class="btn btn-outline-danger" title="Muy mal">
                    <input type="radio" name="mood" id="mood1" value="1" required>
                    <i class="fa-regular fa-face-tired"></i>
                  </label>
                  <label class="btn btn-outline-danger" title="Mal">
                    <input type="radio" name="mood" id="mood2" value="2">
                    <i class="fa-regular fa-face-frown"></i>
                  </label>
                  <label class="btn btn-outline-warning" title="Regular">
                    <input type="radio" name="mood" id="mood3" value="3" checked>
                    <i class="fa-regular fa-face-meh"></i>
                  </label>
                  <label class="btn btn-outline-success" title="Bien">
                    <input type="radio" name="mood" id="mood4" value="4">
                    <i class="fa-regular fa-face-smile"></i>
                  </label>
                  <label class="btn btn-outline-success" title="Muy bien">
                    <input type="radio" name="mood" id="mood5" value="5">
                    <i class="fa-regular fa-face-laugh-beam"></i>
                  </label>
                </div>
                </div>
            </div>
          </div>
          
          <div class="row mb-3">
            <div class="col-md-12">
              <div class="mb-3">
                <label for="diaryContent" class="form-label">¿Qué te gustaría compartir hoy?</label>
                <textarea class="form-control" id="diaryContent" name="content" rows="5" required minlength="1" maxlength="500" placeholder="Describe cómo te sientes, qué pensamientos has tenido hoy, situaciones importantes..."></textarea>
              </div>
            </div>
          </div>
          <div class="col-12">
            <div class="form-group">
              <input class="form-check-input" type="checkbox" id="private_entry" name="private_entry" value="1" data-on-text="Si" data-off-text="No">
              <label class="form-check-label" for="private_entry"> Privada (solo visible para mí)</label>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success" form="formDiaryEntry"><i class="fas fa-save"></i> Guardar</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Cancelar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal para Ver Entrada de Diario -->
<div class="modal fade" id="viewDiaryEntryModal" tabindex="-1" aria-labelledby="viewDiaryEntryModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewDiaryEntryModalLabel">Entrada del Diario</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="text-center mb-3">
          <span id="viewEntryDate" class="text-muted"></span>
          <div class="mt-2" id="viewEntryMood"></div>
        </div>
        
        <div class="card mb-3">
          <div class="card-body">
            <p id="viewEntryContent"></p>
          </div>
        </div>
        
        <div class="row mb-3">
          <div class="col-12">
            <label class="form-label">Emociones:</label>
            <div id="viewEntryEmotions" class="d-flex flex-wrap gap-1"></div>
          </div>
        </div>
        
        <div class="row">
          <div class="col-6">
            <p class="mb-1"><strong>Horas de sueño:</strong> <span id="viewEntrySleep"></span></p>
            <p class="mb-1"><strong>Ansiedad:</strong> <span id="viewEntryAnxiety"></span></p>
            <p class="mb-1"><strong>Estrés:</strong> <span id="viewEntryStress"></span></p>
          </div>
          <div class="col-6">
            <p class="mb-1"><strong>Actividad física:</strong> <span id="viewEntryActivity"></span></p>
            <p class="mb-1"><strong>Medicación:</strong> <span id="viewEntryMedication"></span></p>
            <p class="mb-1"><strong>Entrada privada:</strong> <span id="viewEntryPrivate"></span></p>
          </div>
        </div>
        
        <div id="therapistFeedbackContainer" class="mt-4 border-top pt-3 d-none">
          <h6>Comentarios de tu terapeuta:</h6>
          <div id="therapistFeedbackContent" class="p-3 bg-light rounded"></div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" id="btnEditEntry" class="btn btn-primary">Editar</button>
        <button type="button" id="btnDeleteEntry" class="btn btn-danger">Eliminar</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>