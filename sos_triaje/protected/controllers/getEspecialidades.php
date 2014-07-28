<?php
/**
* Controlador que genera 
*/

# Se incluyen las dependencias que requiere este controlador
require_once DIR_CLASSES . '/sos_db_model.php';

/**
 * Clase estática para obtener las especialidades. 
 */
class getEspecialidades{

	private function __construct(){}
  	private function __clone(){}

	/**
	 * Función principal del controlador para obtener las especialidades.
	 * @return JSON 		JSON que contiene las especialidades. 	
	 * @throws Exception If Ocurre alguna excepción en el proceso de la obtención de la data.
	 */
	public static function run(){

		try {
			# Invocar a la clase sos_db_model
			$DBH_SOS = new sos_db_model();
			
			$result = $DBH_SOS->getEspecialidades();

			//print_r($result);echo '<br>';
			
			# Crear metadata para la consulta exitosa
			$metadata = 
				new json_response_metadata(
						JR_SUCCESS,
						$result->rowCount(),
						$result->queryString,
						$_SERVER['REQUEST_METHOD']
					);
			
			# Retorna el resultado de la consulta con información extra en formato JSON 
			return json_response::generate($metadata, $result);

		} catch (Exception $e) {
            return $e->getMessage();
		}
	}
}
?>