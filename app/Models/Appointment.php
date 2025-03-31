<?php

namespace App\Models;

use App\Entities\Appointment as EntitiesAppointment;
use CodeIgniter\Model;

class Appointment extends Model
{
	protected $table            = 'Appointment';
	protected $primaryKey       = 'id';
	protected $useAutoIncrement = true;
	protected $returnType       = EntitiesAppointment::class;
	protected $useSoftDeletes   = false;
	protected $protectFields    = true;
	protected $allowedFields    = [
		'patient_id', 
		'therapist_id', 
		'status', 
		'modality',
		'appointment_date',
		'appointment_time',
		'video_url',
		'notes'
	];

	protected bool $allowEmptyInserts = false;
	protected bool $updateOnlyChanged = true;

	protected array $casts = [
		'id' => 'integer',
		'patient_id' => 'integer',
		'therapist_id' => 'integer',
		'created_at' => 'datetime',
		'updated_at' => 'datetime',
	];
	protected array $castHandlers = [];

	// Dates
	protected $useTimestamps = false;
	protected $dateFormat    = 'datetime';
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';
	protected $deletedField  = 'deleted_at';

	// Validation
	protected $validationRules      = [
		'id'         => "permit_empty|is_natural_no_zero",
		'patient_id' => 'required|numeric|min_length[1]|is_not_unique[User.id]',
		'therapist_id'	=> 'required|numeric|min_length[1]|is_not_unique[User.id]',
		'status'    => 'required|string|min_length[1]|max_length[2]',
		'modality'  => 'required|string|min_length[1]|max_length[2]',
		'appointment_date'  => 'required|valid_date[Y-m-d]',
		'appointment_time'  => 'required|valid_date[H:i:s]',
		'video_url' => 'permit_empty|string|max_length[255]',
		'notes' => 'required|string|min_length[1]|max_length[1000]',
	];
	protected $validationMessages   = [];
	protected $skipValidation       = false;
	protected $cleanValidationRules = true;

	// Callbacks
	protected $allowCallbacks = true;
	protected $beforeInsert   = [];
	protected $afterInsert    = [];
	protected $beforeUpdate   = [];
	protected $afterUpdate    = [];
	protected $beforeFind     = [];
	protected $afterFind      = [];
	protected $beforeDelete   = [];
	protected $afterDelete    = [];

	public function changeStatus(int $id, string $status)
	{
		$appointment = $this->find($id);
		
		if (!$appointment) {
			return false;
		}
		
		
		return $this->update($id, ['status' => $status]);
	}
}
