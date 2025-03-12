alertify.defaults.theme.ok = "btn btn-primary";
alertify.defaults.theme.cancel = "btn btn-danger";
alertify.defaults.theme.input = "form-control";
const btnLogout = document.getElementById('btnLogout');

$(document).on({
  ajaxStart: function() {
    $("#loading").removeClass('d-none');
  },
  ajaxStop: function() {
    $("#loading").addClass('d-none');
  },
  ajaxError: function(funcion, request, settings){
    $("#loading").removeClass('d-none');
    if (request && request.responseJSON) {
      if (request.responseJSON.errorsList) {
        alertify.alert(request.responseJSON.title || "Error", request.responseJSON.errorsList, function(){
          this.destroy();
        });
      } else if (request.responseJSON.message) {
        alertify.alert(request.responseJSON.title || "Error", request.responseJSON.message, function(){
          this.destroy();
        });
      } else {
        alertify.error('Ocurrió un error inesperado. Por favor, inténtelo de nuevo.');
      }
      
    }
    console.error(funcion);
    console.error(request);
    console.error(settings);
  }
});

window.onerror = function() {
  $("#loading").addClass('d-none');
};

document.addEventListener('DOMContentLoaded', function() {
  
  btnLogout.addEventListener('click', function() {
    alertify.confirm('Cerrar sesión', '¿Estás seguro de que deseas cerrar sesión?', function() {
      $.ajax({
        url: routeBase + 'auth/logout',
        type: 'POST',
        success: (data) => {
          if (data.status === 'success') {
            window.location.href = '/';
          }
        }
      });
    }, function() {
      return;
    });
  });
});