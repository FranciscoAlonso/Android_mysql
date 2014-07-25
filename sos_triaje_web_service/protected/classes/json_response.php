<?php
/**
* 
*/

# Se incluyen las dependencias para las respuestas json
require_once DIR_CONSTANTS . '/json_response_error_codes.php';
require_once DIR_CONSTANTS . '/json_response_error_messages.php';

define('QT_INSERT','insert');
define('QT_SELECT','select');
define('QT_UPDATE','update');
define('QT_DELETE','delete');

define('METADATA_KEY' ,'metadata');
define('DATA_KEY'     ,'data');

class json_response_metadata{
  
  private $queryType; # SELECT, UPDATE, DELETE
  private $errorCode;
  private $errorMessage;
  private $rowsAffected;

  # Constantes que definen el KEY para el arreglo
  const queryTypeKey = 'queryType';
  const errorCodeKey = 'errorCode';
  const errorMessageKey = 'errorMessage';
  const rowsAffectedKey = 'rowsAffected';

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
  public static function generate( $metadata, $data ){

    # Extraer datos de la metadata y juntarlo con la data
    $result[METADATA_KEY][$metadata::queryTypeKey] = $metadata->getQueryType();
    $result[METADATA_KEY][$metadata::errorCodeKey] = $metadata->getErrorCode();
    $result[METADATA_KEY][$metadata::errorMessageKey] = $metadata->getErrorMessage();
    $result[METADATA_KEY][$metadata::rowsAffectedKey] = $metadata->getRowsAffected();

    $index = 0;
      while( $row = $data->fetch() ) {
        /*echo $row['id'] . " - ";
        echo $row['descripcion'] . " - ";
        echo $row['nombre'] . "<br>";/**/
        #print_r($row); echo '<br>';
        foreach( $row as $key => $value ) {
          //echo $key.' - '.$value.'<br />';
          //$result[DATA_KEY][$index][$key] = utf8_encode($value);
          $result[DATA_KEY][$index][$key] = $value;
        }
        $index++;
      }

    //echo $metadata::queryTypeKey . '<br>';
    //print_r( json_decode( json_encode($result, JSON_UNESCAPED_UNICODE) ) );
    return json_encode( $result);
  }

}
?>