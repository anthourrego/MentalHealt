<div class="card">
  <div class="card-header">
    <div class="row justify-content-between">
      <div class="col-8 col-md-3">
        <div class="input-group">
          <div class="input-group-prepend">
            <label class="input-group-text" for="selectStatus">Estado</label>
          </div>
          <select class="custom-select" id="selectStatus">
            <option selected value="1">Activo</option>
            <option value="0">Inactivo</option>
            <option value="-1">Todos</option>
          </select>
        </div>
      </div>
      <div class="col-4 col-md-3 text-right">
        <button type="button" class="btn btn-primary" id="btnCreateUser"><i class="fa-solid fa-user-plus"></i> Crear</button>
      </div>
    </div>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table id="tblUser" class="table table-sm table-striped table-hover table-bordered w-100">
        <thead> 
          <tr>
            <th>Foto</th>
            <th>Nombre</th>
            <th>Apellidos</th>
            <th>Correo</th>
            <th>Perfil</th>
            <th>Estado</th>
            <th>Email Confirmado</th>
            <th>Ultimo Login</th>
            <th>Fecha Creación</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>
</div>

<div class="modal fade modalFormulario" id="modalUser" data-backdrop="static" data-keyboard="false" aria-labelledby="modalUserLabel">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalUserLabel"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formUser" enctype="multipart/form-data">
          <input type="hidden" name="userId" id="userId">
          <div class="form-row">
            <div class="col-12">
              <div class="form-group form-valid">
                <label class="mb-0" for="email">Correo <span class="text-danger">*</span></label>
                <input placeholder="Correo" class="form-control" id="email" name="email" type="email" minlength="3" maxlength="255" required autocomplete="off">
              </div>
            </div>
            <div class="col-6">
              <div class="form-group form-valid">
                <label class="mb-0" for="first_name">Nombres <span class="text-danger">*</span></label>
                <input placeholder="Nombres" class="form-control onlySpaceLetters" id="first_name" name="first_name" type="text" minlength="3" maxlength="255" required autocomplete="off">
              </div>
            </div>
            <div class="col-6">
              <div class="form-group form-valid">
                <label class="mb-0" for="last_name">Apellidos <span class="text-danger">*</span></label>
                <input type="text" id="last_name" name="last_name" class="form-control onlySpaceLetters" minlength="1" maxlength="300" required placeholder="Apellidos" autocomplete="off">
              </div>
            </div>
            <div class="col-6 form-group form-valid">
              <label class="mb-0" for="profile">Perfil</label>
              <select class="custom-select" id="profile">
                <option selected value="1">Administrador</option>
                <option value="2">Terapeuta</option>
                <option value="3">Paciente</option>
              </select>
            </div>
            <div class="col-6 form-group form-group-edit">
              <label class="mb-0" for="estado">Estado</label>
              <input class="form-control" id="estado" disabled>
            </div>
            <div class="col-6 form-group form-group-edit">
              <label class="mb-0" for="fechaLog">Ultimo login</label>
              <input class="form-control" id="fechaLog" disabled>
            </div>
            <div class="col-6 form-group form-group-edit">
              <label class="mb-0" for="fechaMod">Fecha modificación</label>
              <input class="form-control" id="fechaMod" disabled>
            </div>
            <div class="col-6 form-group form-group-edit">
              <label class="mb-0" for="fechaCre">Fecha creación</label>
              <input class="form-control" id="fechaCre" disabled>
            </div>
          </div>
          <div class="form-row">
            <div class="col-6 form-group form-valid">
              <label class="mb-0" for="pass">Contraseña <span class="text-danger">*</span></label>
              <div class="input-group">
                <input type="password" required id="pass" placeholder="******" minlength="1" maxlength="255" name="pass" class="form-control onlyLetters" autocomplete="off">
                <div class="input-group-append">
                  <button class="btn btn-secondary btn-pass" type="button"><i class="fas fa-eye"></i></button>
                </div>
              </div>
            </div>
            <div class="col-6 form-group form-valid">
              <label class="mb-0" for="RePass">Confirmar Contraseña <span class="text-danger">*</span></label>
              <div class="input-group">
                <input type="password" required id="RePass" placeholder="******" minlength="1" maxlength="255" name="RePass" class="form-control onlyLetters" autocomplete="off">
                <div class="input-group-append">
                  <button class="btn btn-secondary btn-pass" type="button"><i class="fas fa-eye"></i></button>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success" form="formUser"><i class="fas fa-save"></i> Guardar</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Cerrar</button>
      </div>
    </div>
  </div>
</div>