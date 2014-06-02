<?php
/*
* Acceso principal al WEB SERVICE (WS)...
* Definición de cada una de las funcionalidades del WS.
*/
?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>SOS Triaje Web Services</title>
</head>
<body>
	
	<h1>It works! =D</h1>

	<?php 

	include('/../protected/consts/db_config.php');

	/*** mysql hostname ***/
	$hostname = 'localhost';

	/*** mysql username ***/
	$username = 'root';

	/*** mysql password ***/
	$password = '';


try {
	
$dbh = new PDO("mysql:host=$hostname;dbname=animals", $username, $password);
    /*** echo a message saying we have connected ***/
    echo 'Connected to database<br />';

    # $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
    //$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    //$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    /*** The SQL SELECT statement ***/
    $sql = "SELECT * FROM animals";

    /*** fetch into an PDOStatement object ***/
    $stmt = $dbh->query($sql);


    $stmt->setFetchMode(PDO::FETCH_OBJ);

    /*** echo number of columns ***/
    $obj = $stmt->fetch(PDO::FETCH_OBJ);
    
    //*** loop over the object directly ***
	//echo $obj->name . "<br />";
    echo $obj->animal_id.'<br />';
    echo $obj->animal_type.'<br />';
    echo $obj->animal_name.'<br />';
    /**/
/*
    # showing the results
	while($row = $stmt->fetch()) {
	    echo $row->name . " - ";
	    echo $row->animal_id . " - ";
	    echo $row->animal_type . " - ";
	    echo $row->animal_name . "<br>";
	}

/**/
    /*
	//*** an invalid table name ***
	$sql = "SELECT animal_id FROM users";

	//*** run the query ***
	$result = $dbh->query($sql);

	//*** show the error code ***
	//echo $dbh->errorCode().'<br />';

	//*** show the error info ***
	foreach($dbh->errorInfo() as $error)
    {
    	echo $error.'<br />';
    }
    /**/
    /*** close the database connection ***/
    //$dbh = null;

}
catch(PDOException $e)
    {
    echo '*** CATCH MESSAGE: ' . $e->getMessage() . ' ***';
    }
    /*
	//*** an invalid table name ***
	$sql = "SELECT animal_id FROM users";

	//*** run the query ***
	$result = $dbh->query($sql);

	//*** show the error code ***
	//echo $dbh->errorCode().'<br />';

	//*** show the error info ***
	foreach($dbh->errorInfo() as $error)
    {
    	echo $error.'<br />';
    }
    /**/

?>

<?php 

	include('/../protected/classes/json_response.php');

	json_response::echo_test("Hola mundo!");

?>

</body>
</html>