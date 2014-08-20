<?php
/**
 * Clase estática encargada del CRUD relacionado con la tabla "opinion".
 */
class opiniones{

	private function __construct(){}
  	private function __clone(){}

  	public static function create($caso_id = ""){
  		
  		try {
			# Invocar a la clase sos_db_model.
			$DBH_SOS = new sos_db_model();
  		exit("CREATE!!!");

			$result = $DBH_SOS->getOpiniones($caso_id, $opinion_id);

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

	/**
	 * Función que se encarga de obtener las opiniones.
	 * @return JSON 		JSON que contiene las opiniones. 	
	 * @throws Exception If Ocurre alguna excepción en el proceso de la obtención de la data.
	 */
	public static function read($caso_id = "", $opinion_id = ""){

		try {
			# Invocar a la clase sos_db_model.
			$DBH_SOS = new sos_db_model();

			$result = $DBH_SOS->getOpiniones($caso_id, $opinion_id);

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