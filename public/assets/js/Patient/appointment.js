const limitDiary = 6;
const generalBaseAppointment = routeBase + "patient/appointments/";
const calendarEl = document.getElementById('calendar');

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
  
  // Funcionalidad de citas
  editable: false, // Los pacientes no pueden editar directamente
  selectable: true, // Permite seleccionar días/horarios
  businessHours: {
    daysOfWeek: [1, 2, 3, 4, 5], // Lunes a viernes
    startTime: '09:00',
    endTime: '18:00',
  },
  selectConstraint: 'businessHours',
  //slotDuration: '01:00:00', // Intervalos de 30 minutos
  
  // Eventos del calendario (citas del paciente)
  events: {
    url: generalBaseAppointment + 'getEvents',
    method: 'GET',
    failure: function() {
      alertify.error('Error al cargar las citas. Por favor, recarga la página.');
    },
    /* extraParams: {
      // Se puede añadir un token CSRF aquí si es necesario
      csrf_token: document.querySelector('meta[name="csrf-token"]')?.content || ''
    } */
  },
  
  // Colores por tipo de evento
  eventClassNames: function(arg) {
    // Determinar clase basada en el estado de la cita
    const status = arg.event.extendedProps.status;
    switch(status) {
      case 'confirmed':
        return ['bg-success'];
      case 'pending':
        return ['bg-warning'];
      case 'cancelled_patient':
      case 'cancelled_therapist':
        return ['bg-danger', 'text-decoration-line-through'];
      case 'completed':
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
      case 'in_person':
        modalityIcon = '<i class="fas fa-user me-1"></i>';
        break;
      case 'video_call':
        modalityIcon = '<i class="fas fa-video me-1"></i>';
        break;
      case 'phone_call':
        modalityIcon = '<i class="fas fa-phone me-1"></i>';
        break;
      default:
        modalityIcon = '<i class="fas fa-book me-1"></i>';
        break;
    }
    
    return { 
      html: `
        <div class="fc-event-time">${timeText}</div>
        <div class="fc-event-title">${modalityIcon} ${title}</div>
      `
    };
  },
  
  // Callbacks de interacción
  dateClick: function(info) {
    // Verificar si el día está dentro del horario laboral
    const clickedDate = info.date;
    const day = clickedDate.getDay();
    
    // Si es fin de semana (0=domingo, 6=sábado), no permitir
    if (day === 0 || day === 6) {
      alertify.warning('No se pueden agendar citas en fines de semana');
      return;
    }

    // Verificar que la fecha seleccionada no sea anterior a hoy
    const today = new Date();
    today.setHours(0, 0, 0, 0); // Resetear la hora para comparar solo fechas
    if (clickedDate < today) {
      alertify.warning('No se pueden agendar citas en fechas pasadas');
      return;
    }
    
    // Mostrar modal para seleccionar terapeuta y horario
    openAppointmentModal(info.dateStr);
  },
  
  eventClick: function(info) {
    // Mostrar detalles de la cita al hacer clic
    const event = info.event;
    if (event.extendedProps.status == "diary") {
      const entry = {
        id: event.extendedProps.primary_id,
        mood: event.extendedProps.mood,
        content: event.extendedProps.content,
        private_entry: event.extendedProps.private_entry,
        entry_date: event.extendedProps.entry_date,
        entry_hour: event.extendedProps.entry_hour
      };
      editEntry(entry);
    } else {
      showAppointmentDetails(event.id, event.extendedProps);
    }
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

// Función para mostrar detalles de una cita
function showAppointmentDetails(appointmentId, details) {
  // Actualizar elementos del modal con los detalles
  document.getElementById('appointmentTherapist').textContent = details.therapistName;
  document.getElementById('appointmentDateTime').textContent = details.formattedDateTime;
  document.getElementById('appointmentModality').textContent = getModalityText(details.modality);
  document.getElementById('appointmentStatus').textContent = getStatusText(details.status);
  document.getElementById('appointmentNotes').textContent = details.notes || 'Sin notas';
  
  // Configurar botones según el estado
  const btnCancel = document.getElementById('btnCancelAppointment');
  if (details.status === 'pending' || details.status === 'confirmed') {
    btnCancel.classList.remove('d-none');
    btnCancel.onclick = () => confirmCancelAppointment(appointmentId);
  } else {
    btnCancel.classList.add('d-none');
  }
  
  // Mostrar botón de enlace a videollamada si es relevante
  const btnJoinCall = document.getElementById('btnJoinVideoCall');
  if (details.modality === 'video_call' && details.video_url && 
      (details.status === 'confirmed')) {
    btnJoinCall.classList.remove('d-none');
    btnJoinCall.href = details.video_url;
  } else {
    btnJoinCall.classList.add('d-none');
  }
  
  // Mostrar el modal
  const detailModal = new bootstrap.Modal(document.getElementById('appointmentDetailModal'));
  detailModal.show();
}

// Función para abrir modal de nueva cita
function openAppointmentModal(dateStr) {
  // Limpiar selecciones previas
  document.getElementById('appointmentDate').value = dateStr;
  
  // Si tienes un select para terapeutas, puedes cargarlo aquí
  loadTherapists();
  
  // Mostrar el modal
  const appointmentModal = new bootstrap.Modal(document.getElementById('appointmentModal'));
  appointmentModal.show();
}

// Función para cargar terapeutas disponibles
function loadTherapists() {
  const selectTherapist = document.getElementById('therapistSelect');
  const date = document.getElementById('appointmentDate').value;
  
  // Limpiar opciones previas
  selectTherapist.innerHTML = '<option value="">Seleccione un terapeuta...</option>';
  
  // Mostrar indicador de carga
  selectTherapist.disabled = true;
  
  fetch(routeBase + 'patient/appointments/getAvailableTherapists?date=' + date)
    .then(response => response.json())
    .then(data => {
      if (data.status && data.therapists.length > 0) {
        // Añadir opciones de terapeutas
        data.therapists.forEach(therapist => {
          const option = document.createElement('option');
          option.value = therapist.id;
          option.textContent = `${therapist.first_name} ${therapist.last_name}`;
          selectTherapist.appendChild(option);
        });
      } else {
        // No hay terapeutas disponibles
        const option = document.createElement('option');
        option.disabled = true;
        option.textContent = 'No hay terapeutas disponibles en esta fecha';
        selectTherapist.appendChild(option);
      }
    })
    .catch(error => {
      console.error('Error al cargar terapeutas:', error);
      alertify.error('Error al cargar terapeutas disponibles');
    })
    .finally(() => {
      selectTherapist.disabled = false;
    });
}

// Función para confirmar cancelación
function confirmCancelAppointment(appointmentId) {
  alertify.confirm(
    'Cancelar Cita',
    '¿Estás seguro de que deseas cancelar esta cita?',
    function() {
      // Si el usuario confirma
      cancelAppointment(appointmentId);
    },
    function() {
      // Si el usuario cancela
      alertify.message('Operación cancelada');
    }
  ).set('labels', {ok:'Sí, cancelar', cancel:'No'});
}

// Función para cancelar cita
function cancelAppointment(appointmentId) {
  fetch(routeBase + 'patient/appointments/cancel/' + appointmentId, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-Requested-With': 'XMLHttpRequest'
    },
    body: JSON.stringify({
      csrf_token: document.querySelector('meta[name="csrf-token"]')?.content || ''
    })
  })
  .then(response => response.json())
  .then(data => {
    if (data.status) {
      alertify.success(data.message || 'Cita cancelada correctamente');
      calendar.refetchEvents();
      bootstrap.Modal.getInstance(document.getElementById('appointmentDetailModal')).hide();
    } else {
      alertify.error(data.message || 'Error al cancelar la cita');
    }
  })
  .catch(error => {
    console.error('Error:', error);
    alertify.error('Error al procesar la solicitud');
  });
}

