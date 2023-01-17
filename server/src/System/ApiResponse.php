<?php

include_once RUTABASE."/cargaClases.php";

class ApiResponse {

	/**
	 * Variable privada
	 */
	private $oDev;


	/**
	 * Constructor
	 */
	public function __construct($iResultado = null, $sResultado = null, $sError = "", $oResultado = null){

		$this->oDev = new \stdClass();
		$this->oDev->iResultado = $iResultado;
		$this->oDev->sResultado = $sResultado;
		$this->oDev->sError = $sError;
		$this->oDev->oResultado = $oResultado;

	}


	public function okResponse(){

		if($this->oDev->iResultado == 2) {
			$response['status_code_header'] = 'HTTP/1.1 400 Bad Request';
		}
		else {
			$response['status_code_header'] = 'HTTP/1.1 200 OK';
		}
		$response['body'] = json_encode($this->oDev);

		return $response;

	}

	public function unAuthorizedResponse(){

		$response['status_code_header'] = 'HTTP/1.1 401 Unauthorized';
		$response['body'] = null;

		return $response;
	}


	public function forbiddenResponse(){
		$response['status_code_header'] = 'HTTP/1.1 403 Forbidden';
		$response['body'] = null;

		return $response;
	}


	public function notFoundResponse(){

		$response['status_code_header'] = 'HTTP/1.1 404 Not Found';
		$response['body'] = null;

		return $response;
	}


	public function errorResponse(){

		$response['status_code_header'] = 'HTTP/1.1 412 Precondition Failed';
		$response['body'] = json_encode($this->oDev);

		return $response;
	}


	public function unprocessableEntityResponse(){

		$response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
		$response['body'] = json_encode(['error' => 'Invalid input']);

		return $response;

	}
	

	public function devolucionResultado($iResultado, $sResultado, $sError = "", $oResultado = null){
		
		$this->oDev->iResultado = $iResultado;
		$this->oDev->sResultado = $sResultado;
		$this->oDev->sError = $sError;
		$this->oDev->oResultado = $oResultado;

	}

}

?>