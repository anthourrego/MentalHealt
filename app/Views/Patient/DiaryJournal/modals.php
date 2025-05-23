<!-- Modal para Nueva Entrada de Diario -->
<div class="modal fade" id="diaryEntryModal" tabindex="-1" aria-labelledby="diaryEntryModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="diaryEntryModalLabel">Nueva Entrada de Diario</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formDiaryEntry" class="formValid" enctype="multipart/form-data">
          <input type="hidden" id="diaryEntryId" name="diaryEntryId">
          
          <div class="row mb-4">
            <div class="col-6">
              <div class="form-group">
                <div class="input-group date" id="dateDiary" data-target-input="nearest">
                  <input type="text" <?= (isset($therapistMode) && $therapistMode == 1) ? "disabled" : "" ?> name="dateDiary" class="form-control datetimepicker-input" required data-target="#dateDiary"/>
                  <div class="input-group-append" data-target="#dateDiary" data-toggle="datetimepicker">
                    <div class="input-group-text"><i class="fa-solid fa-calendar"></i></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <div class="input-group date" id="hourDiary" data-target-input="nearest">
                  <input type="text" <?= (isset($therapistMode) && $therapistMode == 1) ? "disabled" : "" ?> name="hourDiary" class="form-control datetimepicker-input" required data-target="#hourDiary"/>
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
                    <input type="radio" <?= (isset($therapistMode) && $therapistMode == 1) ? "disabled" : "" ?> name="mood" id="mood1" value="1" required>
                    <i class="fa-regular fa-face-tired"></i>
                  </label>
                  <label class="btn btn-outline-warning" title="Mal">
                    <input type="radio" <?= (isset($therapistMode) && $therapistMode == 1) ? "disabled" : "" ?> name="mood" id="mood2" value="2">
                    <i class="fa-regular fa-face-frown"></i>
                  </label>
                  <label class="btn btn-outline-info" title="Regular">
                    <input type="radio" <?= (isset($therapistMode) && $therapistMode == 1) ? "disabled" : "" ?> name="mood" id="mood3" value="3" checked>
                    <i class="fa-regular fa-face-meh"></i>
                  </label>
                  <label class="btn btn-outline-primary" title="Bien">
                    <input type="radio" <?= (isset($therapistMode) && $therapistMode == 1) ? "disabled" : "" ?> name="mood" id="mood4" value="4">
                    <i class="fa-regular fa-face-smile"></i>
                  </label>
                  <label class="btn btn-outline-success" title="Muy bien">
                    <input type="radio" <?= (isset($therapistMode) && $therapistMode == 1) ? "disabled" : "" ?> name="mood" id="mood5" value="5">
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
                <textarea class="form-control" <?= (isset($therapistMode) && $therapistMode == 1) ? "disabled" : "" ?> id="diaryContent" name="content" rows="5" required minlength="1" maxlength="1000" placeholder="Describe cómo te sientes, qué pensamientos has tenido hoy, situaciones importantes..."></textarea>
              </div>
            </div>
          </div>
          <div class="col-12">
            <div class="form-group <?= (isset($therapistMode) && $therapistMode == 1) ? "d-none" : "" ?>">
              <input class="form-check-input" type="checkbox" id="private_entry" name="private_entry" value="1" data-on-text="Si" data-off-text="No">
              <label class="form-check-label" for="private_entry"> Privada (solo visible para mí)</label>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <?php if(!isset($therapistMode) || $therapistMode == 0) { ?>
        <button type="submit" class="btn btn-success" form="formDiaryEntry"><i class="fas fa-save"></i> Guardar</button>
        <?php } ?>
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Cancelar</button>
      </div>
    </div>
  </div>
</div>