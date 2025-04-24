const generalBaseAppointment = routeBase + "therapist/appointments/";
const calendarEl = document.getElementById('calendar');
const appointmentDetailModal = new bootstrap.Modal(document.getElementById('appointmentDetailModal'), {
  backdrop: 'static',
  keyboard: false
});
const formCompleted = document.getElementById('formCompleted');
const formConfirmed = document.getElementById('formConfirmed');

// Configuración para un dashboard de paciente con citas médicas
const calendar = new FullCalendar.Calendar(calendarEl, {
	// Configuración general
	initialView: 'dayGridMonth',
	headerToolbar: {
		left: 'prev,next today',
		center: 'title',
		right: 'dayGridMonth,timeGridWeek,timeGridDay'
	},
	locale: 'es', // Español
	firstDay: 1, // Lunes como primer día de la semana
	height: 'auto',
	timeZone: 'local',
	slotLabelFormat:{
		hour: '2-digit',
		minute: '2-digit',
		hour12: true
	},//se visualizara de esta manera 01:00 AM en la columna de horas
	eventTimeFormat: {
		hour: '2-digit',
		minute: '2-digit',
		hour12: true
	},//y este código se visualizara de la misma manera pero en 
	// Configuración visual
	themeSystem: 'bootstrap4',
	buttonText: {
		today: 'Hoy',
		month: 'Mes',
		week: 'Semana',
		day: 'Día'
	},
	allDaySlot: false,
	// Funcionalidad de citas
	editable: false, // Los pacientes no pueden editar directamente
	selectable: true, // Permite seleccionar días/horarios
	businessHours: {
		daysOfWeek: [1, 2, 3, 4, 5], // Lunes a viernes
		startTime: '08:00',
		endTime: '19:00',
	},
	selectConstraint: 'businessHours',
	slotDuration: '01:00:00',     // Duración de cada slot (1 hora)
	snapDuration: '01:00:00',     // Forzar ajuste a intervalos de 1 hora
	selectable: false,
	// Eventos del calendario (citas del paciente)
	events: {
		url: generalBaseAppointment + 'getEvents',
		method: 'GET',
		failure: function() {
			alertify.error('Error al cargar las citas. Por favor, recarga la página.');
		}
	},
	
	// Colores por tipo de evento
	eventClassNames: function(arg) {
		// Determinar clase basada en el estado de la cita
		const status = arg.event.extendedProps.status;
		switch(status) {
			case 'CO':
				return ['bg-success'];
			case 'PE':
				return ['bg-warning'];
			case 'CP':
			case 'CT':
				return ['bg-danger', 'text-decoration-line-through'];
			case 'CC':
				return ['bg-info'];
			default:
				return ['bg-primary'];
		}
	},
	
	// Personalización del contenido del evento
	eventContent: function(arg) {
		const timeText = arg.timeText;
		const title = arg.event.title;
		const modality = arg.event.extendedProps.modality;
		
		let modalityIcon = '';
		switch(modality) {
			case 'IP':
				modalityIcon = '<i class="fas fa-user me-1"></i>';
				break;
			case 'VC':
				modalityIcon = '<i class="fas fa-video me-1"></i>';
				break;
			case 'PC':
				modalityIcon = '<i class="fas fa-phone me-1"></i>';
				break;
			default:
				modalityIcon = '<i class="fas fa-book me-1"></i>';
				break;
		}
		
		return { 
			html: `<div class="fc-event-time">${timeText} ${modalityIcon} ${title}</div>`
		};
	},
	
	// Callbacks de interacción
	eventClick: function(info) {
		const event = info.event;
		// Mostrar detalles de la cita al hacer clic
		showAppointmentDetails(event.extendedProps.primary_id, event.extendedProps);
	},
	// Texto para cuando no hay eventos
	noEventsContent: 'No tienes citas programadas',
	// Carga y estado
	loading: function(isLoading) {
		if (isLoading) {
			// Mostrar indicador de carga
			document.getElementById('calendar-loading').style.display = 'flex';
		} else {
			// Ocultar indicador de carga
			document.getElementById('calendar-loading').style.display = 'none';
		}
	}
});

// Funciones auxiliares para texto de estado y modalidad
function getStatusText(status) {
  const statusMap = {
    'PE': 'Pendiente de confirmación',
    'CO': 'Confirmada',
    'CP': 'Cancelada por el paciente',
    'CT': 'Cancelada por el terapeuta',
    'CC': 'Completada',
    'NS': 'No asistió'
  };
  return statusMap[status] || status;
}

