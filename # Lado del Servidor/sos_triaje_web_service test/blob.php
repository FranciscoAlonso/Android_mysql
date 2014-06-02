<?php  

/* TEST PARA RECUPERAR UN ARCHIBO DE TIPO BLOB */

	require_once __DIR__ . '\protegido\db_config.php';

	$db = new PDO("mysql:host=" . DB_SERVER .";dbname=" . DB_DATABASE , DB_USER , DB_PASSWORD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

	$params = array(':id' => 3);

	$query = 
    'SELECT *
    FROM archivo
    WHERE id = :id';

    $stmt = $db->prepare($query);

    $stmt->execute($params);

/*
    $array = $stmt->fetchAll( PDO::FETCH_ASSOC );
    $json = json_encode( $array );
    echo $json;
/**/

	$stmt->bindColumn(1, $mime);
    $stmt->bindColumn(3, $data, PDO::PARAM_LOB);
 
    $stmt->fetch(PDO::FETCH_BOUND);

    $array = array("mime" => $mime,"data" => $data);

    //echo $params[':id'];
    
    header("Content-Type:" . "image/jpg");
	
	echo $array['data'];


?>