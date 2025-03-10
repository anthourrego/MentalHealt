<div class="container-fluid position-absolute">
  <div class="row no-gutter">
    <div class="d-none d-lg-flex col-lg-6 p-0 login-background">
      <img class="w-100 vh-100" src="<?= base_url('assets/img/login.webp') ?>">
    </div>
    <div class="col-12 col-lg-6 overflow-auto" style="max-height: 100vh !important">
      <div class="login d-flex align-items-center py-5">
        <div class="container">
          <div class="row">
            <!-- Formulario de Login -->
            <div id="contentLogin" class="col-12 col-md-10 col-xl-7 mx-auto">
              <div class="text-center">
                <img class="w-50 mb-5" src="<?= base_url('assets/img/noPhoto.png') ?>">
              </div>
              <form id="loginForm" class="formValid" autocomplete="off">
                <div class="form-label-group form-valid">
                  <input id="email" name="email" type="email" value="anthourrego@gmail.com" minlength="5" class="form-control" placeholder="Correo" required autocomplete="off">
                  <label for="email">Correo</label>
                </div>
                <div class="form-label-group form-valid">
                  <div class="input-group">
                    <input type="password" id="password" name="password" value="123456" minlength="8" class="form-control" placeholder="Contraseña" required autocomplete="off">
                    <label for="password">Contraseña</label>
                    <div class="input-group-append">
                      <button class="btn btn-outline-secondary btn-pass" type="button">
                        <i class="fas fa-eye"></i>
                      </button>
                    </div>
                  </div>
                </div>
                <button type="submit" id="submitBtn" class="btn btn-lg bg-primary btn-block btn-login text-white text-uppercase font-weight-bold mb-2">
                  Ingresar <i class="fas fa-sign-in-alt"></i>
                </button>
              </form>
              <p class="mt-5 mb-3 text-muted text-center"><?= date('Y'); ?> &copy; Versión <?= VERSION ?></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>