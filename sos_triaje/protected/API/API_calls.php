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
	 * User Login
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
        
        $response = array();

        # Validar correo
        //API::validateEmail($email);

		# Invocar a la clase sos_db_model
		$DBH_SOS = new sos_db_model();

        if ($DBH_SOS->checkLogin($user, $password)) {
        	
        	# Validar si existe la columna API KEY
        	# Si no , agregarla con QUERY y asignarle uno nuevo
        	# si existe pero el valor es null, generar un nuevo api key.
        	
        	$result = $DBH_SOS->getUser($user);
        	
        	# Se crea la metadata para la consulta exitosa.
			$metadata = 
				new json_response_metadata(
						JR_SUCCESS,
						$result->rowCount(),
						$result->queryString,
						$_SERVER['REQUEST_METHOD']
					);

        	echo json_response::generate($metadata, $result);
        	
        }else{

        	echo json_response::error("Login fallido, credenciales incorrectas.");
        
        }
    });
#endregion

#region Métodos con auntenticación
# Logout #
	/**
	 * Prueba del API
	 * url - /especialidades
	 * method - GET
	 */
	//$app->get('/especialidades', function() use($app){
	$app->get('/especialidades', 'authenticate', function() use($app){
		//$app->status(200);
        #echo '>' . $app->request->getContentType() . '<' ; # Split por ';'
        # Hacer override del default Content-type con el de la solicitud.
		require_once  DIR_CONTROLLERS . '/getEspecialidades.php';
		echo getEspecialidades::run();
    });
#endregion
?>