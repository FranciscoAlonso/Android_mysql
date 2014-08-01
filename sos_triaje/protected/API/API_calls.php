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
require_once DIR_CLASSES 	. '/sos_db_model.php';

$app->status(200);
$app->contentType(DEFAULT_CONTENT_TYPE);

#region Métodos sin auntenticación
	/**
	 * User Login
	 * url - /login
	 * method - POST
	 * params - email, password
	 */
	$app->post('/login', function() use ($app) {
	            # check for required params
	            API::verifyRequiredParams(array('email', 'password'));
	            
	            # reading post params
	            $email = $app->request()->post('email');
	            $password = $app->request()->post('password');
	            $response = array();

	            # Validar contraseña
	            API::validateEmail($email);

				# Invocar a la clase sos_db_model
				$DBH_SOS = new sos_db_model();
	            
	            $metadata = new json_response_metadata(JR_ERROR, 0);//, "", $_SERVER['REQUEST_METHOD']);

	            if ($DBH_SOS->checkLogin($email, $password)) {
	            	//exit();
	            }else{
	            	$metadata = new json_response_metadata(JR_ERROR, 0);//, "", $_SERVER['REQUEST_METHOD']);
	            }
				
	            /*
	            $db = new DbHandler();
	            // check for correct email and password
	            if ($db->checkLogin($email, $password)) {
	                // get the user by email
	                $user = $db->getUserByEmail($email);

	                if ($user != NULL) {
	                    $response["error"] = false;
	                    $response['name'] = $user['name'];
	                    $response['email'] = $user['email'];
	                    $response['apiKey'] = $user['api_key'];
	                    $response['createdAt'] = $user['created_at'];
	                } else {
	                    // unknown error occurred
	                    $response['error'] = true;
	                    $response['message'] = "An error occurred. Please try again";
	                }
	            } else {
	                // user credentials are wrong
	                $response['error'] = true;
	                $response['message'] = 'Login failed. Incorrect credentials';
	            }

	            echoRespnse(200, $response);
	            /**/
	            echo json_response::error(DEFAULT_ERROR_MSG);

	        });

#endregion

#region Métodos con auntenticación
	/**
	 * Prueba del API
	 * url - /especialidades
	 * method - GET
	 */
	$app->get('/especialidades', function() use($app){
	    		//$app->status(200);
	            #echo '>' . $app->request->getContentType() . '<' ; # Split por ';'
	            # Hacer override del default Content-type con el de la solicitud.
	    		require_once  DIR_CONTROLLERS . '/getEspecialidades.php';
				echo getEspecialidades::run();
	        });
#endregion
?>