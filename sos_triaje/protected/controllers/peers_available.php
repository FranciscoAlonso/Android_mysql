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

			require_once DIR_LIBS . '/AsteriskManagerInterface/AMI.php';

			# TEST ---------------------------------------------------------------------
				$metadata = new json_response_metadata(JR_SUCCESS);

				$a = array('1' => 1, '2'=> 2,'3' => 3);
				$b = array(4, 5, 6);
				$c = array(7, 8, array('a','b','c', array(true)));

				$arrays_merged = array();

				array_push($arrays_merged, $a);
				//print_r($arrays_merged);
				array_push($arrays_merged, $b);
				//print_r($arrays_merged);
				array_push($arrays_merged, $c);

				//return json_response::generate($metadata, "String response.");
				return json_response::generate($metadata, $arrays_merged);
			# ENDTEST ---------------------------------------------------------------------

			$ami = new AMI();
			# TEST DE LA SALIDA:
			//AMI::isPeerConnected_old();

			$ami->getPeers();

			//$ami->isPeerAvailable();

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