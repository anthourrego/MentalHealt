<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\DialyJournal as DialyJournalEntities;

class DairyJournal extends Model
{
	protected $table            = 'dailyjournal';
	protected $primaryKey       = 'id';
	protected $useAutoIncrement = true;
	protected $returnType       = DialyJournalEntities::class;
	protected $useSoftDeletes   = false;
	protected $protectFields    = true;
	protected $allowedFields    = [
		'patient_id', 
		'mood', 
		'content', 
		'entry_date',
		'entry_hour',
		'private_entry'
	];

	protected bool $allowEmptyInserts = false;
	protected bool $updateOnlyChanged = true;

	protected array $casts = [
		'id' => 'integer',
		'patient_id' => 'integer',
		'mood' => 'integer',
		'private_entry' => 'integer',
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
		'patient_id' => 'required|numeric|min_length[1]|is_not_unique[user.id]',
		'mood'   		 => 'required|integer|in_list[1,2,3,4,5]',
		'content'    => 'required|string|min_length[1]|max_length[1000]',
		'private_entry'  => 'permit_empty|integer|in_list[0,1]',
		'entry_date'  => 'required|valid_date[Y-m-d]',
		'entry_hour'  => 'required|valid_date[H:i:s]',
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

	/**
	 * Obtiene las entradas del diario de un paciente específico con detalles adicionales
	 */
	public function getPatientJournals(int $patientId, bool $includePrivate = true, $limit = 0, $offset = 0)

	{
		$this->where('patient_id', $patientId);

		if (!$includePrivate) {
			$this->where('private_entry', 0);
		}
		$this->orderBy('entry_date', 'DESC')
			->orderBy('entry_hour', 'DESC');

		if ($limit > 0) {
			$this->limit($limit, $offset);
		}

		$entries = $this->get()->getResult();

		return $entries;
	}
}
