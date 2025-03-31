<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\Appointment as ModelsAppointment;

class Therapist extends BaseController
{
	use ResponseTrait; // Para formatear respuestas JSON adecuadamente
	protected $appointmentModel;
	protected $therapist_id;

	public function __construct()
	{
		$this->appointmentModel = new ModelsAppointment();
		$this->therapist_id = session()->get('id');
	}

	public function index()
	{
		$this->content['title'] = "Inicio";
		$this->content['view'] = "Therapist/Index";

		$this->LMoment();
		$this->LTempusDominus();
		$this->LJQueryValidation();
		$this->LFullCalendar();
		$this->LSelect2();
		$this->LJQueryValidation();

		$this->content['css_add'][] = [
			'Patient/dailyJournal.css'
		];

		$this->content['js_add'][] = [
			'Therapist/appointment.js',
		];
		
		return view('UI/viewDefault', $this->content);
	}

	public function getEvents()
	{
		$dataGet = (object) $this->request->getGet();

		$this->appointmentModel->select("
				Appointment.id,
				Appointment.patient_id,
				Appointment.status,
				Appointment.modality,
				Appointment.appointment_date,
				Appointment.appointment_time,
				Appointment.video_url,
				Appointment.notes,
				Appointment.notes_therapist,
				CONCAT(U.first_name, ' ', U.last_name) AS patientName,
			")->join("user U", "Appointment.patient_id = U.id")
			->where('Appointment.therapist_id', $this->therapist_id);

		if (isset($dataGet->start) && isset($dataGet->end)) {
			$this->appointmentModel->where('Appointment.appointment_date >=', $dataGet->start)
				->where('Appointment.appointment_date <=', $dataGet->end);
		}

		if (isset($dataGet->type) && $dataGet->type == 'list') {
			$this->appointmentModel->where("Appointment.appointment_date", date("Y-m-d"))
			  ->whereNotIn('Appointment.status', ["CT", "CC", "CP", "NS"]);
		}

		$events = $this->appointmentModel->findAll();

		$formattedEvents = [];
		foreach ($events as $event) {
			$formattedEvents[] = [
				'id' => $event->id,
				'title' => $event->patientName,
				'start' => $event->appointment_date . 'T' . $event->appointment_time,
				'color' => $event->status == 'CO' ? '#28a745' : '#ffc107',
				'status' => $event->status,
				'formattedDateTime' => date("d/m/Y h:i a", strtotime($event->appointment_date . ' ' . $event->appointment_time)),
				'modality' => $event->modality,
				'primary_id' => $event->id,
				'addNotes' => $event->notes,
				'notes_therapist' => $event->notes_therapist,
				'patientName' => $event->patientName,
				'video_url' => $event->video_url,
				'currentDate' => (date("Y-m-d") == $event->appointment_date) ? true : false,
			];
		}

		return $this->respond($formattedEvents, 200);
	}
}
