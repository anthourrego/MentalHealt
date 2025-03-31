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
		$formattedEvents = $this->getAppointments(true);

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

	public function getAppointments($return = false)
	{
		$dataGet = (object) $this->request->getGet();

		$this->appointmentModel->select("
				Appointment.id,
				Appointment.therapist_id,
				Appointment.status,
				Appointment.modality,
				Appointment.appointment_date,
				Appointment.appointment_time,
				Appointment.video_url,
				Appointment.notes,
				CONCAT(U.first_name, ' ', U.last_name) AS therapistName,
			")->join("user U", "Appointment.therapist_id = U.id")
			->where('Appointment.patient_id', $this->patient_id);

		if (isset($dataGet->start) && isset($dataGet->end)) {
			$this->appointmentModel->where('Appointment.appointment_date >=', $dataGet->start)
				->where('Appointment.appointment_date <=', $dataGet->end);
		}

		if ($return === false) {
			$this->appointmentModel->whereIn("Appointment.status", ['PE', 'CO', 'CT']);
		}

		$events = $this->appointmentModel->findAll();
		
		$formattedEvents = [];
		foreach ($events as $event) {
			$formattedEvents[] = [
				'id' => "C".$event->id,
				'title' => 'Cita',
				'start' => $event->appointment_date . 'T' . $event->appointment_time,
				'color' => $event->status == 'CO' ? '#28a745' : '#ffc107',
				'status' => $event->status,
				'formattedDateTime' => date("d/m/Y h:i a", strtotime($event->appointment_date . ' ' . $event->appointment_time)),
				'modality' => $event->modality,
				'primary_id' => $event->id,
				'addNotes' => $event->notes,
				'therapistName' => $event->therapistName,
				'video_url' => $event->video_url,
			];
		}

		if ($return) {
			return $formattedEvents;
		} else {
			return $this->respond($formattedEvents, 200);
		}
	}

	public function getAvailableTherapists() {
		$dataRequest = (object) $this->request->getGet();

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
				'timeSlots' => [],
			];

			$slots = $this->getAvailableTimeSlots($therapist->id, $dataRequest->date, true);
			if ($slots["status"]) {
				$formattedTherapists[count($formattedTherapists) - 1]['timeSlots'] = $slots['timeSlots'];
			}
		}

		$resp["therapists"] = $formattedTherapists;

		return $this->respond($resp, status: 200);
	}

	public function getAvailableTimeSlots($therapist_id = null, $date = null, $return = false) {
		$dataRequest = (object) $this->request->getGet();
		$therapist_id = $dataRequest->therapist_id ?? $therapist_id;
		$date = $dataRequest->date ?? $date;
		$dateYmd = date("Y-m-d", strtotime($date));
		$dateHour = date("H", strtotime($date));

		$resp = [
			'status' => false,
			'message' => 'No se encontraron horarios disponibles'
		];
		
		if (is_null($therapist_id) || is_null($date)) {
			if ($return) {
				return $resp;
			} else {
				return $this->respond($resp,  400);
			} 
		}

		$arrayHours = [
			["start" => "08:00:00", "strHour" => "08:00 - 08:59 AM", "available" => true, "selected" => false],
			["start" => "09:00:00", "strHour" => "09:00 - 09:59 AM", "available" => true, "selected" => false],
			["start" => "10:00:00", "strHour" => "10:00 - 10:59 AM", "available" => true, "selected" => false],
			["start" => "11:00:00", "strHour" => "11:00 - 11:59 AM", "available" => true, "selected" => false],
			["start" => "12:00:00", "strHour" => "12:00 - 12:59 PM", "available" => true, "selected" => false],
			["start" => "13:00:00", "strHour" => "01:00 - 01:59 PM", "available" => true, "selected" => false],
			["start" => "14:00:00", "strHour" => "02:00 - 02:59 PM", "available" => true, "selected" => false],
			["start" => "15:00:00", "strHour" => "03:00 - 03:59 PM", "available" => true, "selected" => false],
			["start" => "16:00:00", "strHour" => "04:00 - 04:59 PM", "available" => true, "selected" => false],
			["start" => "17:00:00", "strHour" => "05:00 - 05:59 PM", "available" => true, "selected" => false]
		];


		$dataHours = $this->appointmentModel->where('therapist_id', $therapist_id)
			->where('appointment_date', $dateYmd)
			->findAll();
		
		//Creamos un array con los horarios disponibles
		foreach ($arrayHours as $key => $hour) {
			$hour = date("H", strtotime($hour["start"]));

			//Para dejar seleccionada la fecha actual
			if ($dateHour == $hour) {
				$arrayHours[$key]["selected"] = true;
			}

			foreach ($dataHours as $dataHour) {
				if ($dataHour->appointment_time == $hour["start"]) {
					$arrayHours[$key]["available"] = false;
					break;
				}
			}

			if ($dateYmd == date("Y-m-d") && $hour <= date("H")) {
				$arrayHours[$key]["available"] = false;
			}
		}

		$resp = [
			'status' => true,
			'timeSlots' => $arrayHours
		];

		if ($return) {
			return $resp;
		} else {
			return $this->respond($resp, status: 200);
		}
	}

	public function createAppointment() {
		$dataPost = (object) $this->request->getPost();
		$resp = [
			'status' => false,
			'message' => 'Error al crear la cita'
		];

		$appointmentData = [
			'patient_id' => $this->patient_id,
			'therapist_id' => $dataPost->therapist_id,
			'status' => 'CO',
			'modality' => $dataPost->modality,
			'appointment_date' => date("Y-m-d", strtotime($dataPost->date)),
			'appointment_time' => date("H:i:s", strtotime($dataPost->start_time)),
			'notes' => $dataPost->reason
		];

		$appointment = $this->appointmentModel->insert($appointmentData);

		if ($appointment && empty($this->appointmentModel->errors())) {
			$resp = [
				'status' => true,
				'message' => 'Cita creada correctamente'
			];
			return $this->respond($resp, 200);
		} else {
			$resp['errorsList'] = listErrors($this->appointmentModel->errors());
		}

		return $this->respond($resp, 400);
	}

	public function changeStatus($idAppointment) {
		$dataRequest = (object) $this->request->getRawInput();
		$resp = [
			'status' => false,
			'message' => 'No se pudo actualizar la cita'
		];

		$appointment = $this->appointmentModel->changeStatus($idAppointment, $dataRequest->status);

		if ($appointment && empty($this->appointmentModel->errors())) {
			$resp = [
				'status' => true,
				'message' => 'Cita actaulizada correctamente'
			];
			return $this->respond($resp, 200);
		} else {
			$resp['errorsList'] = listErrors($this->appointmentModel->errors());
		}

		return $this->respond($resp, 400);
	}
}
