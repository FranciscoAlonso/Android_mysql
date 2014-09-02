<?php
/**
 * Clase que se encarga de realizar la conexión con la base de datos 
 * "asteriskcdrdb" y ofrece funciones para obtener data básica.
 */
class elastix_db_model{

	private $DBH;

    /**
     * Realiza la conexión con la BD 'asteriskcdrdb'
     * @throws PDOException If Ocurre algún error al momento que establecer la conexión con la BD.
     */
	public function __construct($DB_NAME = ""){

        require_once DIR_CONSTANTS . '/db_config.php';

        if(empty($DB_NAME))
            $DB_NAME = ELASTIX_DB_NAME;

		try {
            # *** Prueba de conexión fallida ***
            //$this->DBH = new PDO("mysql:host=localhost;dbname=database", ELASTIX_DB_USER , ELASTIX_DB_PASSWORD ); 
            $this->DBH = new PDO(
                                "mysql:host="   . ELASTIX_DB_SERVER .
                                ";dbname="      . $DB_NAME,
                                ELASTIX_DB_USER,
                                ELASTIX_DB_PASSWORD,
                                array(
                                    PDO::ATTR_ERRMODE => GLOBAL_PDO_ERROR_MODE,
                                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
                                )
                            );
        } catch (PDOException $e) {
            # Se crea el mensaje de error para informar a la app/usuario de lo ocurrido.
            API::throwPDOException($e);
        }
    }

    /**
     * Cierra la conexión con la BD 'sos_triaje'
     */
    public function __destruct() {
        $this->DBH = null;
    }

    /**
     * Obtiene el último id insertado.
     * @return string   id del último registro insertado.
     */
    public function getLastInsertId(){
        return $this->DBH->lastInsertId();
    }

    /**
     * Esta función se encarga de ejecutar query's con los parametros obtenidos.
     * @param string $query  String con el query que se desea ejecutar, las variables deben comenzar con dos puntos (:).
     * @param array  $params Arreglo con los valores de las variables a colocar con el query, deben comenzar con dos puntos (:). Puede ser NULL si el query no posee parametros.
     * @return PDO   Retorna el resultado de la ejecución del query (Statement).
     * @throws PDOException If Ocurre un error al ejecutar el query en la BD.
     */
    public function execute($query, $params = null){
        try {
            $STMT = $this->DBH->prepare($query);
            $STMT->execute($params);
            $STMT->setFetchMode(GLOBAL_PDO_FETCH_MODE);
            //exit($STMT->debugDumpParams());
            //throw new PDOException("DEBUG_MODE: Execute Exception");
            return $STMT;
        } catch (PDOException $e) {
            //exit($e->getMessage());
            API::throwPDOException($e, 500, $STMT->queryString);
        }
    }
}
?>