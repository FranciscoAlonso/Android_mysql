<?php
/**
* 
*/

# Se incluyen las dependencias que requiere este controlador
require_once DIR_CLASSES . '/json_response.php';
require_once DIR_CLASSES . '/sos_db_model.php';

class getEspecialidades{

	#private function __construct(){}
  	#private function __clone(){}

	public static function run(){
		# Invocar a la clase sos_db_model
		$DBH_SOS = new sos_db_model();
		
		# Obtiene las especialidades
		try {
			$result = $DBH_SOS->getEspecialidades();

			//print_r($result);echo '<br>';

			# Crear metadata para la consulta exitosa
			$metadata = 
				new json_response_metadata(
						QT_SELECT,
						JR_SUCCESS,
						$result->rowCount()
					);
			
			# Retorna el resultado de la consulta con información extra en formato JSON 
			return json_response::generate( $metadata, $result );

		} catch (Exception $e) {
			//print_r($e);
			//echo $e->getMessage() . '<br>';
			# Crear metadata para la consulta fallida
			$app = \Slim\Slim::getInstance();
			$app->status(404);
		}
	}
}
?>