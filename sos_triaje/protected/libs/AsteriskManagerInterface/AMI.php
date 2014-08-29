<?php
/**
 * Clase 
 */
class AMI{

	private $fp; # File Pointer usado para el socket.
	private $manager_version; # Versión del Asterisk Call Manager.
	private $connection_response; # Respuesta "Response: Success" o "Response: Error" de la conexión. 
	private $connection_msg; # Mensaje de la conexión.

	const MAX_LOOPS = 100; # Cantidad máxima de ciclos al leer del socket (para evitar un loop infinito).
	const IP_PATTERN = '/\b(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\b/';

	/**
	 * Conecta con el AMI y ejecuta el comando recibido por parametro.
	 * @param  string $command comando a ejecutar en Asterisk.
	 * @return file pointer          Apuntador del archivo para el socket
	 * @throws [exceptionType] If [this condition is met]
	 */
	public function __construct(){

		require_once DIR_CONSTANTS . '/db_config.php';

		# Connect to the manager
		$this->fp = fsockopen(
						ELASTIX_AMI_HOST
						, ELASTIX_AMI_PORT
						, $errno
						, $errstr
						, ELASTIX_AMI_TIMEOUT
					);

		if (!$this->fp)
		{
			//echo "There was an error connecting to the manager: $errstr (Error Number: $errno)\n";
		}
		else
		{
			/*
			echo "-- Connected to the Asterisk Manager\n";
			echo '<br>';
			echo "-- About to log in\n";
			echo '<br>';
			/**/

			$login = "Action: login\r\n";
			$login .= "Username: " . ELASTIX_AMI_USER . "\r\n";
			$login .= "Secret: " . ELASTIX_AMI_PASSWORD . "\r\n";
			$login .= "Events: Off\r\n";
			$login .= "\r\n";
			fwrite($this->fp, $login);

			$this->manager_version = fgets($this->fp);
			$this->connection_response = fgets($this->fp);
			$this->connection_msg = fgets($this->fp);
			$blank_line = fgets($this->fp);

			/*
			echo $this->manager_version . '<br>'; # Asterisk Call Manager/1.1
			echo $this->connection_response . '<br>'; # Response: Success
			echo $this->connection_msg . '<br>'; # Message: Authentication accepted
			echo $blank_line . '<br>'; #
			/**/

			if (substr($this->connection_msg, 0, 9) == "Message: ")
			{
				# We have got a response
				$loginresponse = trim(substr($this->connection_msg, 9));

				if (!$loginresponse == "Authentication Accepted")
				{
					echo "-- Unable to log in: " . $loginresponse . "\n";
					fclose($this->fp);
				}
				else
				{
					//echo "-- Logged in Successfully\n";
					//echo '<br>';
				}
			}
			else
			{
				//echo "Unexpected response: " . $this->connection_msg . "\n";
				fclose($this->fp);
			}
		}

		//echo ("************************ SE HA CREADO LA CLASE AMI ************************<br>");
	}

	public function __destruct(){
		fclose($this->fp);
		//echo ("<br>************************ SE HA DESTRUIDO LA CLASE AMI ************************");
	}

	/*
	public function getFilePointer(){
		return $this->fp;
	}
	/**/

	public static function isPeerConnected($peer_name = ""){

		echo "-- Logged in Successfully\n";
		echo '<br>';

		$command = "Action: Command\r\n";
		$command .= "Command: " . ELASTIX_AMI_PEER_TYPE . " show peer " . $peer_name . "\r\n";
		$command .= "\r\n";

		fwrite($fp, $command);

		$line = trim(fgets($fp));

		$found_entry = false;
		echo "<br>#" . $line . '<br>';

		$index = 100; # Contador para evitar posible loop infinito.

		while ($line != "--END COMMAND--" && $index > 0)
		{
			if (substr($line, 0, 6) == 'Status')
			{
				$status = trim(substr(strstr($line, ":"), 1));
				$found_entry = true;

				if (substr($status, 0, 2) == 'OK')
				{
					$peer_ok = true;
					echo("******************************************** PEER " . substr($status, 0, 2) . " - ");
				}
				else
				{
					$peer_ok = false;
					echo("############################################ ERROR: PEER " . $status . " - ");
				}
			}

			if(substr($line, 0, 8) == 'Addr->IP')
			{
				$ip_addr = trim(substr(strstr($line, ":"), 1));
				$ip_addr = "192.168.1.255";

				$found_entry = true;

				if (preg_match(self::IP_PATTERN, $ip_addr))
				{
					//$peer_ip_ok = true;
					echo("******************************************** IP VALID: " . $ip_addr . " - ");
				}
				else
				{
					//$peer_ip_ok = false;
					echo("############################################ ERROR: IP NOT VALID: " . $ip_addr . " - ");
				}							
			}

			$line = trim(fgets($fp));
			echo "#" . $line . '<br>';
			
			$index--;
		}

		if ($found_entry == false)
		{	# PEER NOT FOUND (Caso que no ocurrirá en la app ya que los usuarios son tomados de la misma BD de asterisk)
			echo "-- We didn't get the response we were looking for - is the peer name correct?\n";
		}
		else if ($peer_ok == true)
		{	# STATUS OK
			echo "-- Peer looks good at the moment: >$status<\n";
			# FALTA VERIFICAR SI SE ENCUENTRA DISPONIBLE, que no se encuentre llamando...
			// Ejecutar el comando 'core show channels' y verificar si el peer está o no en la lista. 
		}
		else
		{	# SE RECIBIO UNA RESPUESTA DISTINTA A UN "OK", PEER NO CONECTADO

			# We received a response other than ok - you can really do whatever
			# you want here - in this example I'm going to use the originate   
			# command to call me and play me the tt-monkeys sound - if I hear  
			# this then I know there is an issue :)                            
			echo "-- Peer not ok ($status) - running some code\n";

			/*
			$originate = "Action: originate\r\n";
			$originate .= "Channel: SIP/6002\r\n";
			$originate .= "Application: Playback\r\n";
			$originate .= "Data: tt-monkeys\r\n";
			$originate .= "\r\n";
			fwrite($fp, $originate);
			/**/
		}
		fclose($fp);
		exit(0);
	}

