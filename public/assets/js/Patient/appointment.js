const limitDiary = 6;
const generalBaseAppointment = routeBase + "patient/appointments/";
const calendarEl = document.getElementById('calendar');
const dashboard = true;
let dataTherapist = [];
const appointmentModal = new bootstrap.Modal(document.getElementById('appointmentModal'), {
  backdrop: 'static',
  keyboard: false
});

const appointmentDetailModal = new bootstrap.Modal(document.getElementById('appointmentDetailModal'), {
  backdrop: 'static',
  keyboard: false
});

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
  dateClick: function(info) {
    // Verificar si el día está dentro del horario laboral
    
    const clickedDate = info.date;
    const clickedDateClone = new Date(info.date);
    const clickedDateHour = clickedDateClone.setHours(0, 0, 0, 0);
    const calendarMode = calendar.view.type;
    //const startHour = calendar.view.calendar.getCurrentData().businessHours[0].start;
    const day = clickedDate.getDay();
    
    // Si es fin de semana (0=domingo, 6=sábado), no permitir
    if (day === 0 || day === 6) {
      alertify.warning('No se pueden agendar citas en fines de semana');
      return;
    }

    // Verificar que la fecha seleccionada no sea anterior a hoy
    const now = new Date();
    const nowHour = new Date().setHours(0, 0, 0, 0); // Establecer hora a medianoche para comparación
    
    if ((clickedDate < now && calendarMode !== "dayGridMonth") || (clickedDateHour < nowHour && calendarMode === "dayGridMonth")) {
      alertify.warning('No se pueden agendar citas en fechas y horas pasadas');
      return;
    }

    if ("dayGridMonth" !== calendarMode) {
      // Si es vista mensual, no permitir selección de días fuera del horario laboral
      const startHour = calendar.currentData.calendarOptions.businessHours.startTime;
      const endHour = calendar.currentData.calendarOptions.businessHours.endTime;
      const clickedHour = clickedDate.getHours();
      
      if (clickedHour < parseInt(startHour.split(':')[0]) || clickedHour >= parseInt(endHour.split(':')[0])) {
        alertify.warning('No se pueden agendar citas fuera del horario laboral');
        return;
      }
      
    }

    // Verificar si hay demasiados eventos en la fecha seleccionada
    const eventsOnDay = calendar.getEvents().filter(event => {
      const eventDate = new Date(event.start);
      return eventDate.toDateString() === clickedDate.toDateString();
    });

    // Verificar cuántas citas tiene ya en ese día (opcional)
    const appointmentsCount = eventsOnDay.filter(event => event.extendedProps.status !== "diary" && event.extendedProps.status !== "CT" && event.extendedProps.status !== "CP").length;
    if (appointmentsCount >= 1) {
      alertify.warning('Ya tienes el máximo de citas permitidas para este día');
      return;
    }

    // Si deseas mostrar cuántas citas tiene
    if (appointmentsCount > 0) {
      alertify.message(`Ya tienes ${appointmentsCount} cita(s) programada(s) para este día`);
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
      showAppointmentDetails(event.extendedProps.primary_id, event.extendedProps);
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
  document.getElementById('appointmentNotes').textContent = details.addNotes || 'Sin notas';
  
  // Configurar botones según el estado
  const btnCancel = document.getElementById('btnCancelAppointment');
  if (details.status === 'PE' || details.status === 'CO') {
    btnCancel.classList.remove('d-none');
    btnCancel.onclick = () => confirmCancelAppointment(appointmentId);
  } else {
    btnCancel.classList.add('d-none');
  }
  
  // Mostrar botón de enlace a videollamada si es relevante
  const btnJoinCall = document.getElementById('btnJoinVideoCall');
  if (details.modality === 'video_call' && details.video_url && (details.status === 'CO')) {
    btnJoinCall.classList.remove('d-none');
    btnJoinCall.href = details.video_url;
  } else {
    btnJoinCall.classList.add('d-none');
  }
  
  // Mostrar el modal
  appointmentDetailModal.show();
}

// Función para abrir modal de nueva cita
function openAppointmentModal(dateStr) {
  // Limpiar selecciones previas
  document.getElementById('appointmentDate').value = dateStr;
  document.getElementById('reasonText').value = '';
  document.getElementById('modalitySelect').value = 'IP';
  
  // Si tienes un select para terapeutas, puedes cargarlo aquí
  loadTherapists();
  
  appointmentModal.show();
}

// Función para cargar terapeutas disponibles
function loadTherapists() {
  const selectTherapist = document.getElementById('therapistSelect');
  const selectTime = document.getElementById('timeSelect');
  const date = document.getElementById('appointmentDate').value;
  
  // Limpiar opciones previas
  selectTherapist.innerHTML = '<option value="">Seleccione un terapeuta...</option>';
  selectTime.innerHTML = '<option value="">Seleccione un horario...</option>';
  
  // Mostrar indicador de carga
  selectTherapist.disabled = true;
  selectTime.disabled = true;

  $.ajax({
    url: routeBase + 'patient/appointments/getAvailableTherapists',
    type: 'GET',
    data: { date },
    dataType: 'json',
    beforeSend: function() {
      selectTherapist.disabled = true;
    },
    success: function(data) {
      if (data.status && data.therapists.length > 0) {
        dataTherapist = data.therapists;
        // Añadir opciones de terapeutas
        data.therapists.forEach(therapist => {
          const option = document.createElement('option');
          option.value = therapist.id;
          option.textContent = therapist.full_name;
          selectTherapist.appendChild(option);
        });
      } else {
        // No hay terapeutas disponibles
        const option = document.createElement('option');
        option.disabled = true;
        option.textContent = 'No hay terapeutas disponibles en esta fecha';
        selectTherapist.appendChild(option);
      }
    },
    error: function(xhr, status, error) {
      console.error('Error al cargar terapeutas:', error);
      alertify.error('Error al cargar terapeutas disponibles');
    },
    complete: function() {
      selectTherapist.disabled = false;
    }
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
  $.ajax({
    url: generalBaseAppointment + 'cancel/' + appointmentId,
    type: 'PUT',
    dataType: 'json',
    data: {
      status: 'CP'
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
}

function getAppointments() {
  const listAppoinment = document.getElementById('listAppoinment');
  listAppoinment.innerHTML = ''; // Limpiar lista de citas
  $.ajax({
    url: generalBaseAppointment + 'getAppointments',
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

document.addEventListener('DOMContentLoaded', function() {
  
  calendar.render();
  getAppointments();
  
  // Manejo del formulario de creación de cita
  document.getElementById('formCreateAppointment')?.addEventListener('submit', function(e) {
    e.preventDefault();

    if ($(this).valid()) {
      // Recoger datos del formulario
      const formData = new FormData(this);
      
      // Enviar solicitud
      $.ajax({
        url: generalBaseAppointment + 'Create',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(data) {
          if (data.status) {
            alertify.success(data.message || 'Cita agendada correctamente');
            calendar.refetchEvents();
            getAppointments();
            appointmentModal.hide();
          } else {
            alertify.error(data.message || 'Error al agendar la cita');
          }
        },
        error: function(xhr, status, error) {
          console.error('Error:', error);
          alertify.error('Error al procesar la solicitud');
        }
      });

    }
    
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

    slotTherapist = dataTherapist.find(therapist => therapist.id == therapistId);

    if (slotTherapist.timeSlots && slotTherapist.timeSlots.length > 0) {
      let slotCont = 0;
      // Limpiar opciones previas
      timeSelect.innerHTML = '<option value="">Seleccione un horario...</option>';

      slotTherapist.timeSlots.forEach(slot => {
        if (slot.available) {
          slotCont++;
          const option = document.createElement('option');
          option.value = slot.start;
          option.textContent = slot.strHour;
          if (slot.selected) {
            option.selected = true;
          }
          timeSelect.appendChild(option);
        }
      });

      if (slotCont === 0) {
        const option = document.createElement('option');
        option.disabled = true;
        option.textContent = 'No hay horarios disponibles';
        timeSelect.appendChild(option);
      }

    } else {
      timeSelect.innerHTML = '<option value="">No hay horarios disponibles</option>';
      timeSelect.disabled = true;
      return;
    }
    
    timeSelect.disabled = false;
  });
});