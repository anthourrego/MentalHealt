<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Appointment extends Entity
{
	protected $attributes = [
		'id' => null,
		'patient_id' => null,
		'therapist_id' => null,
		'status' => null,
		'modality' => null,
		'appointment_date' => null,
		'appointment_time' => null,
		'video_url' => null,
		'notes' => null,
		'created_at' => null,
		'updated_at' => null,
		'deleted_at' => null,
	];
	protected $datamap = [];
	protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
	protected $casts   = [
		'patient_id' => 'integer',
		'therapist_id' => 'integer',
		'status' => 'string',
		'modality' => 'string',
		'appointment_date' => 'date',
		'appointment_time' => 'time',
		'video_url' => 'string',
		'notes' => 'string',
		'created_at' => 'datetime',
		'updated_at' => 'datetime',
	];
}