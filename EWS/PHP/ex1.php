<?php

try {
    /*This variables you can modify*/
    $user     = "any_elastix_user";
    $password = "password";
    $ipServer = "ip_elastix_server";
    $file     = "msg0002.wav";
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

/*The user autenticated must have an inbox for voicemails in /var/spool/asterisk/voicemail/default/'extension_of_user'/INBOX if not will be a SOAP fault. Also the file must exists */

    $client = new SoapClient("http://$ipServer/modules/voicemail/scenarios/soap.php?WSDL",$soapOptions);

    $param = new SoapParam(
        array(
            "file" => $file
        ),
        'downloadVoicemail'
    );

    $resultado = $client->downloadVoicemail($param);

    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: public");
    header("Content-Description: wav file");
    header("Content-Type: " . $resultado->contentType);
    header("Content-Disposition: attachment; filename=msg0002.wav");
    header("Content-Transfer-Encoding: binary");
    header("Content-length: " . $resultado->size);
    echo base64_decode($resultado->audio);
}
catch (SoapFault $e) {
    if(isset($e->faultactor) && isset($e->detail))
        print "{$e->faultcode} - {$e->faultstring} - Web Service {$e->faultactor} - {$e->detail}\n";
    else
        print "{$e->faultcode} - {$e->faultstring}\n";
    exit(1);
}

?>