function getModalityText(modality) {
  const modalityMap = {
    'IP': 'Presencial',
    'VC': 'Videollamada',
    'PC': 'Llamada telefónica'
  };
  return modalityMap[modality] || modality;
}

// Función para mostrar detalles de una cita
function showAppointmentDetails(appointmentId, details) {
  // Actualizar elementos del modal con los detalles
  document.getElementById('appointmentTherapist').textContent = details.patientName;
  document.getElementById('appointmentDateTime').textContent = details.formattedDateTime;
  document.getElementById('appointmentModality').textContent = getModalityText(details.modality);
  document.getElementById('appointmentStatus').textContent = getStatusText(details.status);
  document.getElementById('appointmentNotes').textContent = details.addNotes || 'Sin notas';
  document.getElementById('appointmentNotesAdd').textContent = details.notes_therapist || 'Sin notas';

  document.getElementById('appointmentIdCompleted').value = appointmentId;
  document.getElementById('appointmentIdConfirm').value = appointmentId;

  document.querySelectorAll(".appointmentNotesAdd").forEach(element => {
    element.classList.add('d-none');
  });

  // Configurar botones según el estado
  const btnCancel = document.getElementById('btnCancelAppointment');
	const btnJoinCall = document.getElementById('btnJoinVideoCall');
	const btnCompleted = document.getElementById('btnCompleted');
	const btnConfirmed = document.getElementById('btnConfirmed');
	const btnNoPresented = document.getElementById('btnNoPresented');
	const btnNotes = document.getElementById('btnNotes');
	
	btnCancel.classList.add('d-none');
	btnJoinCall.classList.add('d-none');
	btnCompleted.classList.add('d-none');
	btnConfirmed.classList.add('d-none');
	btnNoPresented.classList.add('d-none');
	formCompleted.classList.add('d-none');
	formConfirmed.classList.add('d-none');

  if (details.status === 'PE' || details.status === 'CO') {
    btnCancel.classList.remove('d-none');
    btnCancel.onclick = () => confirmCancelAppointment(appointmentId);
  }
  
  // Mostrar botón de enlace a videollamada si es relevante
  if (details.modality === 'VC' && details.video_url && (details.status === 'CO')) {
    btnJoinCall.classList.remove('d-none');
    btnJoinCall.href = details.video_url;
  }

	if (details.modality === 'VC' && (details.status === 'PE')) {
    formConfirmed.classList.remove('d-none');
    btnConfirmed.classList.remove('d-none');
  }

	if (details.status === 'CO' && details.currentDate) {
		formCompleted.classList.remove('d-none');
		btnCompleted.classList.remove('d-none');
		btnNoPresented.classList.remove('d-none');

		btnNoPresented.onclick = () => confirmNoPresentedAppointment(appointmentId);
	}

  if (details.status === 'CC') {
    document.querySelectorAll(".appointmentNotesAdd").forEach(element => {
      element.classList.remove('d-none');
    });
    btnJoinCall.classList.add('d-none');
    btnCompleted.classList.add('d-none');
    btnConfirmed.classList.add('d-none');
    btnNoPresented.classList.add('d-none');
    btnCancel.classList.add('d-none');
  }

  console.log(details);
  btnNotes.onclick = () => redirectPatientNotes(details.patient_id);
  
  // Mostrar el modal
  appointmentDetailModal.show();
}

// Función para confirmar cancelación
function confirmCancelAppointment(appointmentId) {
  alertify.confirm(
    'Cancelar Cita',
    '¿Estás seguro de que deseas cancelar esta cita?',
    function() {
      // Si el usuario confirma
      $.ajax({
        url: generalBaseAppointment + 'cancel/' + appointmentId,
        type: 'PUT',
        dataType: 'json',
        data: {
          status: 'CT'
          //csrf_token: document.querySelector('meta[name="csrf-token"]')?.content || ''
        },
        success: function(data) {
          if (data.status) {
            alertify.success(data.message || 'Cita cancelada correctamente');
            calendar.refetchEvents();
            getAppointments();
            appointmentDetailModal.hide();
          } else {
            alertify.error(data.message || 'Error al cancelar la cita');
          }
        },
        error: function(xhr, status, error) {
          console.error('Error:', error);
          alertify.error('Error al procesar la solicitud');
        }
      });
    },
    function() {
      // Si el usuario cancela
      alertify.message('Operación cancelada');
    }
  ).set('labels', {ok:'Sí, cancelar', cancel:'No'});
}

