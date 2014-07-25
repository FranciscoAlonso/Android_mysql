<?php  
/**
 * Este archivo contendrá la definicion de cada llamada al API. 
 */

#region Métodos sin auntenticación
/**
 * Prueba del API
 * url - /tests
 * method - GET
 */
$app->get('/tests', function() use($app){
    		//$app = \Slim\Slim::getInstance();
    		$app->status(200);
    		$app->contentType('application/json'); #Agregar versión
            
            #echo $app->request->getContentType(); # split por ';' 
            #Convertir en XML si Content-Type es application/xml
            require_once  DIR_CONTROLLERS . '/getEspecialidades.php';
			echo getEspecialidades::run();
        });
#endregion

#region Métodos con auntenticación


#endregion

?>