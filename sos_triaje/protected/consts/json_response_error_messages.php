<?php
/**
* Definición de los mensajes de error para las respuestas JSON.
*/
class json_response_codes{

	/**
	 * Valida y retorna el msj según el código de error.
	 * @param  int 		$errorCode 	Código de error.
	 * @return String            	Mensaje que corresponde con el código de error.
	 */
	public static function getJsonMessage($errorCode){
		if(isset(json_response_codes::$jsonMessages[$errorCode]))
			return json_response_codes::$jsonMessages[$errorCode];
		else
			return "Invalid error code.";
	}
	
	/**
	 * Arreglo que define la correspondecia entre el error y su mensaje.
	 * @var array
	 */
	private static $jsonMessages = array(
		JR_SUCCESS	=> 'Success',
		JR_ERROR 	=> 'Failed'
	);

}

?>