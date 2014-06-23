<?php
/**
* 
*/

# Se incluyen las dependencias para las respuestas json
require_once DIR_CONSTANTS . 'json_response_error_codes.php';
require_once DIR_CONSTANTS . 'json_response_error_messages.php';

define('QT_INSERT','insert');
define('QT_SELECT','select');
define('QT_UPDATE','update');
define('QT_DELETE','delete');

class json_response_metadata{
  
  private $queryType; # SELECT, UPDATE, DELETE
  private $errorCode;
  private $errorMessage;
  private $rowsAffected;

  # Getters
  function getQueryType(){ return $this->queryType; }
  function getErrorCode(){ return $this->errorCode; }
  function getErrorMessage(){ return $this->errorMessage; }
  function getRowsAffected(){ return $this->rowsAffected; }

  # Único constructor
  function json_response_metadata($queryType, $errorCode, $rowsAffected){    
    # Se verifica si es un queryType válido
    if($queryType === QT_INSERT || $queryType === QT_SELECT || $queryType === QT_UPDATE || $queryType === QT_DELETE)
      $this->queryType = $queryType;
    else
      $this->queryType = 'Invalid query type';

    # Se verifica que $errorCode sea un número entero 
    if(is_int($errorCode))
      $this->errorCode = $errorCode;
    else
      $this->errorCode = -1;

    # Asigna el mensaje según el $errorCode
    $this->errorMessage = json_response_codes::getJsonMessage($this->errorCode);

    # Se verifica que $rowsAffected sea un número entero
    if(is_int($rowsAffected))
      $this->rowsAffected = $rowsAffected;
    else
      $this->rowsAffected = -1;
  }

}

class json_response{

  private static $response;

  #private function __construct(){}
  #private function __clone(){}

  # Esta función se encargará es de crear la estructura para el retorno
  public static function generate( $errorCode, $data ){

    # extraer datos de la metadata y juntarlo con la data
    return json_encode( $data );
  }

}
?>