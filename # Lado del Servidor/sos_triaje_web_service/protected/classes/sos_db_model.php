<?php
/*
* 
*/
class sos_db_model{

	private $dbh;

	function __construct(){

		require_once '/../consts/db_config.php';

		try {
            # *** Conexión fallida ***
            #$this->dbh = new PDO("mysql:host=localhost;dbname=database", SOS_DB_USER , SOS_DB_PASSWORD );

            # Modo Silencioso:
            //$this->dbh = new PDO("mysql:host=" . SOS_DB_SERVER .";dbname=" . SOS_DB_NAME , SOS_DB_USER , SOS_DB_PASSWORD, array(PDO::ATTR_ERRMODE => GLOBAL_PDO_ERROR_MODE));

            # Modo de Advertencia:
            $this->dbh = new PDO("mysql:host=" . SOS_DB_SERVER .";dbname=" . SOS_DB_NAME , SOS_DB_USER , SOS_DB_PASSWORD, array(PDO::ATTR_ERRMODE => GLOBAL_PDO_ERROR_MODE));
            
            # Modo de Excepción:
            //$this->dbh = new PDO("mysql:host=" . SOS_DB_SERVER .";dbname=" . SOS_DB_NAME , SOS_DB_USER , SOS_DB_PASSWORD, array(PDO::ATTR_ERRMODE => GLOBAL_PDO_ERROR_MODE));

        } catch ( PDOException $e ) {
            # echo 'Conexi&oacute;n Fallida: <br>' . $e->getMessage();
            // Usar variables para los Strings
            exit('Conexi&oacute;n Fallida: <br>' . $e->getMessage());
        }

	}

	function __destruct() {
        $this->dbh = NULL;
        echo '<br><br>DESTRUCTOR DESDE FUNCTION' . '<br>';
    }

    function getEspecialidades(){

    	$query = 
    	'SELECT *
    	FROM especialidad';

    	$stmt = $this->dbh->prepare($query);

    	$stmt->execute();

    	$array = $stmt->fetchAll( PDO::FETCH_ASSOC ); # Acomodar esta seccion 
        $json = json_encode( $array );
        echo $json;

    }

}
?>