<?php

/**
 * WEB SERVICE PARA LA BD SOS_TRIAJE
 */

# Muestra que drivers de BD soporta (Se agregan en las extensiones de php)
//var_dump(PDO::getAvailableDrivers()); 

if (false /*VERIFICA QUE EXISTAN LOS CAMPOS POST*/) { 
	echo "<h1>Forbidden</h1><p>You don't have permission to access " . __DIR__ . " on this server.</p>";
}else{

    # Se establece la conexión a la BD y se incorpora las funcionalidades que usará la aplicación.
	require_once __DIR__ . '\protegido\db_functions.php';
	$var = new DB_FUNCTIONS();

	# Arreglo para la respuesta.
    //$respuesta = array("tag" => $tag, "success" => 0, "error" => 0);

	# $var->get_all_actor_sistema();

	/*
	$response["success"] = 1;
	$response["uid"] = "UUID";
	$response["user"]["name"] = "name";
	$response["user"]["email"] = "email";

	echo json_encode($response);
	/**/

/*
	# CREATE_READ_UPDATE_DELETE.
	switch ('default') {
		
		case 'login':
			# code...
			break;

		case 'READ_historial_caso':
			# code...
			break;

		case 'caso':
			# code...
			break;

		default:
			# code...
			echo "<h1>Invalid Request</h1><p>Your broser sent an invalid request.</p>";
			break;
	}
	/**/
}
?>