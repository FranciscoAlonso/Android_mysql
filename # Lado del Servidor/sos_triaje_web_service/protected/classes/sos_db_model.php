<?php
/*
* 
*/
class sos_db_model{

	public $DBH;

	function __construct(){

        require_once DIR_CONSTANTS . 'db_config.php';

		try {
            # *** Conexión fallida ***
            # $this->DBH = new PDO("mysql:host=localhost;dbname=database", SOS_DB_USER , SOS_DB_PASSWORD );

            # Modo Silencioso:
            //$this->DBH = new PDO("mysql:host=" . SOS_DB_SERVER .";dbname=" . SOS_DB_NAME , SOS_DB_USER , SOS_DB_PASSWORD, array(PDO::ATTR_ERRMODE => GLOBAL_PDO_ERROR_MODE));

            # Modo de Advertencia:
            $this->DBH = new PDO("mysql:host=" . SOS_DB_SERVER .";dbname=" . SOS_DB_NAME , SOS_DB_USER , SOS_DB_PASSWORD, array(PDO::ATTR_ERRMODE => GLOBAL_PDO_ERROR_MODE));
            //return new PDO("mysql:host=" . SOS_DB_SERVER .";dbname=" . SOS_DB_NAME , SOS_DB_USER , SOS_DB_PASSWORD, array(PDO::ATTR_ERRMODE => GLOBAL_PDO_ERROR_MODE));
            
            # Modo de Excepción:
            //$this->DBH = new PDO("mysql:host=" . SOS_DB_SERVER .";dbname=" . SOS_DB_NAME , SOS_DB_USER , SOS_DB_PASSWORD, array(PDO::ATTR_ERRMODE => GLOBAL_PDO_ERROR_MODE));

        echo '# sos_db_model constructor #' . '<br>';
        } catch ( PDOException $e ) {
            # echo 'Conexi&oacute;n Fallida: <br>' . $e->getMessage();
            // Usar variables para los Strings
            exit('Conexi&oacute;n Fallida: <br>' . $e->getMessage());
        }

    }

    function __destruct() {
        $this->DBH = NULL;
        echo '# sos_db_model destructor #' . '<br>';
    }
    
    /**
    * Esta función se encarga de ejecutar query's con los parametros obtenidos.
    * @param $query String con el query que se desea ejecutar, las variables deben comenzar con dos puntos (:).
    * @param $params Arreglo con los valores de las variables a colocar con el query, deben comenzar con dos puntos (:). Puede ser NULL si el query no posee parametros.
    * @return Retorna el resultado de la ejecución del query.
    */
    function execute( $query , $params ){

        $STMT = $this->DBH->prepare( $query );

        $STMT->execute( $params );

        //$STMT->setFetchMode( GLOBAL_PDO_FETCH_MODE );
        $STMT->setFetchMode( PDO::FETCH_ASSOC );

        return $STMT;
    }

    function getEspecialidades(){

        # Query para obtener las especialidades
        $query =
        'SELECT *
        FROM especialidad';

        /*
        WHERE id != :id';
        # Definión de los parametros para el query
        $params = array( ':id' => 999 );
        /**/

        # Retorno del resultado
        return $this->execute( $query , NULL );

    }

}
?>