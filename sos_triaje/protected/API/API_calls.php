<?php  
/**
 * Este archivo contiene la definicion de cada llamada al API.
 */

define('HTML_CONTENT_TYPE'	, 'text/html'		);
define('JSON_CONTENT_TYPE'	, 'application/json');
define('XML_CONTENT_TYPE'	, 'application/xml'	);

define('DEFAULT_VERSION', 'version=1'); # Versión más antigua.
define('LASTEST_VERSION', 'version=1'); # Versión más reciente.

define('DEFAULT_CONTENT_TYPE', JSON_CONTENT_TYPE . ';' . DEFAULT_VERSION );

define('DEFAULT_ERROR_MSG', 'Ha ocurrido un error mientras se realizaba la operación, intente nuevamente más tarde.'); 

# Se cargan los métodos de utilidad para el API
require_once DIR_CLASSES 	. '/json_response.php';
require_once DIR_API 		. '/API_functions.php';
require_once DIR_CLASSES	. '/sos_db_model.php';

$app->status(200);
$app->contentType(DEFAULT_CONTENT_TYPE);

#region Métodos sin auntenticación
	/**
	 * Login del usuario.
	 * url - /login
	 * method - POST
	 * params - user, password
	 */
	$app->post('/login', function() use ($app) {
        # check for required params
        API::verifyRequiredParams(array('user', 'password'));
        
        # reading post params
        $user = $app->request()->post('user');
        $password = $app->request()->post('password');

        # Validar correo
        //API::validateEmail($user); 
        # No se valida el $user como un correo válido para que 
        # la autenticación pueda ser por correo o por login.

		require_once  DIR_CONTROLLERS . '/login.php';
        echo login::run($user, $password);
    });
#endregion

#region Métodos con auntenticación
	/**
	 * Retorna el conjunto de especialidades que existen en el sistema.
	 * url - /especialidades
	 * method - GET
	 */
	$app->get('/especialidades', 'API::authenticate', function() use($app){
		require_once  DIR_CONTROLLERS . '/especialidades.php';
		echo especialidades::read();
    });

	/**
	 * Retorna el conjunto de casos que existen en el sistema.
	 * url - /casos
	 * method - GET
	 */
    $app->get('/casos', 'API::authenticate', function() use($app){
		require_once  DIR_CONTROLLERS . '/casos.php';
		echo casos::read();
    });
#endregion
?>