	/**
	 * [isPeerConnected description]
	 * @param  string  $peer [description]
	 * @return boolean       [description]
	 */
	public static function isPeerConnected_old($peer_name = ""){

		require_once DIR_CONSTANTS . '/db_config.php';

		# The Asterisk peer you would like to check
		if (empty($peer_name))
			$peer_name = "6001"; ########### Si esta vacío arrojar una excepción ###########

		# The type of peer (i.e. iax2 or sip)
		$peer_type = "sip";

		# Connect to the manager
		$fp = fsockopen(
						ELASTIX_AMI_HOST
						, ELASTIX_AMI_PORT
						, $errno
						, $errstr
						, ELASTIX_AMI_TIMEOUT
					);

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
			$login .= "Username: " . ELASTIX_AMI_USER . "\r\n";
			$login .= "Secret: " . ELASTIX_AMI_PASSWORD . "\r\n";
			$login .= "Events: Off\r\n";
			$login .= "\r\n";
			fwrite($fp, $login);

			$manager_version = fgets($fp);
			echo $manager_version . '<br>'; # Asterisk Call Manager/1.1
			
			$cmd_response = fgets($fp);
			echo $cmd_response . '<br>'; # Response: Success

			$response = fgets($fp);
			echo $response . '<br>'; # Message: Authentication accepted

			$blank_line = fgets($fp);
			echo $blank_line . '<br>'; #

			if (substr($response, 0, 9) == "Message: ") 
			{
			# We have got a response
				$loginresponse = trim(substr($response, 9));

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

					$command = "Action: Command\r\n";
					$command .= "Command: " . ELASTIX_AMI_PEER_TYPE . " show peer " . $peer_name . "\r\n";
					//$command .= "Command: core show channels\r\n";
					//$command .= "Command: " . ELASTIX_AMI_PEER_TYPE . " show peer 0000\r\n"; # STATUS: Peer 0000 not found.
					//$command .= "Command: " . ELASTIX_AMI_PEER_TYPE . " show peers\r\n";
					$command .= "\r\n";

					fwrite($fp, $command);

					$line = trim(fgets($fp));
					# --------------------------------------------------------------------------------------------------------------
					# 
					# --------------------------------------------------------------------------------------------------------------
					$found_entry = false;
					echo "<br>#" . $line . '<br>';

					$index = 100; # Contador para evitar posible loop infinito.

					while ($line != "--END COMMAND--" && $index > 0)
					{
						if (substr($line, 0, 6) == 'Status')
						{
							$status = trim(substr(strstr($line, ":"), 1));
							$found_entry = true;

							if (substr($status, 0, 2) == 'OK')
							{
								$peer_ok = true;
								echo("******************************************** PEER " . substr($status, 0, 2) . " - ");
							}
							else
							{
								$peer_ok = false;
								echo("############################################ ERROR: PEER " . $status . " - ");
							}
						}

						if(substr($line, 0, 8) == 'Addr->IP')
						{
							$ip_addr = trim(substr(strstr($line, ":"), 1));
							$ip_addr = "192.168.1.255";

							$found_entry = true;

							if (preg_match(self::IP_PATTERN, $ip_addr))
							{
								//$peer_ip_ok = true;
								echo("******************************************** IP VALID: " . $ip_addr . " - ");
							}
							else
							{
								//$peer_ip_ok = false;
								echo("############################################ ERROR: IP NOT VALID: " . $ip_addr . " - ");
							}							
						}

						$line = trim(fgets($fp));
						echo "#" . $line . '<br>';
						
						$index--;
					}

					if ($found_entry == false)
					{	# PEER NOT FOUND (Caso que no ocurrirá en la app ya que los usuarios son tomados de la misma BD de asterisk)
						echo "-- We didn't get the response we were looking for - is the peer name correct?\n";
					}
					else if ($peer_ok == true)
					{	# STATUS OK
						echo "-- Peer looks good at the moment: >$status<\n";
						# FALTA VERIFICAR SI SE ENCUENTRA DISPONIBLE, que no se encuentre llamando...
						// Ejecutar el comando 'core show channels' y verificar si el peer está o no en la lista. 
					}
					else
					{	# SE RECIBIO UNA RESPUESTA DISTINTA A UN "OK", PEER NO CONECTADO

						# We received a response other than ok - you can really do whatever
						# you want here - in this example I'm going to use the originate   
						# command to call me and play me the tt-monkeys sound - if I hear  
						# this then I know there is an issue :)                            
						echo "-- Peer not ok ($status) - running some code\n";

						/*
						$originate = "Action: originate\r\n";
						$originate .= "Channel: SIP/6002\r\n";
						$originate .= "Application: Playback\r\n";
						$originate .= "Data: tt-monkeys\r\n";
						$originate .= "\r\n";
						fwrite($fp, $originate);
						/**/
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

		return "";
	}
	/**/
}
?>