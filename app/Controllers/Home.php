<?php

namespace App\Controllers;

use App\Models\User;
use App\Services\EmailService;
use CodeIgniter\API\ResponseTrait;

class Home extends BaseController
{

	use ResponseTrait; // Para formatear respuestas JSON adecuadamente
	protected $userModel;
    protected $emailService;

	public function __construct()
	{
		$this->userModel = new User();
        $this->emailService = new EmailService();
	}

	public function index()
	{
		if (session()->has("logged_in") && session()->get("logged_in")) {

			// Redirigir al dashboard si ya ha iniciado sesión
			switch (session()->get("profile")) {
				case 1:
					return redirect()->to(base_url("admin/"));
				case 2:
					return redirect()->to(base_url("therapist/"));
				case 3:
				default:
				return redirect()->to(base_url("patient/"));
			}
		} else {
			$this->LJQueryValidation();
			$this->content['title'] = "Inicio Sesión";
			$this->content['view'] = "login";
			$this->content['css_add'][] = [
				'login.css'
			];

			$this->content['js_add'][] = [
				'login.js'
			];

			return view('UI/viewSimple', $this->content);
		}
	}

    public function register() {
        if (session()->has("logged_in") && session()->get("logged_in")) {

			// Redirigir al dashboard si ya ha iniciado sesión
			switch (session()->get("profile")) {
				case 1:
					return redirect()->to(base_url("admin/"));
				case 2:
					return redirect()->to(base_url("therapist/"));
				case 3:
				default:
				return redirect()->to(base_url("patient/"));
			}
		} else {
			$this->LJQueryValidation();
			$this->content['title'] = "Registro";
			$this->content['view'] = "register";
			$this->content['css_add'][] = [
				'login.css'
			];

			$this->content['js_add'][] = [
				'register.js'
			];

			return view('UI/viewSimple', $this->content);
		}
    }

    public function Administrator() {
        $this->content['title'] = "Inicio";
        $this->content['view'] = "Administrator/Index";

        $this->content["countTherapist"] = $this->userModel->countByProfile(2);
        $this->content["countPatient"] = $this->userModel->countByProfile(3);

        return view('UI/viewDefault', $this->content);
    }

	/**
	 * Procesa el formulario de inicio de sesión mediante Ajax
	 */
	public function attemptLogin()
	{
		// Verificar si es una solicitud AJAX
		$rules = [
			'email' => 'required|valid_email',
			'password' => 'required|min_length[8]',
		];

		$messages = [
			'email' => [
				'required' => 'El correo electrónico es obligatorio',
				'valid_email' => 'Debe ingresar un correo electrónico válido',
			],
			'password' => [
				'required' => 'La contraseña es obligatoria',
				'min_length' => 'La contraseña debe tener al menos 8 caracteres',
			],
		];

		if (!$this->validate($rules, $messages)) {
			return $this->respond([
				'status' => 'error',
				'title' => 'Error de validación',
				'message' => 'Error de validación',
				'errors' => $this->validator->getErrors(),
				'errorsList' => listErrors($this->validator->getErrors())
			])->setStatusCode(400);
		}

		$email = $this->request->getPost('email');
		$password = $this->request->getPost('password');
		//$remember = $this->request->getPost('remeber');


		$user = $this->userModel->authenticate($email, $password);
		
		if (!$user) {
			return $this->respond([
				'status' => 'error',
				'message' => 'Correo electrónico o contraseña incorrectos'
			], 401);
		}

		if ($user->email_confirm == 0) {
			return $this->respond([
				'status' => 'error',
				'message' => 'Por favor, confirme su correo electrónico antes de iniciar sesión'
			], 401);
		}

		if ($user->status == 0) {
			return $this->respond([
				'status' => 'error',
				'message' => 'Su cuenta está desactivada. Contacte al administrador'
			], 401);
		}

			// Establecer datos de sesión
			$this->setUserSession($user);

			// Determinar URL de redirección según el perfil
			$redirectUrl = $this->getRedirectUrlByProfile($user->profile);

			// Responder con éxito y la URL de redirección
			return $this->respond([
					'status' => 'success',
					'message' => 'Inicio de sesión exitoso',
					'redirect' => $redirectUrl
			], 200);
	}

	/**
     * Obtiene la URL de redirección según el perfil del usuario
     */
    private function getRedirectUrlByProfile(int $profile): string
    {
			switch ($profile) {
				case 1:
					return base_url('admin/');
				case 2:
					return base_url('therapist/');
				case 3:
				default:
					return base_url('patient/');
			}
    }

