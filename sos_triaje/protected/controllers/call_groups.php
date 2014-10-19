<?php
/**
 * Clase estática encargada de listar los grupos de llamadas (Ring Groups) existentes.
 */
class call_groups{

	private function __construct(){}
  	private function __clone(){}

	/**
	 * Invoca al modelo para obtener los peers disponibles.
	 * @return JSON 		JSON que contiene los peers disponibles. 	
	 * @throws Exception If Ocurre alguna excepción en el proceso de la obtención de la data.
	 */
	public static function read(){
		try {
			# Invocar a la clase elastix_db_model.
			$DBH_ELASTIX = new elastix_db_model();

			$result = $DBH_ELASTIX->getCallGroups();

			# Crear metadata para la consulta exitosa.
			$metadata = 
				new json_response_metadata(
						JR_SUCCESS,
						$result->rowCount(),
						$result->queryString,
						$_SERVER['REQUEST_METHOD']
					);

			# Se procesa la data para poder retornar de forma separada los contactos pertenecientes a cada ringgroup.
			$data = array();

			while( $row = $result->fetch() ) {
		    	$aux['grpnum'] = $row['grpnum'];
		    	$aux['description'] = $row['description'];
		    	$aux['grplist'] = explode("-", $row['grplist']);
		    	array_push($data, $aux);
		    }

			# Retorna el resultado de la consulta con información extra en formato JSON.
			return json_response::generate($metadata, $data);
		} catch (Exception $e) {
            return $e->getMessage();
		}
	}
}
?>