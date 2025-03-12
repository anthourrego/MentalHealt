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
    /* {
      orderable: false,
      searchable: false,
      defaultContent: '',
      className: 'text-center noExport',
      render: function (meta, type, data, meta) {

        btnEditar = validPermissions(12) ? '<button type="button" class="btn btn-secondary btnEditar" title="Editar"><i class="fa-solid fa-user-pen"></i></button>' : '<button type="button" class="btn btn-dark btnVer" title="Ver"><i class="fa-solid fa-eye"></i></button>';

        btnCambiarPass = validPermissions(13) ? '<button type="button" class="btn btn-warning btnCambiarPass" title="Cambiar Contraseña"><i class="fa-solid fa-user-lock"></i></button>' : '';

        btnPermisos = (data.perfilId == null && validPermissions(15)) ? '<button type="button" class="btn btn-info btnPermisos" title="Permisos"><i class="fa-solid fa-user-shield"></i></button>' : '';

        btnCambiarEstado = validPermissions(14) ? `<button type="button" class="btn btn-${data.estado == "1" ? "danger" : "success"} btnCambiarEstado" title="${data.estado == "1" ? "Ina" : "A"}ctivar"><i class="fa-solid fa-user-${data.estado == "1" ? "large-slash" : "check"}"></i></button>` : '';

        return `<div class="btn-group btn-group-sm" role="group">
                  ${btnEditar}
                  ${btnCambiarPass}
                  ${btnPermisos}
                  ${btnCambiarEstado}
                </div>`;
      }
    }, */
  ],
  createdRow: function (row, data, dataIndex) {
    
  }
});


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