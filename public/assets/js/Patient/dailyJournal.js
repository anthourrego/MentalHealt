document.addEventListener('DOMContentLoaded', function() {
  // Elementos del DOM
  const diaryList = document.getElementById('diary-list');
  const diaryEmpty = document.getElementById('diary-empty');
  const diaryLoading = document.getElementById('diary-loading');
  const btnNewDiaryEntry = document.getElementById('btnNewDiaryEntry');
  const formDiaryEntry = document.getElementById('formDiaryEntry');
  const formDateDiary = $('#dateDiary');
  const formHourDiary = $('#hourDiary');
  const formDiaryEntryId = document.getElementById('diaryEntryId');
  const diaryEntryModal = $('#diaryEntryModal');

  // Función para cargar las entradas del diario
  function loadDiaryEntries() {
    // Mostrar indicador de carga
    diaryList.innerHTML = '';
    diaryLoading.style.display = 'block';
    diaryEmpty.style.display = 'none';
    
    // Realizar la solicitud
    $.ajax({
      url: `${routeBase}patient/diary/getEntries`,
      type: 'GET',
      dataType: 'json',
      success: function(data) {
        // Ocultar indicador de carga
        diaryLoading.style.display = 'none';
        
        if (!data.entries || data.entries.length === 0) {
          diaryEmpty.style.display = 'block';
          return;
        }
        
        // Mostrar las entradas
        renderDiaryEntries(data.entries);
      },
      errror: () => {
        diaryLoading.style.display = 'none';
        diaryEmpty.style.display = 'block';
      }
    });
  }

  // Función para renderizar las entradas del diario
  function renderDiaryEntries(entries) {
    diaryList.innerHTML = '';
    
    entries.forEach(entry => {
      // Convertir fecha a formato legible
      const entryDate = new Date(entry.entry_date + ' ' + entry.entry_hour);
      const formattedDate = entryDate.toLocaleDateString('es-CO', { 
        weekday: 'long', 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric',
        timeZone: 'America/Bogota'
      }) + ' ' + entryDate.toLocaleTimeString('es-CO', {
        hour: '2-digit',
        minute: '2-digit',
        timeZone: 'America/Bogota'
      });
      
      // Determinar clase CSS basada en el estado de ánimo
      let moodClass, moodEmoji;
      switch (parseInt(entry.mood)) {
        case 1:
          moodClass = 'bg-danger text-white';
          moodEmoji = '<i class="fa-regular fa-face-tired"></i>';
          break;
        case 2:
          moodClass = 'bg-warning';
          moodEmoji = '<i class="fa-regular fa-face-frown"></i>';
          break;
        case 3:
          moodClass = 'bg-info text-white';
          moodEmoji = '<i class="fa-regular fa-face-meh"></i>';
          break;
        case 4:
          moodClass = 'bg-primary text-white';
          moodEmoji = '<i class="fa-regular fa-face-smile"></i>';
          break;
        case 5:
          moodClass = 'bg-success text-white';
          moodEmoji = '<i class="fa-regular fa-face-laugh-beam"></i>';
          break;
        default:
          moodClass = 'bg-secondary text-white';
          moodEmoji = '<i class="fa-regular fa-face-flushed"></i>';
      }

      // Crear elemento de entrada
      const entryElement = document.createElement('div');
      entryElement.className = 'mb-3 px-2';
      entryElement.innerHTML = `
        <div class="diary-entry card">
          <div class="card-header ${moodClass} d-flex justify-content-between align-items-center">
            <div>
              <span class="me-2">${moodEmoji}</span>
              <small>${formattedDate}</small>
            </div>
          </div>
          <div class="card-body">
            <p class="card-text">${entry.content.length > 500 ? entry.content.substring(0, 500) + '...' : entry.content}</p>
          </div>
          ${entry.private_entry == "1" ? '<div class="card-footer bg-light"><small><i class="fas fa-lock me-1"></i> Entrada privada</small></div>' : ''}
        </div>
      `;

      diaryList.appendChild(entryElement);
    });
  }

  // Cargar entradas del diario
  loadDiaryEntries();

  btnNewDiaryEntry?.addEventListener('click', function() {
    formDiaryEntry.reset();
    formDiaryEntryId.value = '';
    formDateDiary.datetimepicker('date', moment());
    formHourDiary.datetimepicker('date', moment());
    
    diaryEntryModal.modal('show');
  });

  formDiaryEntry?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Validar que al menos se haya seleccionado un nivel de estado de ánimo
    if (!document.querySelector('input[name="mood"]:checked')) {
      alertify.error('Por favor, indica cómo te sientes hoy');
      return;
    }

    if ($(this).valid()) {
      // Recopilar datos del formulario
      const formData = new FormData(this);
      // Determinar si es actualización o creación
      const url = formDiaryEntryId.value 
        ? `${routeBase}patient/diary/update/${formDiaryEntryId.value}` 
        : `${routeBase}patient/diary/create`;

      // Mostrar indicador de carga
      const submitBtn = document.querySelector('[form="formDiaryEntry"]');
      const originalBtnText = submitBtn.innerHTML;
      submitBtn.disabled = true;
      submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Guardando...';
  
      $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(data) {
          if (data.status) {
            alertify.success(data.message || 'Entrada guardada correctamente');
            diaryEntryModal.modal('hide');
            loadDiaryEntries();
          } else {
            alertify.error(data.message || 'Error al guardar la entrada');
          }
        },
        complete: function() {
          // Restaurar botón
          submitBtn.disabled = false;
          submitBtn.innerHTML = originalBtnText;
        }
      });
    }
  });

  //Calendarios
  formDateDiary.datetimepicker({
    format: 'L',
    defaultDate: moment(),
    maxDate: moment(),
    allowInputToggle: true
  });
  
  formHourDiary.datetimepicker({
    format: 'hh:mm A',
    defaultDate: moment(),
    maxDate: moment(),
    allowInputToggle: true
  });

  $("#private_entry").bootstrapSwitch();
});