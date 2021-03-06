<?php
/*
* Acceso principal al WEB SERVICE (WS)...
* Definición de cada una de las funcionalidades del WS.
*/
//echo 'Acceso principal al WEB SERVICE (WS)...' . '<br>'; ###

# Autenticación???
# Obtener los parametros GET
# Investigar Injection a este nivel
$WS_request_type = 'default';

if (isset($_GET['request'])) # if not -> exit();
$WS_request_type = $_GET['request'];

# Constantes que definen los directorios que se utilizan en el WS
define( 'DIR_API'			, __DIR__ . '/protected/API/');
define( 'DIR_CLASSES'		, __DIR__ . '/protected/classes/');
define( 'DIR_CONSTANTS'		, __DIR__ . '/protected/consts/');
define( 'DIR_CONTROLLERS'	, __DIR__ . '/protected/controllers/');
define( 'DIR_LIBS'			, __DIR__ . '/protected/libs/');

//echo 'La solicitud fue de tipo: ' . $WS_request_type . '<br>'; ###

# Según sea el tipo se solicitud se invocará al controlador correspondiente
switch ( $WS_request_type ) {
	case 'getEspecialidades':
		require_once  DIR_CONTROLLERS . 'getEspecialidades.php';
		echo getEspecialidades::run();
	break;

	default:
		# Mostrar error en consulta al WS (puede ser de tipo json_response)
		require_once DIR_CLASSES . 'json_response.php';
		#echo json_response::generate(JR_ERROR,null);
	break;

}
?>