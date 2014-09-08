<?php
header('Access-Control-Allow-Origin: *');
try {
    /*This variables you can modify*/
    $user     = "admin";
    $password = "Tajrh123654";
    $ipServer = "192.168.2.44";
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

    $client = new SoapClient("http://$ipServer/modules/address_book/scenarios/soap.php?WSDL",$soapOptions);

    $param = new SoapParam(
        array(
            "addressBookType" => "external"
        ),
        'listAddressBook'
    );

    $resultado = $client->listAddressBook($param);

    print_r($resultado);
}
catch (SoapFault $e) {
    if(isset($e->faultactor) && isset($e->detail))
        print "{$e->faultcode} - {$e->faultstring} - Web Service {$e->faultactor} - {$e->detail}\n";
    else
        print "{$e->faultcode} - {$e->faultstring}\n";
    exit(1);
}

?>
