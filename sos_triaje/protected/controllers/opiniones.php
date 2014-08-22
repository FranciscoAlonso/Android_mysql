<?php
/**
 * Clase estática encargada del CRUD relacionado con la tabla "opinion".
 */
class opiniones{

	private function __construct(){}
  	private function __clone(){}

  	/**
  	 * Crea una opinión a un caso.
  	 * @param  array $form Arreglo con los valores de la nueva opinión.
  	 * @return JSON 		JSON indicando si la inserción de la opinión fue un éxito o no. 
  	 * @throws Exception If Ocurre alguna excepción en el proceso de la obtención de la data.
  	 */
  	public static function create($form){
  		try {
			# Invocar a la clase sos_db_model.
			$DBH_SOS = new sos_db_model();

			$result = $DBH_SOS->createOpinion($form);

			# Crear metadata para la consulta exitosa.
			$metadata = 
				new json_response_metadata(
						JR_SUCCESS,
						$result->rowCount(),
						$result->queryString,
						$_SERVER['REQUEST_METHOD']
					);

			# Retorna el resultado de la consulta con información extra en formato JSON.
			return json_response::generate($metadata, DB_INSERT_SUCESS_MSG);
		} catch (Exception $e) {
            return $e->getMessage();
		}
  	}

	/**
	 * Función que se encarga de obtener las opiniones.
	 * @param string $caso_id Id del caso.
	 * @param string $opinion_id	ID de la opinion a consultar, o vacio si se desean obtener todas las opiniones relacionandas a $caso_id.
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

/*
	public static function update($form){
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
	/**/

	public static function delete($caso_id, $opinion_id){
		try {
			# Invocar a la clase sos_db_model.
			$DBH_SOS = new sos_db_model();

			$result = $DBH_SOS->deleteOpinion($caso_id, $opinion_id);
			exit("opiniones::delete()");
/*
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
	/**/
		} catch (Exception $e) {
            return $e->getMessage();
		}
	}
}
?>