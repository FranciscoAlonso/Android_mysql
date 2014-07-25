<?php
/**
* Definición de los mensajes de error para las respuestas JSON:
*/

class json_response_codes{

	#funcion que valida y retorna el msj segun el código de error.
	public static function getJsonMessage($errorCode){
		if(isset( json_response_codes::$jsonMessages[$errorCode]))
			return json_response_codes::$jsonMessages[$errorCode];
		else
			return "Invalid error code.";
	}

	private static $jsonMessages = array(
		JR_SUCCESS	=> 'Success',
		JR_ERROR 	=> 'Failed'
	);

}

?>