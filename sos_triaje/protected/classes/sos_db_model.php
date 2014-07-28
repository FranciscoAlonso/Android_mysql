<?php
/**
 * Clase que se encarga de realizar la conexión con la base de datos 
 * "sos_triaje" y ofrece funciones para obtener data básica.
 */
class sos_db_model{

	private $DBH;

    /**
     * Realiza la conexión con la BD 'sos_triaje'
     */
	function __construct(){

        require_once DIR_CONSTANTS . '/db_config.php';

		try {
            # *** Prueba de conexión fallida ***
            //$this->DBH = new PDO("mysql:host=localhost;dbname=database", SOS_DB_USER , SOS_DB_PASSWORD ); 
            $this->DBH = new PDO(
                                "mysql:host="   . SOS_DB_SERVER .
                                ";dbname="      . SOS_DB_NAME,
                                SOS_DB_USER,
                                SOS_DB_PASSWORD,
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
    function __destruct() {
        $this->DBH = null;
    }
    
    /**
    * Esta función se encarga de ejecutar query's con los parametros obtenidos.
    * @param string $query  String con el query que se desea ejecutar, las variables deben comenzar con dos puntos (:).
    * @param array  $params Arreglo con los valores de las variables a colocar con el query, deben comenzar con dos puntos (:). Puede ser NULL si el query no posee parametros.
    * @return PDO   Retorna el resultado de la ejecución del query (Statement).
    * @throws PDOException If Ocurre un error al ejecutar el query en la BD.
    */
    function execute($query, $params = null){
        try {
            $STMT = $this->DBH->prepare($query);
            $STMT->execute($params);
            $STMT->setFetchMode(GLOBAL_PDO_FETCH_MODE);
            //throw new PDOException("DEBUG_MODE: Execute Exception");
            return $STMT;
        } catch (PDOException $e) {
            API::throwPDOException($e, 500, $STMT->queryString);
        }
    }

    /**
     * Retorna las especialidades que existe en el sistema.
     * @return PDO  Objeto PDO con las especialidades del sistema.
     */
    function getEspecialidades(){

        # Query para obtener las especialidades
        $query = 'SELECT * FROM especialidad';
        
        #region --- Ejemplos ---
            #$query = 'SELECT * FROM especialidad WHERE id = 666';        
            #$query = 'INSERT INTO `especialidad`(`id`, `version`, `descripcion`, `nombre`) VALUES (99,1,`desc`,`name`)';
            #$query = 'UPDATE especialidad SET version=14 WHERE id = 99';
            #$query = 'DELETE FROM especialidad WHERE id = 99';
            #$query = 'SELECT count(id) as numEspecialidades FROM especialidad';

            /*
            $query = 
            'SELECT *
            FROM especialidad
            WHERE id = :id';

            $params = array(':id' => 3);

            $result = $DBH_SOS->execute($query, $params);
            /**/

            /*
            WHERE id != :id';
            # Definión de los parametros para el query
            $params = array(':id' => 99);
            /**/
        #endregion 
        
        $result = $this->execute($query);
        
        # Si el SELECT no arroja resultados retorna una respuesta generica.
        if($result->rowCount() == 0)
            API::throwPDOException(
                                    SELECT_NO_RESULT_MSG,
                                    200,
                                    $result->queryString,
                                    JR_SUCCESS,
                                    $result->rowCount()
                                );

        return $result;
    }
}
?>