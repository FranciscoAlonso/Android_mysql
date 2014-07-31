<?php
/**
 * Este archivo contiene las funciones de utilidad para el API
 */

/**
 * Clase estática que conteine las funciones estáticas de utilidad para el API.
 */
class API{

    private function __construct(){}
    private function __clone(){}

    /**
     * Arroja una excepción con un mensaje de error y un HTTP status.
     * @param  string/Exception $e              string o excepción para el mensaje.
     * @param  int              $status         Estado HTTP a retornar.
     * @param  string           $queryString    QUERY ejecutado.
     * @param  int              $errorCode      Código del error ocurrido.
     * @param  int              $rowsAffected   Cantidad de filas afectadas.
     * @throws Exception If     Siempre arrojará una excepción.
     */
    public static function throwPDOException(
        $e = DEFAULT_ERROR_MSG,
        $status = 500,
        $queryString = "",
        $errorCode = JR_ERROR,
        $rowsAffected = 0)
    {
        
        $msg_error = DEFAULT_ERROR_MSG;   
        
        # Validación para el mensaje a mostrar.
        if (is_object($e)) {
            if(method_exists($e, "getMessage"))
                $msg_error = $e->getMessage();
        }else{
            if(is_string($e))
                $msg_error = $e;
        }

        if(!DEBUG_MODE && $errorCode == JR_ERROR)
            $msg_error = DEFAULT_ERROR_MSG;
        
        $app = \Slim\Slim::getInstance();
        $app->status($status);

        $response = json_response::error($msg_error);

        throw new Exception($response);
    }

    /**
     * Verifica que la solicitud posea los parametros requeridos.
     * @param  array $required_fields Arreglo con los parametros a verificar.
     */
    public static function verifyRequiredParams($required_fields) {
        $error = false;
        $error_fields = "";
        $request_params = array();
        $request_params = $_REQUEST;

        # Handling PUT request params.
        if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
            $app = \Slim\Slim::getInstance();
            parse_str($app->request()->getBody(), $request_params);
        }

        foreach ($required_fields as $field) {
            if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0) {
                $error = true;
                $error_fields .= $field . ', ';
            }
        }

        if ($error) {
            # Required field(s) are missing or empty, echo error json and stop the app.           
            $app = \Slim\Slim::getInstance();
            $app->status(400);
            echo json_response::error('Required field(s) ' . substr($error_fields, 0, -2) . ' is missing or empty.');
            $app->stop();
        }
    }
    /**/

    /**
     * Adding Middle Layer to authenticate every request checking if 
     * the request has valid api key in the 'Authorization' header.
     * @param  SlimRoute $route [description]
     * @return [type]           [description]
     */
    /*
    public static function authenticate(\Slim\Route $route) {
        # Getting request headers.
        $headers = apache_request_headers();
        $response = array();
        $app = \Slim\Slim::getInstance();

        # Verifying Authorization Header.
        if (isset($headers['Authorization'])) {
            $db = new DbHandler();

            # Get the api key.
            $api_key = $headers['Authorization'];

            # validating api key.
            if (!$db->isValidApiKey($api_key)) {
                # Api key is not present in users table.
                $response["error"] = true;
                $response["message"] = "Access Denied. Invalid Api key";
                echoRespnse(401, $response);
                $app->stop();
            } else {
                global $user_id;
                # Get user primary key id.
                $user_id = $db->getUserId($api_key);
            }
        } else {
            # Api key is missing in header.
            $response["error"] = true;
            $response["message"] = "Api key is misssing";
            echoRespnse(400, $response);
            $app->stop();
        }
    }
    /**/

    /**
     * Validating email address.
     * @param  string $email Email to validate
     */
    public static function validateEmail($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {    
            $app = \Slim\Slim::getInstance();
            $app->status(400);
            echo json_response::error("Dirección de correo inválida.");
            $app->stop();
        }
    }

}
?>