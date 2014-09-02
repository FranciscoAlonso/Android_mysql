<?php
/**
 * Clase estática encargada de listar los peers disponibles utilizando la interfaz AMI.
 */
class peers_available{

	private function __construct(){}
  	private function __clone(){}

	/**
	 * Invoca al modelo para obtener los peers disponibles.
	 * @return JSON 		JSON que contiene los peers disponibles. 	
	 * @throws Exception If Ocurre alguna excepción en el proceso de la obtención de la data.
	 */
	public static function read(){
		try {
			/*
			require_once DIR_LIBS . '/AsteriskManagerInterface/AMI.php';

			$ami = new AMI();

			# TEST DE LA SALIDA:
			//AMI::isPeerConnected_old('6001');
			//AMI::isPeerConnected_old('6003');

			$ami->getPeers();
			/**/
			//$ami->isPeerAvailable('6001');

			//exit("peers_available::read();");
			# - Se utilizará la interfaz AMI para obtener el estado de los peers.
			# - Ampliar el json_response::generate() para que maneje arreglos (para la data).

			# Invocar a la clase elastix_db_model.
			$DBH_ELASTIX = new elastix_db_model();

			$result = $DBH_ELASTIX->test();

			# Crear metadata para la consulta exitosa.
			$metadata = 
				new json_response_metadata(
						JR_SUCCESS,
						$result->rowCount(),
						$result->queryString,
						$_SERVER['REQUEST_METHOD']
					);

			# Se procesa la data para poder retornar de forma separada los contactos pertenecientes a cada ringgroup.
			$aux = array();
			while( $row = $result->fetch() ) {
		    	$aux['grpnum'] = $row['grpnum'];
		    	$aux['description'] = $row['description'];
		    	$aux['grplist'] = explode("-", $row['grplist']);
		    }

			# Retorna el resultado de la consulta con información extra en formato JSON.
			return json_response::generate($metadata, $aux);
		} catch (Exception $e) {
            return $e->getMessage();
		}
	}
}
?>