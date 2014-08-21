<?php  
/**
 * Este archivo contiene la definicion de cada llamada al API.
 */

define('HTML_CONTENT_TYPE'	, 'text/html'		);
define('JSON_CONTENT_TYPE'	, 'application/json');
define('XML_CONTENT_TYPE'	, 'application/xml'	);
define('JPG_CONTENT_TYPE'	, 'image/jpg'		);

define('DEFAULT_VERSION', 'version=1'); # Versión más antigua.
define('LASTEST_VERSION', 'version=1'); # Versión más reciente.

define('DEFAULT_CONTENT_TYPE', JSON_CONTENT_TYPE . ';' . DEFAULT_VERSION );

define('DEFAULT_ERROR_MSG', 'Ha ocurrido un error mientras se realizaba la operación, intente nuevamente más tarde.'); 

# Se cargan los métodos de utilidad para el API
require_once DIR_CLASSES 	. '/json_response.php';
require_once DIR_API 		. '/API_functions.php';
require_once DIR_CLASSES	. '/sos_db_model.php';
require_once DIR_CLASSES	. '/elastix_db_model.php';

$app->status(200);
$app->contentType(DEFAULT_CONTENT_TYPE);

#region Métodos sin auntenticación
	/**
	 * Login del usuario.
	 * method - POST
	 * params - user, password
	 * url - /login
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
	 * Crea una opinion a un caso especifico.
	 * method - POST
	 * url - /casos/:casoId/opiniones
	 */
	$app->post('/casos/:casoId/opiniones', 'API::authenticate', function($caso_id) use ($app){
		
		# check for required params
			# id (AUTO_INCREMENT)
			# version (Default = 0)
			# caso_id (*** FK Requerido ***)
			# cuerpo_opinion
			# estado_opinion (Default = NULL)
			# fecha_opinion (Calculado, NOW())
			# medico_id (*** FK Requerido ***) Si el que esta logueado no es medico asignar uno por default
			# nombre_opinion

        API::verifyRequiredParams(
        	array(
        		'version'
        		, 'cuerpo_opinion'
        		//, 'estado_opinion' # puede ser null
        		, 'nombre_opinion'
        	));
			
		/*
			 # $id;
			$version;
			 #$caso_id;
			$cuerpo_opinion;
			$estado_opinion;
			 # $fecha_opinion;
			 # $medico_id;
			$nombre_opinion;
		
		 */
		
		global $user_id;
		
		$form[':version'] = $app->request()->post('version');
		$form[':caso_id'] = $caso_id;
		$form[':cuerpo_opinion'] = $app->request()->post('cuerpo_opinion');
		$form[':medico_id'] = $user_id;
		$form[':nombre_opinion'] = $app->request()->post('nombre_opinion');
		$form[':estado_opinion'] = $app->request()->post('estado_opinion');

if(isset($form[':estado_opinion']))
	exit("is set!!!!!!!!!!");
else
	exit("is not set");

		require_once  DIR_CONTROLLERS . '/opiniones.php';
		echo opiniones::create($form);
    });

	/**
	 * Retorna el conjunto de especialidades que existen en el sistema.
	 * method - GET
	 * url - /especialidades
	 */
	$app->get('/especialidades', 'API::authenticate', function(){
		require_once  DIR_CONTROLLERS . '/especialidades.php';
		echo especialidades::read();
    });

	/**
	 * Retorna el conjunto de centros sos que existen en el sistema.
	 * method - GET
	 * url - /centro_sos
	 */
	$app->get('/centro_sos', 'API::authenticate', function(){
		require_once  DIR_CONTROLLERS . '/centro_sos.php';
		echo centro_sos::read();
    });

    /**
	 * Retorna un centros sos.
	 * method - GET
	 * url - /centro_sos
	 */
	$app->get('/centro_sos/:centro_id', 'API::authenticate', function($centro_id){
		require_once  DIR_CONTROLLERS . '/centro_sos.php';
		echo centro_sos::read($centro_id);
    });   

	/**
	 * Retorna el conjunto de casos que existen en el sistema.
	 * method - GET
	 * url - /casos
	 */
    $app->get('/casos', 'API::authenticate', function(){
		require_once  DIR_CONTROLLERS . '/casos.php';
		echo casos::read();
    });

    /**
	 * Retorna un caso.
	 * method - GET
	 * url - /casos/:casoId
	 */
    $app->get('/casos/:casoId', 'API::authenticate', function($caso_id){
		require_once  DIR_CONTROLLERS . '/casos.php';
		echo casos::read($caso_id);
    });

    /**
	 * Retorna el historial de un caso.
	 * method - GET
	 * url - /casos/:casoId/historial
	 */
    $app->get('/casos/:casoId/historial', 'API::authenticate', function($caso_id){
		require_once  DIR_CONTROLLERS . '/historial_caso.php';
		echo historial_caso::read($caso_id);
    });

    /**
	 * Retorna los archivos relacionados a un caso.
	 * method - GET
	 * url - /casos/:casoId/archivos
	 */
    $app->get('/casos/:casoId/archivos', 'API::authenticate', function($caso_id){
    	require_once  DIR_CONTROLLERS . '/archivos.php';
		echo archivos::read($caso_id);
    });

    /**
	 * Retorna el archivo del caso.
	 * method - GET
	 * url - /casos/:casoId/archivos/:archivoId
	 */
    $app->get('/casos/:casoId/archivos/:archivoId', 'API::authenticate', function($caso_id, $archivo_id){
    	require_once  DIR_CONTROLLERS . '/archivos.php';
		echo archivos::read($caso_id, $archivo_id);
    });

    /**
     * Retorna las opiniones de un caso.
     * method - GET
     * url - /casos/:casoId/opiniones
     */
    $app->get('/casos/:casoId/opiniones', 'API::authenticate', function($caso_id){
    	require_once  DIR_CONTROLLERS . '/opiniones.php';
		echo opiniones::read($caso_id);
    });

    /**
     * Retorna una opinión del caso.
     * method - GET
     * url - /casos/:casoId/opiniones/:opinionesId
     */
    $app->get('/casos/:casoId/opiniones/:opinionesId', 'API::authenticate', function($caso_id, $opinion_id){
    	require_once  DIR_CONTROLLERS . '/opiniones.php';
		echo opiniones::read($caso_id, $opinion_id);
    });

    /**
     * Retorna una lista de usuarios que estan disponibles para ser llamados.
     * method - GET
     * url - /peers_available
     */
    $app->get('/peers_available', 'API::authenticate', function(){
		require_once  DIR_CONTROLLERS . '/peers_available.php';
		echo peers_available::read();
    });
#endregion
?>