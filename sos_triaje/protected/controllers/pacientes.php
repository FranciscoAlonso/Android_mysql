<?php
/**
 * Clase estática encargada del CRUD relacionado con la tabla "paciente".
 */
class pacientes{

	private function __construct(){}
  	private function __clone(){}

  	/**
  	 * Invoca al modelo para crear un paciente.
  	 * @param  array $form Arreglo con los valores del nuevo paciente.
  	 * @return JSON 		JSON indicando si la inserción del paciente fue un éxito o no. 
  	 * @throws Exception If Ocurre alguna excepción en el proceso de la creación de la data.
  	 */
  	/*
  	public static function create($form){
  		try {
			# Invocar a la clase sos_db_model.
			$DBH_SOS = new sos_db_model();
			
			$result = $DBH_SOS->createPaciente($form);
			
			# Crear metadata para la consulta exitosa.
			$metadata = 
				new json_response_metadata(
						JR_SUCCESS,
						$result->rowCount(),
						$result->queryString,
						$_SERVER['REQUEST_METHOD']
					);

			# Retorna el resultado de la consulta con información extra en formato JSON.
			return json_response::generate($metadata, DB_INSERT_SUCESS_MSG, $DBH_SOS->getLastInsertId());
		} catch (Exception $e) {
            return $e->getMessage();
		}
  	}
  	/**/

	/**
	 * Invoca al modelo para obtener los pacientes.
	 * @param string $paciente_id 	ID del paciente.
	 * @return JSON 			JSON que contiene los pacientes. 	
	 * @throws Exception If Ocurre alguna excepción en el proceso de la obtención de la data.
	 */
	public static function read($paciente_id = ""){
		try {
			# Invocar a la clase sos_db_model.
			$DBH_SOS = new sos_db_model();

			$result = $DBH_SOS->getPacientes($paciente_id);

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

	//public static function update($form){}

	/**
	 * para eliminar un caso.
	 * @param string $caso_id 		ID del caso.
	 * @return JSON 				JSON indicando si la eliminación del caso fue un éxito o no. 
	 * @throws Exception If Ocurre alguna excepción en el proceso de la eliminación de la data.
	 *//*
	public static function delete($caso_id = ""){
		try {
			# Invocar a la clase sos_db_model.
			$DBH_SOS = new sos_db_model();

			$result = $DBH_SOS->deleteCaso($caso_id);

			# Crear metadata para la consulta exitosa.
			$metadata = 
				new json_response_metadata(
						JR_SUCCESS,
						$result->rowCount(),
						$result->queryString,
						$_SERVER['REQUEST_METHOD']
					);

			# Retorna el resultado de la consulta con información extra en formato JSON.
			return json_response::generate($metadata, $result->rowCount() . DB_DELETE_SUCESS_MSG);
		} catch (Exception $e) {
            return $e->getMessage();
		}
	}
	/**/
}
?>