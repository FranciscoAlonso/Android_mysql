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

    $client = new SoapClient("http://$ipServer/modules/calendar/scenarios/soap.php?WSDL",$soapOptions);

    $param = new SoapParam(
        array(
            "startdate"           => "2011-04-27 15:00:00",
	    "enddate"  		  => "2011-04-27 19:00:00",
	    "subject"   	  => "Testing",
	    "description"	  => "test Elastix Web Service",
	    "asterisk_call" 	  => false,
	    "emails_notification" => "user@example.com"
        ),
        'addCalendarEvent'
    );

    $resultado = $client->addCalendarEvent($param);

    if($resultado->return)
      echo "Event added";
    else
      echo "Event could not be added";
}
catch (SoapFault $e) {
    if(isset($e->faultactor) && isset($e->detail))
        print "{$e->faultcode} - {$e->faultstring} - Web Service {$e->faultactor} - {$e->detail}\n";
    else
        print "{$e->faultcode} - {$e->faultstring}\n";
    exit(1);
}

?>
