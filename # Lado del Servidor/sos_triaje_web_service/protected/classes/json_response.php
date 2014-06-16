<?php
/**
* 
*/

# Puede que no haga falta la inclusión de estas constantes 
# aca ya que estas seran recibidas por parametros.
require_once '/../consts/json_response_error_codes.php';

class json_response{

  private static $response;

  # Lista de constantes que definen el valor del $errorCode 

  public static function echo_test( $msg ){
    //echo 'This is a test, '. $msg . '<br>';

    # Estructura generica para las respuestas JSON.
    $response = array( 
      'errorCode'     => JR_SUCCESS, // Se recibe por parametro!!!
      'errorMessage'  => JR_SUCCESS_MSG . ' ' . $msg, // Se recibe por parametro!!!
      'numRows'  => 0 // Se recibe por parametro!!!
        # Arreglo de datos de respuesta...
      );

    echo json_encode( $response ) . '<br>';

  }

  # funcion que asigne el mensaje segun el valor del $errorCode

  public static function generate( $errorCode , $errorMessage , $data ){
     
     return json_encode( $response );
  }
  /**/
}
?>