// Funciones auxiliares para texto de estado y modalidad
function getStatusText(status) {
  const statusMap = {
    'pending': 'Pendiente de confirmación',
    'confirmed': 'Confirmada',
    'cancelled_patient': 'Cancelada por el paciente',
    'cancelled_therapist': 'Cancelada por el terapeuta',
    'completed': 'Completada',
    'no_show': 'No asistió'
  };
  return statusMap[status] || status;
}

function getModalityText(modality) {
  const modalityMap = {
    'in_person': 'Presencial',
    'video_call': 'Videollamada',
    'phone_call': 'Llamada telefónica'
  };
  return modalityMap[modality] || modality;
}

document.addEventListener('DOMContentLoaded', function() {
  
  calendar.render();
  
  // Manejo del formulario de creación de cita
  document.getElementById('formCreateAppointment')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Recoger datos del formulario
    const formData = new FormData(this);
    
    // Enviar solicitud
    fetch(routeBase + 'patient/appointments/create', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.status) {
        alertify.success(data.message || 'Cita agendada correctamente');
        calendar.refetchEvents();
        bootstrap.Modal.getInstance(document.getElementById('appointmentModal')).hide();
      } else {
        alertify.error(data.message || 'Error al agendar la cita');
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alertify.error('Error al procesar la solicitud');
    });
  });
  
  // Actualizar horarios disponibles cuando se selecciona un terapeuta
  document.getElementById('therapistSelect')?.addEventListener('change', function() {
    const therapistId = this.value;
    const date = document.getElementById('appointmentDate').value;
    const timeSelect = document.getElementById('timeSelect');
    
    if (!therapistId) {
      timeSelect.innerHTML = '<option value="">Seleccione un horario...</option>';
      timeSelect.disabled = true;
      return;
    }
    
    // Mostrar indicador de carga
    timeSelect.disabled = true;
    timeSelect.innerHTML = '<option value="">Cargando horarios...</option>';
    
    fetch(`${routeBase}patient/appointments/getAvailableTimeSlots?therapist_id=${therapistId}&date=${date}`)
      .then(response => response.json())
      .then(data => {
        // Resetear select
        timeSelect.innerHTML = '<option value="">Seleccione un horario...</option>';
        
        if (data.status && data.timeSlots.length > 0) {
          data.timeSlots.forEach(slot => {
            const option = document.createElement('option');
            option.value = slot.start;
            option.textContent = `${slot.start} - ${slot.end}`;
            timeSelect.appendChild(option);
          });
        } else {
          const option = document.createElement('option');
          option.disabled = true;
          option.textContent = 'No hay horarios disponibles';
          timeSelect.appendChild(option);
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alertify.error('Error al cargar horarios disponibles');
      })
      .finally(() => {
        timeSelect.disabled = false;
      });
  });
});