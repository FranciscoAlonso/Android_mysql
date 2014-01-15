<?php

/**
 * Clase para la el manejo de la BD
 */
class DB_CONNECT {
 
    # constructor
    function __construct() {
        # Conexión a la BD
        $this->connect();
    }
 
    # Destructor
    function __destruct() {
        # Cierra conexión a la BD
        $this->close();
    }
 
    /**
     * Function to connect with database
     */
    function connect() {
        # Import de las constantes para la conexión a la BD
        require_once __DIR__ . '\db_config.php';
        
        ######################################################################################## 
        # CAPTURAR ESTOS POSIBLES ERRORES Y MANEJARLOS 
        # ( evitar el die(), pueda que la aplicación se comporte de foma extraña )
        #
        # Conexión a la BD mysql
        $con = mysql_connect( DB_SERVER , DB_USER , DB_PASSWORD ) or die( mysql_error() );
        #
        # Seleccionando BD
        $db = mysql_select_db( DB_DATABASE ) or die( mysql_error() ) or die( mysql_error() );
        #
        ######################################################################################## 
 
        # Retorna el cursor de conexión
        return $con;
    }
 
    /**
     * Función para cerrar la conexión con la BD
     */
    function close() {
        # Cierra conexión a la BD
        mysql_close();
    }
 
}
 
?>