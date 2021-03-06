<?php
/**
 * Acceso principal al WEB SERVICE (WS) sos_triaje
 *
 * Autores:
 * Francisco Alonso - francisco.a.alonso.s@gmail.com 
 * Tony Briceño - tonybp18@gmail.com
 * 2014
 */

# Habilita mensajes para desarrollador (Default: false).
define('DEBUG_MODE', !false);

# Constantes que definen los directorios que se utilizan en el WS
define('DIR_API'			, __DIR__ . '/protected/API'		);
define('DIR_CLASSES'		, __DIR__ . '/protected/classes'	);
define('DIR_CONSTANTS'		, __DIR__ . '/protected/consts'		);
define('DIR_CONTROLLERS'	, __DIR__ . '/protected/controllers');
define('DIR_LIBS'			, __DIR__ . '/protected/libs'		);

#require_once '../include/PassHash.php';
require_once DIR_LIBS . '/Slim/Slim.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

# User id from db - Global Variable
$user_id = NULL;

# Se cargan las definiciones de las llamadas al API
require_once DIR_API . '/API_calls.php';

# Se ejecuta la aplicación REST (Slim Framework)
$app->run();

/*
SELECT calldate, src, dst, duration, disposition, uniqueid, userfield FROM cdr WHERE src = 6002 AND disposition = "ANSWERED" AND userfield != "" ORDER BY calldate desc LIMIT 1;

SELECT 
	calldate
	, src
	, dst
	, duration
	, disposition
	, uniqueid
	, userfield 

	FROM cdr
		WHERE src = 6002
			AND disposition = "ANSWERED"
			AND userfield != ""

		ORDER BY calldate desc LIMIT 1;
/**/

/*
DOWNLOAD DIRECTO:

https://192.168.2.44/index.php
?
menu=monitoring
&
action=download
&
id=1404932607.2

https://192.168.2.44/index.php?menu=monitoring&action=download&id=1404932607.2&namefile=20140709-143327-1404932607.2.wav&rawmode=yes
*/
?>