const generalBase = routeBase + "admin/Users/";
const selectStatus = document.getElementById('selectStatus');
const btnCreateUser = document.getElementById('btnCreateUser');
const btnPassEye = document.querySelectorAll('.btn-pass');
const formUser = document.getElementById('formUser');
const formPass = document.getElementById('formPass');
const inputUserId = document.getElementById('userId');
const inputEmail = document.getElementById('email');
const inputFirstName = document.getElementById('first_name');
const inputLastName = document.getElementById('last_name');
const inputProfile = document.getElementById('profile');
const inputStatus = document.getElementById('status');
const inputEmailConfirm = document.getElementById('email_confirm');
const inputPass = document.getElementById('pass');
const inputRePass = document.getElementById('RePass');
const inputDateLog = document.getElementById('dateLog');
const inputDateMod = document.getElementById('dateMod');
const inputDateCre = document.getElementById('dateCre');
const modalUserLabel = document.getElementById('modalUserLabel');

const DTUser = $("#tblUser").DataTable({
  ajax: {
    url: generalBase + "DT",
    type: "POST",
    data: function (d) {
      return $.extend(d, { "status": selectStatus.value })
    }
  },
  order: [[1, "asc"]],
  columns: [
    {
      orderable: false,
      defaultContent: '',
      className: "text-center",
      render: function (data, type, row) {
        const routeImg = `${generalBase}Photo/${row.id}.png`;
        return `<a href="${routeImg}" data-fancybox="images${row.id}" data-caption="${row.full_name}">
                  <img class="img-thumbnail" src="${routeImg}" alt="${row.full_name}" />
                </a>`;
      }
    },
    { data: 'first_name' },
    { data: 'last_name' },
    { data: 'email' },
    { data: 'profileDesc' },
    { data: 'statusDesc' },
    { data: 'email_confirmDesc' },
    {
      data: 'last_login',
      render: function (data) {
        return moment(data, "YYYY-MM-DD HH:mm:ss").format("DD/MM/YYYY hh:mm:ss A");
      }
    },
    {
      data: 'created_at',
      render: function (data) {
        return moment(data, "YYYY-MM-DD HH:mm:ss").format("DD/MM/YYYY hh:mm:ss A");
      }
    },
    {
      orderable: false,
      searchable: false,
      defaultContent: '',
      className: 'text-center noExport',
      render: function (data, type, row) {

        btnEdit = '<button type="button" class="btn btn-secondary btnEdit" title="Editar"><i class="fa-solid fa-user-pen"></i></button>';
        btnResetPass = '<button type="button" class="btn btn-dark btnChangePass" title="Cambiar Contraseña"><i class="fa-solid fa-user-lock"></i></button>';
        btnChangeStatus = `<button type="button" class="btn btn-${row.status == "1" ? "warning" : "success"} btnChangeStatus" title="${row.status == "1" ? "Ina" : "A"}ctivar"><i class="fa-solid fa-user-${row.status == "1" ? "large-slash" : "check"}"></i></button>`;
        btnDelete = '<button type="button" class="btn btn-danger btnDelete" title="Eliminar"><i class="fa-solid fa-user-xmark"></i></button>';

        return `<div class="btn-group btn-group-sm" role="group">
                  ${btnEdit}
                  ${btnResetPass}
                  ${btnChangeStatus}
                  ${btnDelete}
                </div>`;
      }
    },
  ],
  createdRow: function (row, data, dataIndex) {
    row.querySelector(".btnDelete").addEventListener("click", function () {
      deleteUser(data.id, data.full_name);
    });

    row.querySelector(".btnChangeStatus").addEventListener("click", function () {
      updateStatusUser(data.id, data);
    });

    //Editar usuario
    row.querySelector(".btnEdit").addEventListener("click", function (e) {
      e.preventDefault();
      modalUserLabel.innerHTML = `<i class="fa-solid fa-user-pen"></i> Editar usuario`;
      inputUserId.value = data.id;
      inputEmail.value = data.email;
      inputFirstName.value = data.first_name;
      inputLastName.value = data.last_name;
      inputProfile.value = data.profile;
      inputProfile.dispatchEvent(new Event('change'));
      inputEmailConfirm.value = data.email_confirmDesc;
      inputStatus.value = data.statusDesc;
      inputDateLog.value = moment(data.last_login, "YYYY-MM-DD HH:mm:ss").format("DD/MM/YYYY hh:mm:ss A");
      inputDateMod.value = moment(data.updated_at, "YYYY-MM-DD HH:mm:ss").format("DD/MM/YYYY hh:mm:ss A");
      inputDateCre.value = moment(data.created_at, "YYYY-MM-DD HH:mm:ss").format("DD/MM/YYYY hh:mm:ss A");

      document.querySelectorAll('#pass, #RePass').forEach(el => el.closest('.form-group').classList.add('d-none'));
      document.querySelectorAll('.form-group-edit').forEach(el => el.classList.remove('d-none'));
      
      $("#modalUser").modal("show");
    });

    //Cambio de contraseña
    row.querySelector(".btnChangePass").addEventListener("click", function (e) {
      $("#formPass input[name='id'").val(data.id);
      $("#changePassModal").modal("show");
    });
  }
});

