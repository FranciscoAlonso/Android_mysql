<?php
/**
 * Clase estática encargada de listar los peers disponibles utilizando la interfaz AMI.
 */
class peers_available{

	private function __construct(){}
  	private function __clone(){}

	/**
	 * Función que se encarga de obtener los peers disponibles.
	 * @return JSON 		JSON que contiene los peers disponibles. 	
	 * @throws Exception If Ocurre alguna excepción en el proceso de la obtención de la data.
	 */
	public static function read(){

		try {
			exit("peers_available::read();");

			# - Se utilizará la interfaz AMI para obtener el estado de los peers.
			# - Ampliar el json_response::generate() para que maneje arreglos (para la data).

			/*
			# Invocar a la clase elastix_db_model.
			$DBH_ELASTIX = new elastix_db_model();

			$result = $DBH_ELASTIX->getCasos($caso_id);

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