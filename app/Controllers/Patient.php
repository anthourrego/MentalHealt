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

	public function index($therapistMode = 0, $patient_id = null)
	{
		$this->content['title'] = "Mi Diario";
		$this->content['view'] = "Patient/Index";

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

		$this->content["therapistMode"] = $therapistMode;
		if ($therapistMode == 1) {
			session()->set('patient_id', $patient_id);
		}

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

		$entryData = [
			'patient_id' => $patientId,
			'mood' => (int) $postData->mood,
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
		$include = true;
		$patientId = session()->get('id');
		if (session()->has('patient_id')) {
			$patientId = session()->get('patient_id');
			$include = false;
		}

		// Obtener entradas del diario con paginación
		$entries = $this->dairyJournalModel->getPatientJournals($patientId, $include);

		return $this->respond([
			'status' => true,
			'entries' => $entries
		]);
	}

	public function deleteEntry($idEntry) {
		$resp = [
			'status' => false,
			'message' => 'No se pudo eliminar entrada'
		];

		$entry = $this->dairyJournalModel->delete($idEntry);

		if ($entry) {
			$resp = [
				'status' => true,
				'message' => 'Entrada eliminada correctamente'
			];
			return $this->respond($resp, 200);
		}

		return $this->respond($resp, 400);
	}

	public function updateEntry() {
		$dataRequest = (object) $this->request->getRawInput();
		$resp = [
			'status' => false,
			'message' => 'No se pudo actualizar la entrada'
		];
		
		// Verificar que tenemos el ID de la entrada
		if (!isset($dataRequest->diaryEntryId) || empty($dataRequest->diaryEntryId)) {
			$resp['message'] = 'ID de entrada no válido';
			return $this->respond($resp, 400);
		}
		
		// Preparar datos para actualizar
		$dateDiary = date('Y-m-d', strtotime(str_replace('/', '-', $dataRequest->dateDiary)));
		$hourDiary = date('H:i:s', strtotime($dataRequest->hourDiary));

		$entryData = [
			'mood' => (int) $dataRequest->mood,
			'content' => $dataRequest->content,
			'entry_date' => $dateDiary,
			'entry_hour' => $hourDiary,
			'private_entry' => isset($dataRequest->private_entry) ? $dataRequest->private_entry : 0
		];

		// Actualizar la entrada
		$updated = $this->dairyJournalModel->update($dataRequest->diaryEntryId, $entryData);

		if ($updated) {
			$resp = [
				'status' => true,
				'message' => 'Entrada actualizada correctamente'
			];
			return $this->respond($resp, 200);
		} else {
			$resp['errorsList'] = listErrors($this->dairyJournalModel->errors());
		}

		return $this->respond($resp, 400);
	}
}