const deleteUser = (id, name) => {
  alertify.confirm(`¿Estás seguro de eliminar al usuario <b>${name}</b>?`, function () {
    $.ajax({
      url: generalBase + `Delete/${id}`,
      type: 'DELETE',
      dataType: 'json',
      success: function (resp) {
        if (resp.status) {
          alertify.success(resp.message);
          DTUser.ajax.reload();
        } else {
          alertify.error(resp.message);
        }
      }
    });
  });
};

const updateStatusUser = (id, userData) => {
  alertify.confirm(`¿Estás seguro de ${userData.status == "1" ? "Ina" : "A"}ctivar a <b>${userData.full_name}</b>?`, function () {
    $.ajax({
      url: generalBase + `ChangeStatus/${id}`,
      type: 'PUT',
      data: userData,
      dataType: 'json',
      success: function (resp) {
        if (resp.status) {
          alertify.success(resp.message);
          DTUser.ajax.reload();
        } else {
          alertify.error(resp.message);
        }
      }
    });
  });
};

$(document).ready(function() {

  //Las condiciones del formulario
  $("#formUser").validate({
    rules: {
      pass: "required",
      RePass: {
        equalTo: "#pass"
      }
    }
  });

  //Las condiciones del formulario password
  $("#formPass").validate({
    rules: {
      pass: "required",
      RePass: {
        equalTo: "#formPassPass"
      }
    }
  });

  selectStatus.addEventListener('change', function() {
    DTUser.ajax.reload();
  });

  btnCreateUser.addEventListener('click', function () {
    modalUserLabel.innerHTML = `<i class="fa-solid fa-user-plus"></i> Crear usuario`;
    document.querySelectorAll('.form-group-edit').forEach(el => el.classList.add('d-none'));
    document.querySelectorAll('#pass, #RePass').forEach(el => el.closest('.form-group').classList.remove('d-none'));
    $("#modalUser").modal("show");
  });

  btnPassEye.forEach(button => {
    button.addEventListener('click', function() {
      const icon = this.querySelector('i');
      const input = this.closest('.input-group').querySelector('input');
      
      if (icon.classList.contains('fa-eye')) {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
      } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
      }
    });
  });

  $('#modalUser').on('shown.bs.modal', function() {
    if (inputUserId.value.length <= 0) {
      inputEmail.focus();
    }
  });

  inputEmail.addEventListener('focusout', function () {
    let email = this.value;
    let idUser = inputUserId.value.length ? inputUserId.value : 0;
    if (email.length > 0) {
      $.ajax({
        url: generalBase + 'validEmail',
        type: 'POST',
        data: { email, idUser },
        dataType: 'json',
        success: function(resp) {
          if (!resp.status) {
            alertify.warning(`El correo <b>${email}</b>, ya se encuentra creado, intente con otro correo.`);
          }
        }
      });
    }
  });

  formUser.addEventListener('submit', function (e) {
    e.preventDefault();

    if ($(this).valid()) {
      let id = inputUserId.value;
      let userData = {
        first_name: inputFirstName.value,
        last_name: inputLastName.value,
        email: inputEmail.value,
        profile: inputProfile.value
      };

      if (id.length > 0) {
        $.ajax({
          url: generalBase + `Update/${id}`,
          type: 'PUT',
          data: userData,
          dataType: 'json',
          success: function (resp) {
            if (resp.status) {
              alertify.success(resp.message);
              DTUser.ajax.reload();
              $("#modalUser").modal("hide");
            } else {
              alertify.error(resp.message);
            }
          }
        });
      } else {
        userData.pass = inputPass.value;
        userData.RePass = inputRePass.value;
        if (userData.pass == userData.RePass) {
          $.ajax({
            url: generalBase + 'Create',
            type: 'POST',
            data: userData,
            dataType: 'json',
            success: function (resp) {
              if (resp.status) {
                alertify.success(resp.message);
                DTUser.ajax.reload();
                $("#modalUser").modal("hide");
              } else {
                alertify.error(resp.message);
              }
            }
          });
        } else {
          alertify.warning('Las contraseñas no coinciden.');
        }
      }
    }
  });


  formPass.addEventListener('submit', function (e) {
    e.preventDefault();
    let id = $(this).find("input[name='id']").val().trim();
    let pass = $(this).find("input[name='pass']").val().trim();
    let RePass = $(this).find("input[name='RePass']").val().trim();

    if ($(this).valid()) {
      if (pass == RePass) {
        $.ajax({
          url: generalBase + `ChangePass/${id}`,
          type: 'PUT',
          dataType: 'json',
          data: {
            pass
          },
          success: function (resp) {
            if (resp.status) {
              alertify.success(resp.message);
              DTUser.ajax.reload();
              $("#changePassModal").modal("hide");
            } else {
              alertify.alert('¡Advertencia!', resp.message);
            }
          }
        });
      } else {
        alertify.warning("La contraseñas no coinciden, intentelo de nuevo");
        $(this).find("input[name='pass']").trigger("focus");
      }
    }
  });
});