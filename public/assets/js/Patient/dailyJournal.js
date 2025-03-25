const dashboard = true;

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
  const labelEntryModal = document.getElementById("diaryEntryModalLabel");
  const generalBaseEntry = routeBase + "patient/diary/";

  // Función para cargar las entradas del diario
  function loadDiaryEntries() {
    // Mostrar indicador de carga
    diaryList.innerHTML = '';
    diaryLoading.style.display = 'block';
    diaryEmpty.style.display = 'none';

    // Set the limit for diary entries, handling potential undefined values
    const limit = typeof limitDiary !== 'undefined' ? limitDiary : 0; // Default to 10 entries if limitDiary is not defined

    // Realizar la solicitud
    $.ajax({
      url: generalBaseEntry + `getEntries`,
      type: 'GET',
      dataType: 'json',
      data: {limit},
      success: function(data) {
        // Ocultar indicador de carga
        diaryLoading.style.display = 'none';
        
        if (!data.entries || data.entries.length === 0) {
          diaryEmpty.style.display = 'block';
          return;
        }
        
        // Mostrar las entradas
        renderDiaryEntries(data.entries);

        if (dashboard) {
          calendar.refetchEvents();
        }
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
        <div class="diary-entry card mb-0">
          <div class="card-header ${moodClass} d-flex justify-content-between align-items-center">
            <div>
              <span class="me-2">${moodEmoji}</span>
              <small>${formattedDate}</small>
            </div>
            ${entry.private_entry == "1" ? '<div title="Entrada privada" class="ml-auto"><i class="fas fa-lock me-1"></i></div>' : ''}
          </div>
          <div class="card-body">
            <p class="card-text">${entry.content.length > 250 ? entry.content.substring(0, 250) + '...' : entry.content}</p>
          </div>
          <div class="card-footer bg-light text-center">
            <button class="btn btn-sm btn-outline-secondary me-2 btn-edit-entry" data-id="${entry.id}">
              <i class="fas fa-edit"></i> Editar
            </button>
            <button class="btn btn-sm btn-outline-danger btn-delete-entry" data-id="${entry.id}">
              <i class="fas fa-trash"></i> Eliminar
            </button>
          </div>
        </div>
      `;

      diaryList.appendChild(entryElement);

      // Configurar los botones de editar y eliminar después de agregar la entrada al DOM
      const editButtons = entryElement.querySelectorAll('.btn-edit-entry');
      const deleteButtons = entryElement.querySelectorAll('.btn-delete-entry');

      // Evento para eliminar entrada
      deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
          const entryId = this.getAttribute('data-id');
          deleteEntry(entryId);
        });
      });

      editButtons.forEach(button => {
        button.addEventListener('click', function() {
          editEntry(entry);
        });
      });
    });
  }

  function deleteEntry(entryId) {
    alertify.confirm('Confirmar eliminación', '¿Estás seguro de que deseas eliminar esta entrada del diario?',
      function() {
        $.ajax({
          url: generalBaseEntry + `delete/${entryId}`,
          type: 'DELETE',
          success: function(response) {
            if (response.status) {
              alertify.success(response.message || 'Entrada eliminada correctamente');
              loadDiaryEntries();
            } else {
              alertify.error(response.message || 'Error al eliminar la entrada');
            }
          }
        });
      },
      function() {
        // Cancelado por el usuario
      }
    ).set('labels', {ok:'Eliminar', cancel:'Cancelar'});
  }

  function editEntry(entry) {
    // Rellenar formulario con datos existentes
    formDiaryEntryId.value = entry.id;
    document.getElementById(`mood${entry.mood}`).click();
    document.getElementById('diaryContent').value = entry.content;
    $("#private_entry").bootstrapSwitch('state', entry.private_entry == "1");
    labelEntryModal.innerHTML = 'Editar entrada del diario';
    
    // Establecer fecha y hora
    formDateDiary.datetimepicker('date', moment(entry.entry_date));
    formHourDiary.datetimepicker('date', moment(entry.entry_date + ' ' + entry.entry_hour));
    
    // Mostrar modal
    diaryEntryModal.modal('show');
  }

  // Cargar entradas del diario
  loadDiaryEntries();

  btnNewDiaryEntry?.addEventListener('click', function() {
    formDiaryEntry.reset();
    formDiaryEntryId.value = '';
    formDateDiary.datetimepicker('date', moment().format('L'));
    formHourDiary.datetimepicker('date', moment().format('LT'));
    document.getElementById("mood3").click();
    labelEntryModal.innerHTML = 'Nueva entrada del diario';
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
      // Determinar si es actualización o creación
      const url = generalBaseEntry + (formDiaryEntryId.value ? `update/${formDiaryEntryId.value}` : `create`);

      // Mostrar indicador de carga
      const submitBtn = document.querySelector('[form="formDiaryEntry"]');
      const originalBtnText = submitBtn.innerHTML;
      submitBtn.disabled = true;
      submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Guardando...';

      const entryData = $(this).serialize();
  
      $.ajax({
        url: url,
        type: (formDiaryEntryId.value ? 'PUT' : 'POST'),
        data: entryData,
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