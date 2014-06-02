<?php
/*
* Acceso principal al WEB SERVICE (WS)...
* Definición de cada una de las funcionalidades del WS.
*/
echo 'Acceso principal al WEB SERVICE (WS)...' . '<br>';

# Autenticación???

# Obtener los parametros GET
# Investigar Injection a este nivel
$WS_request_type = $_GET['request'];

echo 'La solicitud fue de tipo: ' . $WS_request_type . '<br>';

# Realizar un switch para determinar que acción desea solicitar
switch ( $WS_request_type ) {
# Invocar al método correspondiente en Controllers
	case 'getEspecialidades':
		# code...
			
		break;
	
	default:
		# code...
		break;

}

?>