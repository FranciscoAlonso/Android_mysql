<?php
	#echo "Esto es el Web service para SOS_TRIAJE.";

	#echo '<br><br>';

	$response = array("tag" => "TAG", "success" => 0, "error" => 0);
	
	$response["success"] = 1;
    $response["uid"] = "UUID";
    $response["user"]["name"] = "NAME";
    $response["user"]["email"] = "EMAIL";

    echo json_encode($response);
    
?>