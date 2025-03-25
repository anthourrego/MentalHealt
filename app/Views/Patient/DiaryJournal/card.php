<div class="col-12">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <?php if (isset($dashboard) && $dashboard === true) { ?>
      <h5 class="card-title">Mi Diario</h5>
      <?php } ?>
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
    <?php if (isset($dashboard) && $dashboard === true) { ?>
    <div class="card-footer text-center">
      <a href="<?= base_url('patient/diary') ?>" class="btn btn-outline-secondary">
        <i class="fas fa-list"></i> Ver todas las entradas
      </a>
    </div>
    <?php } ?>
  </div>
</div>