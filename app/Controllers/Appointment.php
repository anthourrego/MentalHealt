<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Appointment as ModelsAppointment;
use App\Models\DairyJournal As ModelsDairyJournal;
use CodeIgniter\API\ResponseTrait;

class Appointment extends BaseController
{
	use ResponseTrait; // Para formatear respuestas JSON adecuadamente
	protected $appointmentModel;
	protected $patient_id;
	protected $dairyJournalModel;

	public function __construct()
	{
		$this->appointmentModel = new ModelsAppointment();
		$this->dairyJournalModel = new ModelsDairyJournal();
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
				'id' => $event->id,
				'title' => $event->title ?? 'Cita',
				'start' => $event->appointment_date . 'T' . $event->appointment_time,
				'color' => $event->status == 'confirmed' ? '#28a745' : '#ffc107',
				'status' => $event->status
			];
		}

		$dialy = $this->dairyJournalModel->where('patient_id', $this->patient_id)->findAll();
		foreach ($dialy as $event) {
			$formattedEvents[] = [
				'id' => $event->id,
				'title' => 'Diario',
				'start' => $event->entry_date . 'T' . $event->entry_hour,
				'color' => '#007bff',
				'status' => 'diary'
			];
		}

		return $this->respond($formattedEvents, 200);
	}
}
