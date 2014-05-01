<?php

# Fuente: http://www.infi.nl/blog/view/id/70/Displaying_asterisk_online_status_on_a_web_page

function exec_get_output($command){
	$output;
	exec($command,$output);
	return implode("\n",$output);
}

$peers = exec_get_output("/usr/sbin/asterisk -r -x 'sip show peers'");

/*
echo 
"--- peers ---<br>" . 
	$peers
. "<br>--- end peers ---<br>";
/**/

# Find user
preg_match_all("/([0-9a-z]*)(\/[0-9a-z]*)?[ ]*(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}|[0-9a-z\/]*[ ]*\(Unspecified\))/",$peers,$matches);

$names = $matches[1];
$ipAddresses = $matches[3];

//We are returning a png image
header('Content-Type: application/json');

if(($index = array_search($_GET['user'], $names)) === false){
	# User was not found
	echo "Unknown";
}else if($ipAddresses[$index] == '(Unspecified)'){
	# User is not logged in
	echo "Unavailable";
}else{
	# Get current calls
	$channels = exec_get_output("/usr/sbin/asterisk -r -x 'core show channels'");
	
	# Find requested user
	preg_match("/SIP\/" . $_GET['user'] . "*-[a-f0-9]*/",$channels,$matches);
	
	if($matches[0]){
		# User is on the phone
		echo "Busy";
	}else{
		# User is available
		echo "Available w/content type";
		$arr = array("a" => "1");
		echo json_encode($arr);
	}
}

?>
