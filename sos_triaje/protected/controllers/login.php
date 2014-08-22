<?php
/**
 * Clase estática encargada del inicio de sesión.
 */
class login{

	private function __construct(){}
  	private function __clone(){}

  	/**
  	 * Invoca al modelo para obtener datos del usuario (iniciar sesión)
     * @param  string $user     Correo o login del usuario. 
     * @param  string $password Contraseña del usuario.
  	 * @return JSON           	Objeto JSON con el resultado del inicio de sesión.
  	 */
	public static function run($user, $password){
		try {
			# Se crea una instancia de la clase sos_db_model
			$DBH_SOS = new sos_db_model();

	        if ($DBH_SOS->checkLogin($user, $password)) {
	
	        	$result = $DBH_SOS->getUser($user);

	        	# Se crea la metadata para la consulta exitosa.
				$metadata = 
					new json_response_metadata(
							JR_SUCCESS,
							$result->rowCount(),
							$result->queryString,
							$_SERVER['REQUEST_METHOD']
						);

	        	return json_response::generate($metadata, $result);
	        }else{
	        	return json_response::error("Login fallido, credenciales incorrectas.");
	        }
        } catch (Exception $e) {
            return $e->getMessage();
        }
	}
}
?>