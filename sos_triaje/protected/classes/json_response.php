<?php
/**
 * Se definene las clases para el formato estandard 
 * de retorno tanto de la metadata como de la data.
 */

# Se incluyen las dependencias para las respuestas json
require_once DIR_CONSTANTS . '/json_response_error_codes.php';
require_once DIR_CONSTANTS . '/json_response_error_messages.php';

define('METADATA_KEY' ,'metadata');
define('DATA_KEY'     ,'data'    );

/**
 * Clase que define la estructura de la metadata.
 */
class json_response_metadata{

  private $errorCode;
  private $errorMessage;
  private $rowsAffected;
  private $queryString;
  private $requestMethod;

  # Constantes que definen el KEY para el arreglo.
  const errorCodeKey = 'errorCode';
  const errorMessageKey = 'errorMessage';
  const rowsAffectedKey = 'rowsAffected';
  const queryStringKey = 'queryString';
  const requestMethodKey = 'requestMethod';

  /**
   * Constructor de la clase json_response_metadata.
   * @param  int    $errorCode    Valor del error.
   * @param  int    $rowsAffected Cantidad de columnas afectadas.
   * @param  string $queryString  Query ejecutado.
   */
  function __construct($errorCode, $rowsAffected = 0, $queryString = "", $requestMethod = ""){    
    
    # Se verifica que $errorCode sea un número entero.
    if(is_int($errorCode))
      $this->errorCode = $errorCode;
    else
      $this->errorCode = -1;

    # Asigna el mensaje según el $errorCode.
    $this->errorMessage = json_response_codes::getJsonMessage($this->errorCode);

    # Se verifica que $rowsAffected sea un número entero.
    if(is_int($rowsAffected))
      $this->rowsAffected = $rowsAffected;
    else
      $this->rowsAffected = -1;

    # Se verifica que $queryString sea un String.
    if (is_string($queryString))
      $this->queryString = $queryString;

    # Se verifica que $queryString sea un String.
    if (is_string($requestMethod))
      $this->requestMethod = $requestMethod;
  }

  /**
   * Retorna un arreglo con toda la metadata.
   * @return array Arreglo con todos los valores de la metadata.
   */
  function getMetaData(){ 
    $result[METADATA_KEY][self::errorCodeKey]    = $this->errorCode;
    $result[METADATA_KEY][self::rowsAffectedKey] = $this->rowsAffected;
    if(DEBUG_MODE){
      $result[METADATA_KEY]['debug'][self::errorMessageKey] = $this->errorMessage;
      $result[METADATA_KEY]['debug'][self::requestMethodKey] = $this->requestMethod;
      $result[METADATA_KEY]['debug'][self::queryStringKey]   = $this->queryString;
    }
    return $result;
  }
}

/**
 * Clase estatica que se encarga de generar la estructura JSON para el retorno.
 */
class json_response{

  private function __construct(){}
  private function __clone(){}

  # Constantes que definen el KEY para el arreglo
  const msgDescriptionKey = 'msgDescription';
  const lastInsertIdKey = 'lastInsertId';

  /**
   * Junta la metadata con la data.
   * @param  json_response_metadata $metadata    Objeto con la metadata de la respuesta.
   * @param  PDO                    $data        Objeto PDO resultado del Query ejecutado.
   * @return JSON           Respuesta JSON final.
   */
  public static function generate($metadata, $data, $lastInsertId = ""){

    $result = $metadata->getMetaData();

    $result[DATA_KEY] = array();

    if(!empty($lastInsertId))
      $result[DATA_KEY][self::lastInsertIdKey] = $lastInsertId;

    # Se procede a insertar la data.
    if(is_string($data))
    {
      $result[DATA_KEY][self::msgDescriptionKey] = $data;
    }
    else
    {
      foreach ($data as $row) {
        array_push($result[DATA_KEY], $row);
      }
    }

    return json_encode($result);
  }

  /**
   * Crea una respuesta JSON generica para los errores.
   * @param  string $msgDescription Descripción del error que estará en el cuerpo del mensaje.
   * @return JSON                   JSON con el formato generico de respuesta de error. 
   */
  public static function error($msgDescription = DEFAULT_ERROR_MSG, $queryString = ""){

    $metadata = new json_response_metadata(JR_ERROR, 0, $queryString, $_SERVER['REQUEST_METHOD']);
    $result = $metadata->getMetaData();
    $result[DATA_KEY][self::msgDescriptionKey] = $msgDescription;

    return json_encode($result);
  }
}
?>