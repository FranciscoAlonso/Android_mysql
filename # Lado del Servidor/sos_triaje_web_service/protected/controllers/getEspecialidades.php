<?php
/**
* 
*/

# Se incluyen las dependencias que requiere este controlador
require_once DIR_CLASSES . 'json_response.php';
require_once DIR_CLASSES . 'sos_db_model.php';

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
			echo '* Rows Affected: ' . $result->rowCount() . ' *<br>';

			# Crear metadata
			$metadata = 
				new json_response_metadata(
						QT_SELECT,
						JR_SUCCESS,
						$result->rowCount()
					);
			/*
			print_r($metadata);echo '<br>';
			echo '----<br>';	
			echo $metadata->getQueryType() . '<br>';    
			echo $metadata->getErrorCode() . '<br>';    
			echo $metadata->getErrorMessage() . '<br>';    
			echo $metadata->getRowsAffected() . '<br>----<br>';    
			/**/
			$arrayTest['metadata']['m1'] = 11;

			$index = 0;
			while( $row = $result->fetch() ) {
		        //echo $row['id'] . " - ";
		        //echo $row['descripcion'] . " - ";
		        //echo $row['nombre'] . "<br>";
		        #print_r($row); echo '<br>';
		        foreach( $row as $key => $value ) {
		          	echo $key.' - '.$value.'<br />';
					$arrayTest['data'][$index][$key] = $value;
		        }
		       $index++;
		    }

		    echo 'Index: ' . $index . '<br>';
		    echo json_encode($arrayTest);
		    echo '<br>';

			# incorporar dentro de la clase json_response

			# retornar
			#return json_response::generate( $metadata, $result );
			return '=D';

		} catch (Exception $e) {
			#print_r($e);
			//echo $e->getMessage() . '<br>';
		}

	}
}
?>