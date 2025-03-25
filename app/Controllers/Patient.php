<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DairyJournal;
use CodeIgniter\API\ResponseTrait;

class Patient extends BaseController
{
	use ResponseTrait; // Para formatear respuestas JSON adecuadamente
	protected $dairyJournalModel;

	public function __construct()
	{
		$this->dairyJournalModel = new DairyJournal();
	}

	public function index()
	{
		$this->content['title'] = "Mi Diario";
		$this->content['view'] = "Patient/index";

		$this->LMoment();
		$this->LTempusDominus();
		$this->LBootstrapSwitch();
		$this->LJQueryValidation();

		$this->content['css_add'][] = [
			'Patient/dailyJournal.css'
		];

		$this->content['js_add'][] = [
			'Patient/dailyJournal.js'		
		];

		return view('UI/viewDefault', $this->content);
	}

	public function saveDiary() {
		$postData = (object) $this->request->getPost();
		$patientId = session()->get('id');
		$resp = [
			'status' => false,
			'message' => 'No se pudo guardar la entrada'
		];
		
		// Preparar datos para guardar
		$dateDiary = date('Y-m-d', strtotime(str_replace('/', '-', $postData->dateDiary)));
		$hourDiary = date('H:i:s', strtotime($postData->hourDiary));
		$postData->mood = (int) $postData->mood;

		$entryData = [
			'patient_id' => $patientId,
			'mood' => $postData->mood,
			'content' => $postData->content,
			'entry_date' => $dateDiary,
			'entry_hour' => $hourDiary,
			'private_entry' => isset($postData->private_entry) ? $postData->private_entry : 0
		];

		$entry = $this->dairyJournalModel->insert($entryData);

		if ($entry && empty($this->dairyJournalModel->errors())) {
			$resp = [
				'status' => true,
				'message' => 'Entrada guardada correctamente'
			];
			return $this->respond($resp, 200);
		} else {
			$resp['errorsList'] = listErrors($this->dairyJournalModel->errors());
		}

		return $this->respond($resp, 400);
	}

	/**
     * Obtener entradas del diario
     */
    public function getEntries()
    {
			$patientId = session()->get('id');
			
			// Obtener entradas del diario con paginación
			$entries = $this->dairyJournalModel->getPatientJournals($patientId, true);

			return $this->respond([
				'status' => true,
				'entries' => $entries
			]);
    }
}
