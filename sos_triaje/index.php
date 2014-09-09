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
https://admin:123456@192.168.2.44/index.php
*/

/*
https://192.168.2.44/index.php
?
menu=monitoring
&
action=download
&
id=1404932607.2
&
namefile=20140709-143327-1404932607.2.wav
&
rawmode=yes
*/

/*
 SELECT calldate,src,dst,uniqueid,userfield FROM cdr WHERE src = 6002 order by calldate desc limit 1;
 */

/*
https://190.72.194.19/index.php
?
menu=monitoring
&
filter_field=src
&
filter_value=
&
filter_value_userfield=
&
date_start=09+Sep+2014
&
date_end=09+Sep+2014
 */
?>