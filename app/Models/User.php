<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\User as UserEntities;

class User extends Model
{
	protected $table            = 'User';
	protected $primaryKey       = 'id';
	protected $useAutoIncrement = true;
	protected $returnType       = UserEntities::class;
	protected $useSoftDeletes   = false;
	protected $protectFields    = true;
	protected $allowedFields    = [
		'email', 
		'password', 
		'first_name', 
		'last_name', 
		'profile', 
		'status', 
		'last_login',
		'email_confirm'
	];

	protected bool $allowEmptyInserts = false;
	protected bool $updateOnlyChanged = true;

	// Registrar el handler de casteo personalizado

	protected array $casts = [
		'id' => 'integer',
		'profile' => 'integer',
		'status' => 'integer',
		'email_confirm' => 'integer',
		'last_login' => '?datetime',
		'created_at' => 'datetime',
		'updated_at' => 'datetime',
	];

	// Dates
	protected $useTimestamps = true;
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';
	protected $deletedField  = 'deleted_at';

	// Validation
	protected $validationRules      = [
		'id'         => "permit_empty|is_natural_no_zero",
		'email'      => 'required|valid_email|is_unique[User.email,id,{id}]',
		'password'   => 'required|min_length[8]',
		'first_name' => 'required|alpha_space|min_length[2]|max_length[255]',
		'last_name'  => 'required|alpha_space|min_length[2]|max_length[255]',
		'profile'    => 'required|integer|in_list[1,2,3]',
		'status'     => 'required|integer|in_list[0,1]',
		'email_confirm' => 'required|integer|in_list[0,1]'
	];
	protected $validationMessages = [
		'email' => [
			'required' => 'El correo electrónico es obligatorio',
			'valid_email' => 'Debe ingresar un correo electrónico válido',
			'is_unique' => 'Este correo electrónico ya está registrado'
		],
		'password' => [
			'required' => 'La contraseña es obligatoria',
			'min_length' => 'La contraseña debe tener al menos 8 caracteres'
		],
		'first_name' => [
			'required' => 'El nombre es obligatorio',
			'alpha_space' => 'El nombre solo puede contener letras y espacios',
			'min_length' => 'El nombre debe tener al menos 2 caracteres'
		],
		'last_name' => [
			'required' => 'El apellido es obligatorio',
			'alpha_space' => 'El apellido solo puede contener letras y espacios',
			'min_length' => 'El apellido debe tener al menos 2 caracteres'
		],
		'profile' => [
			'required' => 'El perfil es obligatorio',
			'in_list' => 'El perfil debe ser administrador, terapeuta o paciente'
		],
		'status' => [
			'required' => 'El estado es obligatorio',
			'in_list' => 'El estado debe ser activo o inactivo'
		]
	];
	protected $skipValidation       = false;
	protected $cleanValidationRules = true;

	// Callbacks
	protected $allowCallbacks = true;
	protected $beforeInsert   = ["hashPassword"];
	protected $afterInsert    = [];
	protected $beforeUpdate   = ["hashPassword"];
	protected $afterUpdate    = [];
	protected $beforeFind     = [];
	protected $afterFind      = [];
	protected $beforeDelete   = [];
	protected $afterDelete    = [];

	/**
	 * Método para cifrar la contraseña antes de guardarla en la base de datos
	 */
	protected function hashPassword(array $data)
	{
		if (! isset($data['data']['password'])) {
			return $data;
		}

		$data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT, array("cost" => 15));
		
		return $data;
	}

	/**
	 * Método para autenticar un usuario
	 */
	public function authenticate(string $email, string $password)
	{   
		$user = $this->where('email', $email)
			->where('status', 1)
			->first();

		if ($user && password_verify($password, $user->password)) {
			// Actualizar último inicio de sesión
			$now = \CodeIgniter\I18n\Time::now();
			$result = $this->update($user->id, ['last_login' => $now]);
			
			// Verificar resultado de la actualización (opcional)
			if (!$result) {
				log_message('error', 'No se pudo actualizar last_login para el usuario ' . $user->id);
			}
			return $user;
		}

		return false;
	}

	/**
	 * Obtener usuarios por perfil
	 */
	public function getByProfile(int $profile)
	{
		return $this->where('profile', $profile)
					->where('status', 1)
					->findAll();
	}

	/**
	 * Obtener cantidad usuarios por perfil
	 */
	public function countByProfile(int $profile = null, int $status = 1)
	{
		if (!is_null($profile)) {
			$this->where('profile', $profile);
		}

		if (!is_null($status)) {
			$this->where('status', $status);
		}
					
		return $this->countAllResults();
	}

	/**
	 * Activar o desactivar un usuario
	 */
	public function toggleStatus(int $userId, int $status = null)
	{
		$user = $this->find($userId);
		
		if (!$user) {
			return false;
		}
		
		$newStatus = $status ?? ($user->status == 1 ? 0 : 1);
		
		return $this->update($userId, ['status' => $newStatus]);
	}

	/**
	 * Confirmar el correo electrónico de un usuario
	 */
	public function confirmEmail(int $userId)
	{
		return $this->update($userId, ['email_confirm' => 1]);
	}

	/**
	 * Check if email is valid
	 */
	public function isValidEmail(string $email, int $id): bool
	{
		$this->where('email', $email);

		if (!is_null($id) && $id > 0) {
			$this->where('id !=', $id);
		}
					 
		$user = $this->first();

		return $user !== null;
	}
}
