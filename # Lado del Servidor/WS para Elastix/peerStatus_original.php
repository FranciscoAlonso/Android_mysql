<?php

# Fuente: http://www.infi.nl/blog/view/id/70/Displaying_asterisk_online_status_on_a_web_page

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
	preg_match("/SIP\/" . $_GET['user'] . "*-[a-f0-9]*/",$channels,$matches);
	
	if($matches[0]){
		//User is on the phone
		print file_get_contents('images/busy.png');
	}else{
		//User is available
		print file_get_contents('images/available.png');
	}
}

?>
