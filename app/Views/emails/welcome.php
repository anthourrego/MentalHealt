<?php $content = '
<h3>¡Bienvenido a MentalHealth!</h3>

<p>Hola <strong>' . ($name ?? 'Usuario') . '</strong>,</p>

<p>Tu cuenta ha sido creada exitosamente. Ahora puedes acceder a nuestra plataforma para gestionar tus citas y comunicarte con tu terapeuta.</p>

<p>Tus datos de acceso son:</p>
<ul>
    <li><strong>Usuario:</strong> ' . ($email ?? '') . '</li>
    <li><strong>Contraseña:</strong> La contraseña que configuraste durante el registro</li>
</ul>

<p>Haz clic en el siguiente botón para confirmar el correo e iniciar sesión:</p>

<p style="text-align: center;">
    <a href="' . base_url('confirmEmail?q=' . urlencode($encripted_email)) . '" class="btn">Confirmar correo</a>
</p>

<p>Si tienes alguna pregunta o necesitas asistencia, no dudes en contactarnos.</p>

<p>¡Gracias por confiar en MentalHealth!</p>
'; ?>

<?= view('emails/layout', ['subject' => $subject ?? 'Bienvenido a MentalHealth', 'content' => $content]) ?>