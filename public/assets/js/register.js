alertify.defaults.theme.ok = "btn btn-primary";
alertify.defaults.theme.cancel = "btn btn-danger";
alertify.defaults.theme.input = "form-control";

$(document).ready(function() {
  // Formulario de inicio de sesión
  $("#registerForm").validate({
    rules: {
      password: {
        required: true,
        passwordStrength: true
      },
      password_confirm: {
        required: true,
        equalTo: "#password"
      }
    },
    messages: {
      password: {
        required: "Por favor, introduce una contraseña",
        passwordStrength: "La contraseña no es lo suficientemente segura"
      },
      password_confirm: {
        required: "Por favor, confirma tu contraseña",
        equalTo: "Las contraseñas no coinciden"
      }
    }
  });

  // Formulario de registro
  $("#registerForm").submit(function(e) {
      e.preventDefault();
      if ($(this).valid()) {
        $.ajax({
          url: routeBase + 'auth/register',
          type: 'POST',
          data: $(this).serialize(),
          dataType: 'json',
          beforeSend: function() {
            // Mostrar spinner de carga o similar
            $("#submitBtn").prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Procesando...');
          },
          success: function(response) {
            if (response.status === 'success') {
              // Mostrar mensaje de éxito
              alertify.success(response.message);
              
              // Redireccionar
              setTimeout(function() {
                window.location.href = routeBase;
              }, 1000);
            }
          },
          complete: function() {
            // Restaurar botón
            $("#submitBtn").prop('disabled', false).html('Iniciar sesión');
          }
        });
      }
  });

  document.querySelectorAll('.btn-pass').forEach(button => {
    button.addEventListener('click', function() {
      const icon = this.querySelector('i');
      const input = this.closest('.input-group').querySelector('input');
      
      if (icon.classList.contains('fa-eye')) {
        input.setAttribute('type', 'text');
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
      } else {
        input.setAttribute('type', 'password');
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
      }
    });
  });

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

  // Agregar esta configuración al inicio de tu archivo JavaScript
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': document.querySelector('meta[name="X-CSRF-TOKEN"]')?.content || ''
    }
  });

  window.onerror = function() {
    $("#loading").addClass('d-none');
  };
  
});