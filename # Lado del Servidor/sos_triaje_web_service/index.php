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

if (isset($_GET['request'])) # if not -> exit();
$WS_request_type = $_GET['request'];

# Constantes que definen los directorios que se utilizan en el WS
define( 'DIR_CLASSES'		, __DIR__ . '/protected/classes/');
define( 'DIR_CONSTANTS'		, __DIR__ . '/protected/consts/');
define( 'DIR_CONTROLLERS'	, __DIR__ . '/protected/controllers/');

echo 'La solicitud fue de tipo: ' . $WS_request_type . '<br>'; ###

# Realizar un switch para determinar que acción desea solicitar
switch ( $WS_request_type ) {
# Invocar al método correspondiente en controllers
	case 'getEspecialidades':
		require_once  DIR_CONTROLLERS . 'getEspecialidades.php';
		echo getEspecialidades::run();
	break;

	default:
		# Mostrar error en consulta al WS (puede ser de tipo json_response)
		require_once DIR_CLASSES . 'json_response.php';
		json_response::echo_test('FAIL!'); # Invocar el metodo correcto!
	break;

}
?>