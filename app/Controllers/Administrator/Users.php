<?php

namespace App\Controllers\Administrator;

use App\Controllers\BaseController;
use App\Models\User;
use CodeIgniter\API\ResponseTrait;
use \Hermawan\DataTables\DataTable;

class Users extends BaseController
{
	use ResponseTrait; // Para formatear respuestas JSON adecuadamente
	public $userModel;

	public function __construct()
	{
		$this->userModel = new User();
	}

	public function index()
	{
		$this->LDataTables();
		$this->LMoment();
		$this->LFancybox();
		$this->LJQueryValidation();

		$this->content['title'] = "Usuarios";
		$this->content['view'] = "Administrator/Users";

		$this->content['js_add'][] = [
			'users.js'
		];

		return view('UI/viewDefault', $this->content);
	}

	public function listaDT() {
		$postData = (object) $this->request->getPost();
		$status = $postData->status;
		
		$query = $this->db->table('User AS u')
			->select("
				u.id, 
				u.email, 
				u.first_name,
				u.last_name,
				concat(u.first_name, ' ', u.last_name) As full_name,
				u.profile,
				CASE u.profile
						WHEN '1' THEN 'Administrador'
						WHEN '2' THEN 'Terapista'
						WHEN '3' THEN 'Paciente'
						ELSE 'Sin perfil'
				END profileDesc,
				u.status, 
				CASE u.status
						WHEN '1' THEN 'Activo'
						WHEN '0' THEN 'Inactivo'
						ELSE 'Sin estado'
				END statusDesc, 
				u.last_login, 
				u.email_confirm,
				CASE email_confirm
						WHEN '1' THEN 'Confirmado'
						WHEN '0' THEN 'Sin confirmar'
						ELSE 'Sin confirmar'
				END email_confirmDesc,
				u.created_at,
				u.updated_at
			");

		if($status != "-1"){
			$query->where("u.status", $status);
		}

		return DataTable::of($query)->toJson(true);
	}

	public function validEmail()
	{	
		$dataPost = (object) $this->request->getPost();

		$resp = [
			'status' => false,
			'message' => 'El correo electrónico ya existe'
		];

		$user = $this->userModel->isValidEmail($dataPost->email, $dataPost->idUser);

		if (!$user) {
			$resp = [
				'status' => true,
				'message' => 'Correo electrónico disponible'
			];
		}

		return $this->respond($resp, 200);
	}

	public function delete($idUser) {
		$resp = [
			'status' => false,
			'message' => 'No se pudo eliminar el usuario'
		];

		$user = $this->userModel->delete($idUser);

		if ($user) {
			$resp = [
				'status' => true,
				'message' => 'Usuario eliminado correctamente'
			];
			return $this->respond($resp, 200);
		}

		return $this->respond($resp, 400);
	}
	public function changeStatus($idUser) {
		$dataRequest = (object) $this->request->getRawInput();
		$newStatus = $dataRequest->status == "1" ? "0" : "1";
		$resp = [
			'status' => false,
			'message' => 'No se pudo cambiar el estado del usuario'
		];

		$user = $this->userModel->toggleStatus($idUser, $newStatus);

		if ($user && empty($this->userModel->errors())) {
			$resp = [
				'status' => true,
				'message' => 'Estado del usuario cambiado correctamente'
			];
			return $this->respond($resp, 200);
		} else {
			$resp['errorsList'] = listErrors($this->userModel->errors());
		}

		return $this->respond($resp, 400);
	}

	public function foto($img = null){
		$filename = UPLOADS_USER_PATH ."{$img}.png"; //<-- specify the image  file
		//Si la foto no existe la colocamos por defecto
		if(is_null($img) || !file_exists($filename)){ 
			$filename = ASSETS_PATH . "img/noPhoto.png";
		}
		//$mime = mime_content_type($filename); //<-- detect file type
		header('Content-Length: '.filesize($filename)); //<-- sends filesize header
		header("Content-Type: image/png"); //<-- send mime-type header
		header("Content-Disposition: inline; filename='{$filename}';"); //<-- sends filename header
		readfile($filename); //<--reads and outputs the file onto the output buffer
		exit(); // or die()
	}

	public function create() {
		$dataPost = (object) $this->request->getPost();
		$resp = [
			'status' => false,
			'message' => 'No se pudo crear el usuario'
		];

		$data = [
			'email' => $dataPost->email,
			'first_name' => $dataPost->first_name,
			'last_name' => $dataPost->last_name,
			'profile' => $dataPost->profile,
			'status' => 1,
			"email_confirm" => 1,
			'password' => $dataPost->pass
		];

		$user = $this->userModel->insert($data);

		if ($user && empty($this->userModel->errors())) {
			$resp = [
				'status' => true,
				'message' => 'Usuario creado correctamente'
			];
			return $this->respond($resp, 200);
		} else {
			$resp['errorsList'] = listErrors($this->userModel->errors());
		}

		return $this->respond($resp, 400);
	}

	public function update($idUser){
		$dataRequest = (object) $this->request->getRawInput();
		$resp = [
			'status' => false,
			'message' => 'No se pudo actualizar el usuario'
		];

		$data = [
			'id' => $idUser,
			'email' => $dataRequest->email,
			'first_name' => $dataRequest->first_name,
			'last_name' => $dataRequest->last_name,
			'profile' => $dataRequest->profile
		];

		$user = $this->userModel->update($idUser, $data);

		if ($user && empty($this->userModel->errors())) {
			$resp = [
				'status' => true,
				'message' => 'Usuario actualizado correctamente'
			];
			return $this->respond($resp, 200);
		} else {
			$resp['errorsList'] = listErrors($this->userModel->errors());
		}

		return $this->respond($resp, 400);

	}

	public function changePassword($idUser) {
		$dataRequest = (object) $this->request->getRawInput();
		$resp = [
			'status' => false,
			'message' => 'No se pudo cambiar la contraseña del usuario'
		];

		$data = [
			'password' => $dataRequest->pass
		];

		$user = $this->userModel->update($idUser, $data);

		if ($user && empty($this->userModel->errors())) {
			$resp = [
				'status' => true,
				'message' => 'Contraseña del usuario cambiada correctamente'
			];
			return $this->respond($resp, 200);
		} else {
			$resp['errorsList'] = listErrors($this->userModel->errors());
		}

		return $this->respond($resp, 400);
	}
}
