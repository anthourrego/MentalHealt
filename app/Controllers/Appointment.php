<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Appointment as ModelsAppointment;
use App\Models\DairyJournal As ModelsDairyJournal;
use App\Models\User As ModelsUser;
use CodeIgniter\API\ResponseTrait;

class Appointment extends BaseController
{
	use ResponseTrait; // Para formatear respuestas JSON adecuadamente
	protected $appointmentModel;
	protected $patient_id;
	protected $dairyJournalModel;
	protected $userModel;

	public function __construct()
	{
		$this->appointmentModel = new ModelsAppointment();
		$this->dairyJournalModel = new ModelsDairyJournal();
		$this->userModel = new ModelsUser();
		$this->patient_id = session()->get('id');
	}

	public function index()
	{
		$this->content['title'] = "Inicio";
		$this->content['view'] = "Patient/appointment";

		$this->LMoment();
		$this->LTempusDominus();
		$this->LBootstrapSwitch();
		$this->LJQueryValidation();
		$this->LFullCalendar();
		$this->LSelect2();
		$this->LJQueryValidation();

		$this->content['css_add'][] = [
			'Patient/dailyJournal.css'
		];

		$this->content['js_add'][] = [
			'Patient/appointment.js',
			'Patient/dailyJournal.js'
		];
		
		$this->content["dashboard"] = true;

		return view('UI/viewDefault', $this->content);
	}

	public function getEvents()
	{
		$events = $this->appointmentModel->where('patient_id', $this->patient_id)->findAll();

		$formattedEvents = [];
		foreach ($events as $event) {
			$formattedEvents[] = [
				'id' => "C".$event->id,
				'title' => 'Cita',
				'start' => $event->appointment_date . 'T' . $event->appointment_time,
				'color' => $event->status == 'CO' ? '#28a745' : '#ffc107',
				'status' => $event->status
			];
		}

		$dialy = $this->dairyJournalModel->where('patient_id', $this->patient_id)->findAll();
		foreach ($dialy as $event) {
			$formattedEvents[] = [
				'id' => "D".$event->id,
				'title' => 'Diario',
				'start' => $event->entry_date . 'T' . $event->entry_hour,
				'color' => '#007bff',
				'status' => 'diary',
				'primary_id' => $event->id,
				'content' => $event->content,
				'entry_date' => $event->entry_date,
				'entry_hour' => $event->entry_hour,
				'mood' => $event->mood,
				'private_entry' => $event->private_entry,
			];
		}

		return $this->respond($formattedEvents, 200);
	}

	public function getAvailableTherapists() {
		$resp = [
			'status' => true,
			'therapists' => []
		];

		$therapists = $this->userModel->where('profile', 2)->findAll();

		$formattedTherapists = [];
		foreach ($therapists as $therapist) {
			$formattedTherapists[] = [
				'id' => $therapist->id,
				'name' => $therapist->first_name,
				'last_name' => $therapist->last_name,
				'full_name' => $therapist->getFullName(),
			];
		}

		$resp["therapists"] = $formattedTherapists;

		return $this->respond($resp, status: 200);
	}

	public function getAvailableTimeSlots() {
		$resp = [
			'status' => false,
			'message' => 'No se encontraron horarios disponibles'
		];

		$arrayHours = [
			["start" => "08:00:00", "end" => "08:59:00", "available" => true],
			["start" => "09:00:00", "end" => "09:59:00", "available" => true],
			["start" => "10:00:00", "end" => "10:59:00", "available" => true],
			["start" => "11:00:00", "end" => "11:59:00", "available" => true],
			["start" => "12:00:00", "end" => "12:59:00", "available" => true],
			["start" => "13:00:00", "end" => "13:59:00", "available" => true],
			["start" => "14:00:00", "end" => "14:59:00", "available" => true],
			["start" => "15:00:00", "end" => "15:59:00", "available" => true],
			["start" => "16:00:00", "end" => "16:59:00", "available" => true],
			["start" => "17:00:00", "end" => "17:59:00", "available" => true]
		];

		$dataRequest = (object) $this->request->getGet();

		$dataHours = $this->appointmentModel->where('therapist_id', $dataRequest->therapist_id)
			->where('appointment_date', $dataRequest->date)
			->findAll();
		//Creamos un array con los horarios disponibles
		foreach ($arrayHours as $key => $hour) {
			foreach ($dataHours as $dataHour) {
				if ($dataHour->appointment_time == $hour["start"]) {
					$arrayHours[$key]["available"] = false;
					break;
				}
			}
		}

		$resp = [
			'status' => true,
			'timeSlots' => $arrayHours
		];

		return $this->respond($resp, status: 200);
	}
}
