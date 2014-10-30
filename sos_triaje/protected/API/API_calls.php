<?php  
/**
 * Este archivo contiene la definicion de cada llamada al API.
 */

define('HTML_CONTENT_TYPE'	, 'text/html'		);
define('JSON_CONTENT_TYPE'	, 'application/json');
define('XML_CONTENT_TYPE'	, 'application/xml'	);
define('JPG_CONTENT_TYPE'	, 'image/jpg'		);

define('DEFAULT_API_VERSION', '111'); # Versión del API más antigua.
define('LASTEST_API_VERSION', '111'); # Versión del API más reciente.

define('DEFAULT_CONTENT_TYPE', JSON_CONTENT_TYPE . ';' . 'version=' . DEFAULT_API_VERSION );

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

	#region CREATES
		/**
		 * Crea un caso.
		 * method - POST
		 * url - /casos
		 */
		$app->post('/casos', 'API::authenticate', function() use($app){

			# check for required params
	        API::verifyRequiredParams(
	        	array(
	        		'descripcion'
	        		// , 'version'
	        		//, 'paciente_id'
	        		//, 'status_id'
	        	));

			$form = array();

			$form[':version'] = $app->request()->post('version');
			$form[':centro_id'] = $app->request()->post('centro_id');
			$form[':descripcion'] = $app->request()->post('descripcion');
			$form[':fecha_solucion'] = $app->request()->post('fecha_solucion');
			$form[':id_casosos'] = $app->request()->post('id_casosos');
			$form[':paciente_id'] = $app->request()->post('paciente_id');
			$form[':status_id'] = $app->request()->post('status_id');

			# Si no se envía la versión se asigna el valor de LASTEST_API_VERSION.
			if(empty($form[':version']))
				$form[':version'] = LASTEST_API_VERSION;
			
			# Si no se envía el ID de un paciente se asigna el caso a un paciente generico.
			if(empty($form[':paciente_id']))
				$form[':paciente_id'] = 0;

			# Si no se envía el ID del status se asigna el ID del status "En espera" (ID = 1)
			if(empty($form[':status_id']))
				$form[':status_id'] = 1;

			$especialidad_id = $app->request()->post('especialidad_id');

			require_once  DIR_CONTROLLERS . '/casos.php';
			echo casos::create($form, $especialidad_id);
		});

		/**
		 * Crea una opinion a un caso especifico.
		 * method - POST
		 * url - /casos/:casoId/opiniones
		 */
		$app->post('/casos/:casoId/opiniones', 'API::authenticate', function($caso_id) use ($app){
			
			# check for required params
	        API::verifyRequiredParams(
	        	array(
	        		'version'
	        		, 'cuerpo_opinion'
	        		, 'nombre_opinion'
	        		, 'user_extension'
	        	));

			global $user_id;
			
			$form[':caso_id'] = $caso_id;
			$form[':medico_id'] = $user_id;

			$form[':version'] = $app->request()->post('version');
			$form[':cuerpo_opinion'] = $app->request()->post('cuerpo_opinion');
			$form[':nombre_opinion'] = $app->request()->post('nombre_opinion');
			$form[':estado_opinion'] = $app->request()->post('estado_opinion');

			$user_extension = $app->request()->post('user_extension');

			require_once  DIR_CONTROLLERS . '/opiniones.php';
			echo opiniones::create($form, $user_extension);
	    });

		/**
		 * Crea un paciente.
		 * method - POST
		 * url - /pacientes
		 */
		/*
	    $app->post('/pacientes', 'API::authenticate', function() use ($app){

	    	# check for required params
	        API::verifyRequiredParams(
	        	array(
	        		'fecha_nacimiento'
	        		//, 'id'
	        	));

	        $form[':fecha_nacimiento'] = $app->request()->post('fecha_nacimiento');
	        //$form[':id'] = $app->request()->post('id');

	    	require_once  DIR_CONTROLLERS . '/pacientes.php';
			echo pacientes::create($form);
	    });
	    /**/
    #endregion

	#region READS
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
	     * Retorna información de un paciente.
	     * method - GET
	     * url - /pacientes/:paciente_id  
	     */
	    $app->get('/pacientes', 'API::authenticate', function(){
	    	require_once  DIR_CONTROLLERS . '/pacientes.php';
			echo pacientes::read();
	    });

	    /**
	     * Retorna información de un paciente.
	     * method - GET
	     * url - /pacientes/:paciente_id  
	     */
	    $app->get('/pacientes/:paciente_id', 'API::authenticate', function($paciente_id){
	    	require_once  DIR_CONTROLLERS . '/pacientes.php';
			echo pacientes::read($paciente_id);
	    });

		/**
	     * Retorna una lista de grupos de llamada (Ring Groups).
	     * method - GET
	     * url - /call_groups
	     */
	    $app->get('/call_groups', 'API::authenticate', function(){
			require_once  DIR_CONTROLLERS . '/call_groups.php';
			echo call_groups::read();
	    });

	    /**
	     * Retorna una lista de usuarios que estan disponibles para ser llamados.
	     * method - GET
	     * url - /peers_available
	     *//*
	    //$app->get('/peers_available', 'API::authenticate', function(){
	    $app->get('/peers_available', function(){
			require_once  DIR_CONTROLLERS . '/peers_available.php';
			echo peers_available::read();
	    });
		/**/
    #endregion

    #region UPDATES
    	/**
    	 * Modifica una opinión asociada a un caso.
    	 * method - PUT
    	 * url - /casos/:casoId/opiniones/:opinionesId
    	 */
	    $app->put('/casos/:casoId/opiniones/:opinionesId', 'API::authenticate', function($caso_id, $opinion_id) use($app){

			global $user_id;

			$form[':id'] = $opinion_id;
			$form[':caso_id'] = $caso_id;
			$form[':medico_id'] = $user_id;
				
			$version = $app->request()->post('version');
			if(isset($version))
				$form[':version'] = $version;

			$cuerpo_opinion = $app->request()->post('cuerpo_opinion');
			if(isset($cuerpo_opinion))
				$form[':cuerpo_opinion'] = $cuerpo_opinion;

			$nombre_opinion = $app->request()->post('nombre_opinion');
			if(isset($nombre_opinion))
				$form[':nombre_opinion'] = $nombre_opinion;
			
			$estado_opinion = $app->request()->post('estado_opinion');
			if(isset($estado_opinion))
				$form[':estado_opinion'] = $estado_opinion;

			require_once  DIR_CONTROLLERS . '/opiniones.php';
			echo opiniones::update($form);
	    });
    #endregion

    #region DELETES:
    	/**
    	 * Elimina un caso.
    	 * method - DELETE
    	 * url - /casos/:casoId
    	 */
    	$app->delete('/casos/:casoId', 'API::authenticate', function($caso_id){
    		require_once  DIR_CONTROLLERS . '/casos.php';
			echo casos::delete($caso_id);
    	});

	    /**
		 * Elimina una opinión asociada a un caso.
		 * method - DELETE
		 * url - /casos/:casoId/opiniones/:opinionesId
		 */
	    $app->delete('/casos/:casoId/opiniones/:opinionesId', 'API::authenticate', function($caso_id, $opinion_id){
	    	require_once  DIR_CONTROLLERS . '/opiniones.php';
			echo opiniones::delete($caso_id, $opinion_id);
	    });
    #endregion

#endregion
?>