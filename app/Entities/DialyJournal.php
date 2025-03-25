<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class DialyJournal extends Entity
{
	/**
	 * @var array Atributos que pueden ser asignados en masa
	 */
	protected $attributes = [
		'id' => null,
		'patient_id' => null,
		'mood' => null,
		'content' => null,
		'entry_date' => null,
		'entry_hour' => null,
		'private_entry' => 0,
		'created_at' => null,
		'updated_at' => null,
	];

	/**
	 * @var array Mapeo de nombres de propiedades a nombres de columnas
	 */
	protected $datamap = [];
	
	/**
	 * @var array Fechas a convertir a instancias DateTime
	 */
	protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
	
	/**
	 * @var array Tipos de cast para los atributos
	 */
	protected $casts = [
		'id' => 'integer',
		'patient_id' => 'integer',
		'mood' => 'integer',
		'content' => 'string',
		'private_entry' => 'integer',
		'created_at' => 'datetime',
		'updated_at' => 'datetime',
	];
}
