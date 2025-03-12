const generalBase = routeBase + "admin/Users/";
const selectStatus = document.getElementById('selectStatus');
const btnCreateUser = document.getElementById('btnCreateUser');
const btnPassEye = document.querySelectorAll('.btn-pass');
const inputEmail = document.getElementById('email');
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

        btnEdit = '<button type="button" class="btn btn-secondary btnEditar" title="Editar"><i class="fa-solid fa-user-pen"></i></button>';
        btnResetPass = '';
        /* btnResetPass = '<button type="button" class="btn btn-warning btnCambiarPass" title="Restablecer Contraseña"><i class="fa-solid fa-user-lock"></i></button>'; */
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
    $(row).find(".btnDelete").on("click", function () {
      deleteUser(data.id, data.full_name);
    });

    $(row).find(".btnChangeStatus").on("click", function () {
      updateStatusUser(data.id, data);
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


const updateUser = (id, userData) => {
  alertify.confirm(`¿Estás seguro de actualizar la información del usuario <b>${userData.full_name}</b>?`, function () {
    $.ajax({
      url: generalBase + `Update/${id}`,
      type: 'PUT',  // O 'PATCH' para actualización parcial
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
  selectStatus.addEventListener('change', function() {
    DTUser.ajax.reload();
  });

  btnCreateUser.addEventListener('click', function () {
    document.getElementById('modalUserLabel').innerHTML = `<i class="fa-solid fa-user-plus"></i> Crear usuario`;
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
    if (document.getElementById('userId').value.length <= 0) {
      document.getElementById('email').focus();
    }
  });

  inputEmail.addEventListener('focusout', function () {
    let email = this.value;
    let idUser = document.getElementById('userId').value.length ? document.getElementById('userId').value : 0;
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
});