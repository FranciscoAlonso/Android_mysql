<?php
/*
Adress IP:
\bAddr->IP : (25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\b

Status: 
\bStatus : OK\b

Explode:
$str = "Hello world. It's a beautiful day.";
print_r (explode(" ",$str));

Regexp:
$subject = "abcdef";
$pattern = '/^def/';
preg_match($pattern, substr($subject,3), $matches, PREG_OFFSET_CAPTURE);
print_r($matches);

# preg_match() returns 1 if the pattern matches given subject, 0 if it does not, or FALSE if an error occurred.

# The \b in the pattern indicates a word boundary, so only the distinct
# word "web" is matched, and not a word partial like "webbing" or "cobweb"
if (preg_match("/\bweb\b/i", "PHP is the web scripting language of choice.")) {
    echo "A match was found.";
} else {
    echo "A match was not found.";
}

if (preg_match("/\bweb\b/i", "PHP is the website scripting language of choice.")) {
    echo "A match was found.";
} else {
    echo "A match was not found.";
}
*/

/* 
Fuentes:
	http://www.venturevoip.com/news.php?rssid=2217
	http://www.venturevoip.com/peer-status.phps

Para poder utilizar este código es necesario que en el Asterisk se habilite
el usuario en la ACL del manager.conf, de otra forma no podra conectarse 
a menos que sea de forma local

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
$manager_host = "192.168.2.44";
$manager_host = "190.72.194.241";
$manager_user = "admin";
$manager_pass = "Tajrh123654";

/* Default Port */
$manager_port = "5038";

/* Connection timeout */
$manager_connection_timeout = 30;

/* The Asterisk peer you would like to check */
$peer_name = "6001";
if ( !empty($_GET['user']))
$peer_name = $_GET['user'];

/* The type of peer (i.e. iax2 or sip) */
$peer_type = "sip";

/* Connect to the manager */
$fp = fsockopen($manager_host, $manager_port, $errno, $errstr, $manager_connection_timeout);
if (!$fp) 
{
    echo "There was an error connecting to the manager: $errstr (Error Number: $errno)\n";
} 
else 
{
    echo "-- Connected to the Asterisk Manager\n";
    echo '<br>';
    echo "-- About to log in\n";
    echo '<br>';

    $login = "Action: login\r\n";
    $login .= "Username: $manager_user\r\n";
    $login .= "Secret: $manager_pass\r\n";
    $login .= "Events: Off\r\n";
    $login .= "\r\n";
    fwrite($fp,$login);

    $manager_version = fgets($fp);
    echo $manager_version . '<br>'; # Asterisk Call Manager/1.1
    
    $cmd_response = fgets($fp);
    echo $cmd_response . '<br>'; # Response: Success

    $response = fgets($fp);
    echo $response . '<br>'; # Message: Authentication accepted

    $blank_line = fgets($fp);
    echo $blank_line . '<br>'; #

    if (substr($response,0,9) == "Message: ") 
    {
        /* We have got a response */
        $loginresponse = trim(substr($response,9));
        
        if (!$loginresponse == "Authentication Accepted") 
        {
            echo "-- Unable to log in: $loginresponse\n";
            fclose($fp);
            exit(0);
        } 
        else 
        {
            echo "-- Logged in Successfully\n";
            echo '<br>';
            
            $checkpeer = "Action: Command\r\n";
            $checkpeer .= "Command: $peer_type show peer $peer_name\r\n";
            //$checkpeer .= "Command: core show channels\r\n";
            //$checkpeer .= "Command: $peer_type show peer 0000\r\n"; # STATUS: Peer 0000 not found.
            //$checkpeer .= "Command: $peer_type show peers\r\n";
            $checkpeer .= "\r\n";
            fwrite($fp,$checkpeer);
            
            $line = trim(fgets($fp));
            $found_entry = false;

            echo "<br>#" . $line . '<br>';

            # -- CREAR CONTADOR PARA EVITAR LOOP INFINITO (> 70 #)
            while ($line != "--END COMMAND--") 
            {
                if (substr($line,0,6) == "Status") 
                {
                    $status = trim(substr(strstr($line, ":"),1));
                    $found_entry = true;
                
                    if (substr($status,0,2) == "OK")
                    {
                        $peer_ok = true;
                    }
                    else
                    {
                        $peer_ok = false;
                    }
                }
                $line = trim(fgets($fp));
                echo "#" . $line . '<br>';
            }


            if ($found_entry == false)
            {   # PEER NOT FOUND (Caso que no ocurrirá en la app ya que los usuarios son tomados de la misma BD de asterisk)
                echo "-- We didn't get the response we were looking for - is the peer name correct?\n";
            } 
            else if ($peer_ok == true)
            {   # STATUS OK
                echo "-- Peer looks good at the moment: >$status<\n";
                # FALTA VERIFICAR SI SE ENCUENTRA DISPONIBLE, que no se encuentre llamando...
                // Ejecutar el comando 'core show channels' y verificar si el peer está o no en la lista. 
            }
            else
            {   # SE RECIBIO UNA RESPUESTA DISTINTA A UN "OK", PEER NO CONECTADO

                /* We received a response other than ok - you can really do whatever */
                /* you want here - in this example I'm going to use the originate    */
                /* command to call me and play me the tt-monkeys sound - if I hear   */
                /* this then I know there is an issue :)                             */
                echo "-- Peer not ok ($status) - running some code\n";

                $originate = "Action: originate\r\n";
                $originate .= "Channel: SIP/6002\r\n";
                $originate .= "Application: Playback\r\n";
                $originate .= "Data: tt-monkeys\r\n";
                $originate .= "\r\n";
                fwrite($fp, $originate);
            
            }
           
            fclose($fp);
            exit(0);
        
        }

    } 
    else 
    {
        echo "Unexpected response: $response\n";
        fclose($fp);
        exit(0);
    }

}

?>