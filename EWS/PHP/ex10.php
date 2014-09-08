<?php

try {
    /*This variables you can modify*/
   $user     = "any_elastix_user";
    $password = "password";
    $ipServer = "ip_elastix_server"
   /********************************/

    $soapOptions = array(
        'soap_version'          => SOAP_1_1,
        'trace'                 =>  TRUE,
        'exceptions'            =>  TRUE,
        'compression'           =>
            SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP,
        'connection_timeout'    => 15,

        'login'                 =>  $user,
        'password'              =>  $password,
    );

    $client = new SoapClient("http://$ipServer/modules/myex_config/scenarios/soap.php?WSDL",$soapOptions);

    $param = new SoapParam(
        array(
            "doNotDisturb"  => true
        ),
        'setDoNotDisturb'
    );

    $resultado = $client->setDoNotDisturb($param);

    if($resultado->return)
      echo "Option do not disturb was configurated";
    else
      echo "Option do not disturb could not be configurated";
}
catch (SoapFault $e) {
    if(isset($e->faultactor) && isset($e->detail))
        print "{$e->faultcode} - {$e->faultstring} - Web Service {$e->faultactor} - {$e->detail}\n";
    else
        print "{$e->faultcode} - {$e->faultstring}\n";
    exit(1);
}

?>