	/**
	 * Establece los datos de sesión del usuario
	 */
	private function setUserSession($user, bool $remember = false)
	{
			$userData = [
					'id' => $user->id,
					'email' => $user->email,
					'first_name' => $user->first_name,
					'last_name' => $user->last_name,
                    'full_name' => $user->first_name . ' ' . $user->last_name,
					'profile' => $user->profile,
					'logged_in' => true,
			];

			session()->set($userData);

			// Actualizar última sesión
			//$this->userModel->update($user->id, ['last_login' => date('Y-m-d H:i:s')]);

			// Si está marcado "recordarme", establece una cookie
			/* if ($remember) {
					$this->setRememberMeCookie($user->id);
			} */
	}

    /**
     * Cierra la sesión del usuario - puede ser por Ajax o normal
     */
    public function logout()
    {
        // Eliminar cookie de "recordarme" si existe
        /* if (isset($_COOKIE['remember_token'])) {
            setcookie('remember_token', '', time() - 3600, '/');
            unset($_COOKIE['remember_token']);
        } */

        // Destruir la sesión
        session()->destroy();

        return $this->respond([
            'status' => 'success',
            'message' => 'Ha cerrado sesión correctamente',
            'redirect' => base_url('')
        ], 200);
    }

    /**
     * Procesa el registro de usuario mediante Ajax
     */
    public function attemptRegister()
    {

        $rules = [
            'first_name' => 'required|alpha_space|min_length[2]|max_length[255]',
            'last_name' => 'required|alpha_space|min_length[2]|max_length[255]',
            'email' => 'required|valid_email|is_unique[User.email]',
            'password' => 'required|min_length[8]',
            'password_confirm' => 'required|matches[password]',
        ];

        $messages = [
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
            'email' => [
                'required' => 'El correo electrónico es obligatorio',
                'valid_email' => 'Debe ingresar un correo electrónico válido',
                'is_unique' => 'Este correo electrónico ya está registrado'
            ],
            'password' => [
                'required' => 'La contraseña es obligatoria',
                'min_length' => 'La contraseña debe tener al menos 8 caracteres'
            ],
            'password_confirm' => [
                'required' => 'La confirmación de contraseña es obligatoria',
                'matches' => 'Las contraseñas no coinciden'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Error de validación',
                'errors' => $this->validator->getErrors()
            ], 400);
        }

        // Preparar los datos del usuario (por defecto es paciente)
        $userData = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'profile' => 3, // Paciente por defecto
            'status' => 1, // Activo por defecto
            'email_confirm' => 0, // Requiere confirmación de email
        ];

        // Guardar el usuario
        $userId = $this->userModel->insert($userData);

        if (!$userId) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Ocurrió un error al registrar su cuenta. Por favor, inténtelo nuevamente.'
            ], 500);
        }

        // Enviar correo de confirmación (requeriría implementación de envío de correos)
        //$this->sendConfirmationEmail($userData['email'], $userId);

        // Use the same encryption key from environment
        $key = getenv('encryption.key') ?: 'your-secure-key-here';
        $iv = substr(hash('sha256', $key), 0, 16);
        
        // Encrypt using same algorithm and parameters
        $encrypted = openssl_encrypt($this->request->getPost('email'), 'AES-256-CBC', $key, 0, $iv);
        $encodedData = base64_encode($encrypted);

        $this->emailService->sendTemplate(
            $this->request->getPost('email'),
            'Bienvenido a MentalHealth',
            'welcome',
            [
                'name' => $this->request->getPost('first_name') . ' ' . $this->request->getPost('last_name'),
                'email' => $this->request->getPost('email'),
                'encripted_email' => $encodedData
            ]
        );

        return $this->respond([
            'status' => 'success',
            'message' => 'Registro exitoso. Por favor revise su correo electrónico para confirmar su cuenta.'
        ], 200);
    }

    public function confirmEmail() {
        // Obtener el parámetro de datos de la URL
        $data = $this->request->getGet('q');

        // Decodificar y desencriptar el parámetro de datos
        $decodedData = base64_decode($data);
        $key = getenv('encryption.key') ?: 'your-secure-key-here';
        $iv = substr(hash('sha256', $key), 0, 16);
        $decrypted = openssl_decrypt($decodedData, 'AES-256-CBC', $key, 0, $iv);

        if ($decrypted === false) {
            return $this->showConfirmationResult(false, null, 'Enlace de verificación inválido');
        }

        $email = $decrypted;
        $user = $this->userModel->where('email', $email)->first();

        if (!$user) {
            return $this->showConfirmationResult(false, null, 'Usuario no encontrado');
        }

        // Actualizar estado de confirmación de email
        $this->userModel->update($user->id, ['email_confirm' => 1]);

        return $this->showConfirmationResult(true, $user);
    }

    /**
     * Muestra la pantalla de resultado de confirmación
     */
    private function showConfirmationResult($success, $user = null, $errorMessage = null, $expired = false)
    {
        $data = [
            'title' => 'Verificación de Email',
            'success' => $success,
            'expired' => $expired,
            'error_message' => $errorMessage,
            'user' => $user,
            'userId' => $user ? $user->id : null
        ];
        
        return view('emailConfirmation', $data);
    }

}
