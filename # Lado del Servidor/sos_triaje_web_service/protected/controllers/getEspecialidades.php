<?php
/**
* 
*/
echo "# getEspecialidades INCLUDED #" . '<br>';

# Se incluyen las dependencias que requiere este controlador
require_once DIR_CLASSES . 'json_response.php';
require_once DIR_CLASSES . 'sos_db_model.php';

class getEspecialidades{

	# private function __construct(){}

	public static function run(){
		echo "!!! EXEC TEST !!!" . '<br>';

		# Invocar a la clase sos_db_model
		$DBH_SOS = new sos_db_model();
		
		# Obtiene las especialidades
		$result = $DBH_SOS->getEspecialidades();

		/*
		# Definir el query
		$query = 
		'SELECT *
		FROM especialidad
		WHERE id = :id';

		$params = array( ':id' => 3 );

		$result = $DBH_SOS->execute( $query , $params );
		*/

		$row_count = $result->rowCount();
	    echo 'Columnas afectadas por el query: ';
	    echo $row_count . '<br>';

		# obtener el resultado (array)
		# showing the results		
	    
	    while( $row = $result->fetch() ) {
	    	/**/
		    echo $row['id'] . " - ";
		    //echo $row['version'] . "<br>";
		    echo $row['descripcion'] . " - ";
		    echo $row['nombre'] . "<br>";
			/**/
			/*
			# 
			foreach( $row as $key => $val ) {
		    	echo $key.' - '.$val.'<br />';
		    	# push al arreglo !
		    }
			/**/
		}

		# incorporar dentro de la clase json_response
		//json_response::echo_test("Hola mundo!");
		//json_response::generate("Hola mundo!");

		# retornar
		return '=D';
	}
}
?>