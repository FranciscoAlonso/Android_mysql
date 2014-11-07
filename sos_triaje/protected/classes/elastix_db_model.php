<?php
/**
 * Clase que se encarga de realizar la conexión con la base de datos 
 * relacionada con Elastix y ofrece funciones para obtener data básica.
 */
class elastix_db_model{

	private $DBH;

    /**
     * Realiza la conexión con la BD
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
    private function execute($query, $params = null){
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

    /**
     * PDO::beginTransaction — Initiates a transaction
     */
    public function beginTransaction(){
        $this->DBH->beginTransaction();
    }
    
    /**
     * PDO::commit — Commits a transaction
     */
    public function commit(){
        $this->DBH->commit();
    }
    
    /**
     * PDO::rollBack — Rolls back a transaction
     */
    public function rollBack(){
        $this->DBH->rollBack();
    }
    
    /**
     * Esta función retorna la lista de grupos de llamadas (Ring Groups) que existen configurados en el Elastix
     * @return PDO   Retorna información de los Ring Groups.
     */
    public function getCallGroups(){

        $params = array();

        $query = 'SELECT grpnum, description, grplist
                    FROM ringgroups';

        $result = $this->execute($query, $params);

        # Si el SELECT no arroja resultados retorna una respuesta generica.
        if($result->rowCount() == 0)
            API::throwPDOException(
                                    DB_SELECT_NO_RESULT_MSG,
                                    200,
                                    $result->queryString,
                                    JR_SUCCESS
                                );

        return $result;
    }

    /**
     * Retorna un string con los valores de LIMIT y OFFSET enviados por GET para que se concatene al final de un query y se delimite la consulta.
     * @return string String en SQL con LIMIT y OFFSET si fueron definidos, de lo contrario retorna vacío. 
     */
    public function getLimitAndOffsetString(){

        $query = "";

        # ISSET limit 
        if(isset($_GET['limit'])){
            $limit = intval($_GET['limit']);

            if($limit > 0){
                $query .= ' LIMIT ' . $limit;

                # ISSET offset ?
                if(isset($_GET['offset'])){
                    $offset = intval($_GET['offset']);
                    if ($offset > 0)
                        $query .= ' OFFSET ' . $offset;
                }
            }
        }

        return $query;
    }

    #region READ
        /**
         * Retorna la última grabación realizada por el usuario.
         * @param  string $user_extension Extension del usuario a consultar su ultima grabación.
         * @return PDO                 Informacion acerca de la grabación, si existe.
         */
        public function getLastRecordedCall($user_extension){

            $params = array(':user_extension' => $user_extension);

            $query = 'SELECT 
                        calldate
                        , src
                        , dst
                        , duration
                        , disposition
                        , uniqueid
                        , userfield 

                        FROM cdr
                            WHERE src = :user_extension
                                AND disposition = "ANSWERED"
                                AND userfield != ""

                            ORDER BY calldate desc LIMIT 1;';

            $result = $this->execute($query, $params);

            return $result;
        }
    #endregion
}
?>