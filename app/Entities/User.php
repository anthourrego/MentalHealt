<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class User extends Entity
{
    /**
     * @var array Atributos que pueden ser asignados masivamente
     */
    protected $attributes = [];

    /**
     * @var array Campos que se deben convertir a tipos nativos
     */
    protected $casts = [
        'id' => 'integer',
        'profile' => 'integer',
        'status' => 'integer',
        'email_confirm' => 'integer',
        'last_login' => '?datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * @var array Getters para convertir valores específicos cuando se accede a ellos
     */
    protected $datamap = [];

    /**
     * @var array Setters para manipular valores específicos al asignarlos
     */
    protected $dates = ['last_login', 'created_at', 'updated_at'];

    /**
     * @var bool Determina si las fechas deben ser convertidas a instancias de DateTime
     */
    protected $castAsJson = [];

    /**
     * Método para cifrar la contraseña antes de guardarla
     * 
     * @param string $password Contraseña en texto plano
     * @return User
     */
    public function setPassword(string $password)
    {
        $this->attributes['password'] = password_hash($password, PASSWORD_DEFAULT);
        return $this;
    }

    /**
     * Método para verificar si una contraseña coincide con la almacenada
     * 
     * @param string $password Contraseña en texto plano para verificar
     * @return bool
     */
    public function verifyPassword(string $password)
    {
        return password_verify($password, $this->attributes['password']);
    }

    /**
     * Devuelve el nombre completo del usuario
     * 
     * @return string
     */
    public function getFullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Determina si el usuario es administrador
     * 
     * @return bool
     */
    public function isAdmin()
    {
        return $this->profile === 1;
    }

    /**
     * Determina si el usuario es terapeuta
     * 
     * @return bool
     */
    public function isTherapist()
    {
        return $this->profile === 2;
    }

    /**
     * Determina si el usuario es paciente
     * 
     * @return bool
     */
    public function isPatient()
    {
        return $this->profile === 3;
    }

    /**
     * Determina si el usuario está activo
     * 
     * @return bool
     */
    public function isActive()
    {
        return $this->status === 1;
    }

    /**
     * Determina si el email ha sido confirmado
     * 
     * @return bool
     */
    public function isEmailConfirmed()
    {
        return $this->email_confirm === 1;
    }
}