<?php
/*
* Acceso principal al WEB SERVICE (WS)...
* Definición de cada una de las funcionalidades del WS.
*/
echo 'Acceso principal al WEB SERVICE (WS)...' . '<br>'; ###

# Autenticación???

# Obtener los parametros GET
# Investigar Injection a este nivel
$WS_request_type = 'default';

if (isset($_GET['request'])) # exit();
$WS_request_type = $_GET['request'];

echo 'La solicitud fue de tipo: ' . $WS_request_type . '<br>'; ###

# Realizar un switch para determinar que acción desea solicitar
switch ( $WS_request_type ) {
# Invocar al método correspondiente en Controllers
	case 'getEspecialidades':
		# code...
			
		break;
	
	default:
		# Mostrar error en consulta al WS (puede ser de tipo json_response)
		include '/protected/classes/json_response.php';
		json_response::echo_test('FAIL!'); # Invocar el metodo correcto!
		break;

}

echo '<br>' . 'TESTING CLASS SOS_DB_MODEL' . '<br>'; ###

include '/protected/classes/sos_db_model.php';
$aux = new sos_db_model(); # funciona ! =)


?>