function confirmNoPresentedAppointment(appointmentId) {
  alertify.confirm(
    'Actualizar cita',
    '¿Estás seguro de que deseas actualizar esta cita?',
    function() {
      $.ajax({
        url: generalBaseAppointment + 'noPresented/' + appointmentId,
        type: 'PUT',
        dataType: 'json',
        data: {
          status: 'NS'
          //csrf_token: document.querySelector('meta[name="csrf-token"]')?.content || ''
        },
        success: function(data) {
          if (data.status) {
            alertify.success(data.message || 'Cita actualizada correctamente');
            calendar.refetchEvents();
            getAppointments();
            appointmentDetailModal.hide();
          } else {
            alertify.error(data.message || 'Error al cancelar la cita');
          }
        },
        error: function(xhr, status, error) {
          console.error('Error:', error);
          alertify.error('Error al procesar la solicitud');
        }
      });
    },
    function() {
      // Si el usuario cancela
      alertify.message('Operación cancelada');
    }
  ).set('labels', {ok:'Si', cancel:'No'});
}

function redirectPatientNotes($patientId) {
  const url = routeBase + 'therapist/diary/' + $patientId;
  window.location = url;
}

function getAppointments() {
  const listAppoinment = document.getElementById('listAppoinment');
  listAppoinment.innerHTML = ''; // Limpiar lista de citas
  $.ajax({
    url: generalBaseAppointment + 'getAppointments',
		data: {
			type: "list"
		},
    type: 'GET',
    dataType: 'json',
    success: function(data) {
      if (data.length > 0) {
        // Procesar datos de citas
        data.forEach(appointment => {
          const listGroup = document.createElement('button');
          modalityIcon = '';
          classStatus = '';
          switch(appointment.modality) {
            case 'IP':
              modalityIcon = '<i class="fas fa-user me-1"></i>';
              break;
            case 'VC':
              modalityIcon = '<i class="fas fa-video me-1"></i>';
              break;
            case 'PC':
              modalityIcon = '<i class="fas fa-phone me-1"></i>';
              break;
            default:
              modalityIcon = '<i class="fas fa-book me-1"></i>';
              break;
          }

          switch(appointment.status) {
            case 'CO':
              classStatus = "list-group-item-success";
              break;
            case 'PE':
              classStatus = "list-group-item-warning";
              break;
            case 'CP':
            case 'CT':
              classStatus = "list-group-item-danger";
              break;
            case 'CC':
              classStatus = "list-group-item-info";
              break;
          }

          listGroup.className = 'list-group-item list-group-item-action ' + classStatus;
          listGroup.innerHTML = `${modalityIcon} | ${appointment.title} | ${appointment.formattedDateTime}`;
          listGroup.addEventListener('click', function() {
            showAppointmentDetails(appointment.primary_id, appointment);
          });

          listAppoinment.appendChild(listGroup);
        });
      } else {
        // No hay citas programadas
        const listGroup = document.createElement('div');
        listGroup.className = 'list-group-item text-center';
        listGroup.textContent = 'No tienes citas programadas';
        listAppoinment.appendChild(listGroup);
      }
    },
    error: function(xhr, status, error) {
      console.error('Error:', error);
      alertify.error('Error al procesar la solicitud');
    }
  });
}

function updateAppointments(appointmentId, dataJson) {
  $.ajax({
    url: generalBaseAppointment + 'Update/' + appointmentId,
    type: 'PUT',
    dataType: 'json',
    data: dataJson,
    success: function(data) {
      if (data.status) {
        alertify.success(data.message || 'Cita confirmada correctamente');
        calendar.refetchEvents();
        getAppointments();
        appointmentDetailModal.hide();
      } else {
        alertify.error(data.message || 'Error al modificar la cita');
      }
    },
    error: function(xhr, status, error) {
      console.error('Error:', error);
      alertify.error('Error al procesar la solicitud');
    }
  });
}

document.addEventListener('DOMContentLoaded', function() {
  calendar.render();
  getAppointments();

  document.getElementById('formConfirmed')?.addEventListener('submit', function(e) {
    e.preventDefault();

    if ($(this).valid()) {
      // Recoger datos del formulario
      const formData = new FormData(this);

      updateAppointments(formData.get('appointmentId'), {
        status: 'CO',
        video_url: formData.get('urlVideo')
      });
    }
  });

  document.getElementById('formCompleted')?.addEventListener('submit', function(e) {
    e.preventDefault();

    if ($(this).valid()) {
      // Recoger datos del formulario
      const formData = new FormData(this);

      updateAppointments(formData.get('appointmentId'), {
        status: 'CC',
        notes_therapist: formData.get('appointmentAnotation')
      });
    }
  });
});