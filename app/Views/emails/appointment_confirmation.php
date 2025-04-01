<?php $content = '
<h3>Confirmación de Cita</h3>

<p>Hola <strong>' . ($name ?? 'Usuario') . '</strong>,</p>

<p>Te confirmamos que tu cita ha sido agendada exitosamente.</p>

<p><strong>Detalles de la Cita:</strong></p>
<ul>
    <li><strong>Fecha:</strong> ' . ($date ?? 'No especificada') . '</li>
    <li><strong>Hora:</strong> ' . ($time ?? 'No especificada') . '</li>
    <li><strong>Terapeuta:</strong> ' . ($therapist ?? 'No especificado') . '</li>
    <li><strong>Modalidad:</strong> ' . ($modality ?? 'No especificada') . '</li>
</ul>

' . ($modality === 'Videollamada' ? '<p><strong>Enlace para la videollamada:</strong> <a href="' . ($link ?? '#') . '">' . ($link ?? 'Enlace a la videollamada') . '</a></p>' : '') . '

<p>Recuerda estar disponible 5 minutos antes de la hora programada.</p>

<p>Si necesitas modificar o cancelar tu cita, puedes hacerlo a través de nuestra plataforma con al menos 24 horas de anticipación.</p>

<p style="text-align: center;">
    <a href="' . base_url('patient/appointments') . '" class="btn">Ver Mis Citas</a>
</p>

<p>¡Gracias por confiar en MentalHealth!</p>
'; ?>

<?= view('emails/layout', ['subject' => $subject ?? 'Confirmación de Cita', 'content' => $content]) ?>