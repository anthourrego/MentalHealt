<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Appointment extends BaseController
{
	public function index()
	{
		$this->content['title'] = "Mis Citas";
		$this->content['view'] = "Patient/appointment";

		$this->LFullCalendar();

		$this->content['js_add'][] = [
			'Patient/Appointment.js'
		];

		return view('UI/viewDefault', $this->content);
	}
}
