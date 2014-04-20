<?php

###
# PAMI: http://marcelog.github.io/PAMI/index.html
###

	/*
	#echo "Esto es el Web service para SOS_TRIAJE.";

	#echo '<br><br>';

	$response = array("tag" => "TAG", "success" => 0, "error" => 0);
	
	$response["success"] = 1;
    $response["uid"] = "UUID";
    $response["user"]["name"] = "NAME";
    $response["user"]["email"] = "EMAIL";

    echo json_encode($response);

/**/

/* 
Fuentes:
	http://www.venturevoip.com/news.php?rssid=2217
	http://www.venturevoip.com/peer-status.phps
/**/

/* ============================ */
/*   PHP Asterisk Peer Status   */
/* ============================ */
/*    (C) 2009 Matt Riddell     */
/*     Daily Asterisk News      */
/* www.venturevoip.com/news.php */
/*      Public domain code      */
/* ============================ */

/* Connection details */
$manager_host = "190.72.197.215";
$manager_user = "admin";
$manager_pass = "Tajrh123654";

/* Default Port */
$manager_port = "5038";

/* Connection timeout */
$manager_connection_timeout = 30;

/* The Asterisk peer you would like to check */
$peer_name = "6001";

/* The type of peer (i.e. iax2 or sip) */
$peer_type = "sip";

/* Connect to the manager */
$fp = fsockopen($manager_host, $manager_port, $errno, $errstr, $manager_connection_timeout);
if (!$fp) {
    echo "There was an error connecting to the manager: $errstr (Error Number: $errno)\n";
} else {
    echo "-- Connected to the Asterisk Manager\n";
    echo "-- About to log in\n";

    $login = "Action: login\r\n";
    $login .= "Username: $manager_user\r\n";
    $login .= "Secret: $manager_pass\r\n";
    $login .= "Events: Off\r\n";
    $login .= "\r\n";
    fwrite($fp,$login);

    $manager_version = fgets($fp);

    $cmd_response = fgets($fp);

    $response = fgets($fp);

    $blank_line = fgets($fp);

    if (substr($response,0,9) == "Message: ") {
        /* We have got a response */
        $loginresponse = trim(substr($response,9));
        if (!$loginresponse == "Authentication Accepted") {
            echo "-- Unable to log in: $loginresponse\n";
            fclose($fp);
            exit(0);
        } else {
            echo "-- Logged in Successfully\n";
            $checkpeer = "Action: Command\r\n";
            $checkpeer .= "Command: $peer_type show peer $peer_name\r\n";
            $checkpeer .= "\r\n";
            fwrite($fp,$checkpeer);
            $line = trim(fgets($fp));
            $found_entry = false;
            while ($line != "--END COMMAND--") {
                if (substr($line,0,6) == "Status") {
                    $status = trim(substr(strstr($line, ":"),1));
                    $found_entry = true;
                    if (substr($status,0,2) == "OK") {
                        $peer_ok = true;
                    } else {
                        $peer_ok = false;
                    }
                }
                $line = trim(fgets($fp));
            }
            if ($found_entry == false) {
                echo "-- We didn't get the response we were looking for - is the peer name correct?\n";
            } else if ($peer_ok == true) {
                echo "-- Peer looks good at the moment: >$status<\n";
            } else {
                /* We received a response other than ok - you can really do whatever */
                /* you want here - in this example I'm going to use the originate    */
                /* command to call me and play me the tt-monkeys sound - if I hear   */
                /* this then I know there is an issue :)                             */
                echo "-- Peer not ok ($status) - running some code\n";

                $originate = "Action: originate\r\n";
                $originate .= "Channel: Zap/g1/1234r\n";
                $originate .= "Application: Playback\r\n";
                $originate .= "Data: tt-monkeys\r\n";
                $originate .= "\r\n";
                fwrite($fp, $originate);
            } 
            fclose($fp);
            exit(0);
        }
    } else {
        echo "Unexpected response: $response\n";
        fclose($fp);
        exit(0);
    }
}
    
?>

<?php

# Fuente: http://www.infi.nl/blog/view/id/70/Displaying_asterisk_online_status_on_a_web_page

/*
function exec_get_output($command){
    $output;
    exec($command,$output);
    return implode("\n",$output);
}

$peers = exec_get_output("/usr/sbin/asterisk -r -x 'sip show peers'");

//Find users
preg_match_all("/([0-9a-z]*)(\/[0-9a-z]*)?[ ]*(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}|[0-9a-z\/]*[ ]*\(Unspecified\))/",$peers,$matches);

$names = $matches[1];
$ipAddresses = $matches[3];

//We are returning a png image
header('Content-Type: image/png');

if(($index = array_search($_GET['user'], $names)) === false){
    //User was not found
    print file_get_contents('images/unknown.png');
}else if($ipAddresses[$index] == '(Unspecified)'){
    //User is not logged in
    print file_get_contents('images/unavailable.png');
}else{
    //Get current calls
    $channels = exec_get_output("/usr/sbin/asterisk -r -x 'core show channels'");
    
    //Find requested user
    /**/
    #preg_match("/SIP\/" . $_GET['user'] . "*-[a-f0-9]*/",$channels,$matches);
    /*
    if($matches[0]){
        //User is on the phone
        print file_get_contents('images/busy.png');
    }else{
        //User is available
        print file_get_contents('images/available.png');
    }
}
/**/
?>