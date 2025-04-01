<?php

namespace App\Services;

use CodeIgniter\Email\Email;
use CodeIgniter\Config\Services;

class EmailService
{
    protected $email;
    
    public function __construct()
    {
        $this->email = Services::email();
    }
    
    /**
     * Envía un correo electrónico
     *
     * @param string|array $to Destinatario(s)
     * @param string $subject Asunto
     * @param string $message Mensaje
     * @param array $attachments Archivos adjuntos
     * @return bool
     */
    public function send($to, string $subject, string $message, array $attachments = [])
    {
        // Configurar email
        $this->email->setTo($to);
        $this->email->setSubject($subject);
        $this->email->setMessage($message);
        
        // Añadir archivos adjuntos si existen
        if (!empty($attachments)) {
            foreach ($attachments as $attachment) {
                $this->email->attach($attachment);
            }
        }
        
        // Enviar email y devolver resultado
        if ($this->email->send()) {
            log_message('info', 'Email enviado correctamente a: ' . (is_array($to) ? implode(', ', $to) : $to));
            return true;
        } else {
            log_message('error', 'Error al enviar email: ' . $this->email->printDebugger(['headers']));
            return false;
        }
    }
    
    /**
     * Envía un correo con copia (CC)
     *
     * @param string|array $to Destinatario(s)
     * @param string|array $cc Copia(s)
     * @param string $subject Asunto
     * @param string $message Mensaje
     * @return bool
     */
    public function sendWithCC($to, $cc, string $subject, string $message)
    {
        $this->email->setTo($to);
        $this->email->setCC($cc);
        $this->email->setSubject($subject);
        $this->email->setMessage($message);
        
        if ($this->email->send()) {
            return true;
        } else {
            log_message('error', 'Error al enviar email CC: ' . $this->email->printDebugger(['headers']));
            return false;
        }
    }
    
    /**
     * Envía un correo con plantilla
     *
     * @param string|array $to Destinatario(s)
     * @param string $subject Asunto
     * @param string $template Nombre de la plantilla
     * @param array $data Datos para la plantilla
     * @return bool
     */
    public function sendTemplate($to, string $subject, string $template, array $data = [])
    {
        // Renderizar plantilla
        $message = view('emails/' . $template, $data);
        
        // Enviar email con la plantilla renderizada
        return $this->send($to, $subject, $message);
    }
}