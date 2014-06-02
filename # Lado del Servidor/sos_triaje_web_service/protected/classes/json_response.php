<?php
/*
* 
*/
# Puede que no haga falta la inclusión de estas constantes 
# aca ya que estas seran recibidas por parametros.
require_once '/../consts/json_response_error_codes.php';

class json_response{

  private static $response;

  public static function echo_test( $msg ){
    //echo 'This is a test, '. $msg . '<br>';

    # Estructura generica para las respuestas JSON.
    $response = array( 
      'errorCode'     => JR_SUCCESS, // Se recibe por parametro!!!
      'errorMessage'  => JR_SUCCESS_MSG // Se recibe por parametro!!!
        # Arreglo de datos de respuesta...
      );

    echo json_encode($response) . '<br>';

  }

  /*
  public static function generate_json_response( $errorCode , $errorMessage , $data ){
     
     return json_encode($response);
  }
  /**/
}
?>