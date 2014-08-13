<?php
/**
 * Clase estática encargada del CRUD relacionado con la tabla "caso".
 */
class casos{

	private function __construct(){}
  	private function __clone(){}

	/**
	 * Función que se encarga de obtener las especialidades.
	 * @return JSON 		JSON que contiene las especialidades. 	
	 * @throws Exception If Ocurre alguna excepción en el proceso de la obtención de la data.
	 */
	public static function read(){

		try {		
			# Invocar a la clase sos_db_model.
			$DBH_SOS = new sos_db_model();
		
			$result = $DBH_SOS->getCasos();

			# Crear metadata para la consulta exitosa.
			$metadata = 
				new json_response_metadata(
						JR_SUCCESS,
						$result->rowCount(),
						$result->queryString,
						$_SERVER['REQUEST_METHOD']
					);
			
			# Retorna el resultado de la consulta con información extra en formato JSON.
			return json_response::generate($metadata, $result);
		} catch (Exception $e) {
            return $e->getMessage();
		}
	}
}
?>