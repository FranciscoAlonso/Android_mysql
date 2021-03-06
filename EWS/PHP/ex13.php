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
    /*The user autenticated must have an inbox for voicemails in /var/spool/asterisk/voicemail/default/'extension_of_user'/INBOX
      if not will be a SOAP fault */
    $client = new SoapClient("http://$ipServer/modules/voicemail/scenarios/soap.php?WSDL",$soapOptions);

    $param = new SoapParam(
        array(
            "enable" 		=> true,
	    "email"   		=> "user@example.com",
	    "password"   	=> "password",
	    "confirmPassword"   => "password",
	    "emailAttachment"   => false,
	    "playCID"  		=> true,
	    "playEnvelope"   	=> false,
	    "deleteVmail"   	=> true
        ),
        'setConfiguration'
    );

    $resultado = $client->setConfiguration($param);

    if($resultado->return)
      echo "Voicemail was configurated";
    else
      echo "Voicemail could not be configurated";

}
catch (SoapFault $e) {
    if(isset($e->faultactor) && isset($e->detail))
        print "{$e->faultcode} - {$e->faultstring} - Web Service {$e->faultactor} - {$e->detail}\n";
    else
        print "{$e->faultcode} - {$e->faultstring}\n";
    exit(1);
}

?>
