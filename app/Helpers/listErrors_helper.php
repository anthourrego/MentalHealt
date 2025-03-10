<?php

if (! function_exists('listErrors')) {
    /**
     * Formatea los errores de validación para mostrarlos en la vista.
     *
     * @param array $errors Array de errores de validación
     * @param string $format Formato de salida ('html', 'json', 'string', o 'bootstrap')
     * @param string $htmlClass Clase CSS para el contenedor de errores en formato HTML
     * 
     * @return string|array Los errores formateados según el formato especificado
     */
    function listErrors(array $errors = [], string $format = 'bootstrap', string $htmlClass = 'alert alert-danger'): string|array
    {
        // Si no hay errores, retornar cadena vacía
        if (empty($errors)) {
            return '';
        }

        // Formatos disponibles
        switch ($format) {
            case 'html':
                $output = '<div class="' . $htmlClass . '">';
                $output .= '<ul>';
                foreach ($errors as $error) {
                    $output .= '<li>' . esc($error) . '</li>';
                }
                $output .= '</ul>';
                $output .= '</div>';
                break;
            
            case 'bootstrap':
                $output = '<div class="' . $htmlClass . '">';
                $output .= '<ul class="mb-0">';
                foreach ($errors as $error) {
                    $output .= '<li>' . esc($error) . '</li>';
                }
                $output .= '</ul>';
                $output .= '</div>';
                break;

            case 'string':
                $output = '';
                foreach ($errors as $error) {
                    $output .= esc($error) . "\n";
                }
                break;

            case 'json':
                $output = $errors; // Devolver array para que se convierta a JSON
                break;

            default:
                $output = $errors;
                break;
        }

        return $output;
    }
}
