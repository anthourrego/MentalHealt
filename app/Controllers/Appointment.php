<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Appointment extends BaseController
{
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
}
