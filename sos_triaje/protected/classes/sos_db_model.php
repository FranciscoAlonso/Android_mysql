<?php
/**
 * Clase que se encarga de realizar la conexión con la base de datos 
 * "sos_triaje" y ofrece funciones para obtener data básica.
 */
class sos_db_model{

	private $DBH;

    /**
     * Realiza la conexión con la BD 'sos_triaje'
     * @throws PDOException If Ocurre algún error al momento que establecer la conexión con la BD.
     */
	public function __construct(){

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
    public function __destruct() {
        $this->DBH = null;
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

    /**
     * Verifica que el usuario este registrado.
     * @param  string $user     Correo o login del usuario. 
     * @param  string $password Contraseña del usuario.
     * @return bool             Retorna TRUE si es un usuario registrado y FALSE en caso contrario.
     */
    public function checkLogin($user, $password){

        $params = array(':user' => $user);

        $query = 'SELECT password as DB_PASSWORD 
                    FROM actor_sistema 
                        WHERE mail = :user OR login = :user';

        $result = $this->execute($query, $params);

        if ($result->rowCount() > 0) {

            $result = $result->fetch();

            if ($password == $result['DB_PASSWORD']) # OR $password con hash == $result['DB_PASSWORD']
                return true;        
        }

        return false;
    }

    /**
     * Generating random Unique MD5 String for user Api key.
     * @return string API Key.
     */
    private function generateApiKey(){
        return md5(uniqid(rand(), true));
    }

    /**
     * Verifica si el api_key es null, si es así asigna un nuevo valor.
     * @param  string $user Correo o login del usuario.
     */
    private function checkUserApiKey($user){

        $params = array(':user' => $user);

        $query = 'SELECT api_key 
                    FROM actor_sistema 
                        WHERE mail = :user OR login = :user';

        $result = $this->execute($query, $params);
        $result = $result->fetch();

        if (is_null($result['api_key'])) {

            $params[':api_key'] = $this->generateApiKey();

            $query = 'UPDATE actor_sistema 
                        SET api_key = :api_key 
                            WHERE mail = :user OR login = :user';

            $this->execute($query, $params);
        }
    }

    /**
     * Validating user api key
     * If the api key is there in db, it is a valid key
     * @param String $api_key user api key.
     * @return boolean  Retorna true si es un API KEY valido, de lo contrario retorna False.
     */
    public function isValidApiKey($api_key) {

        $params = array(':api_key' => $api_key);

        $query = 'SELECT id 
                    FROM actor_sistema 
                        WHERE api_key = :api_key';

        $result = $this->execute($query, $params);

        return $result->rowCount() > 0;
    }

    #region CREATE
        # otros
    #endregion

    #region READ
        /**
         * Fetching user id by api key
         * @param String $api_key user api key.
         * @return PDO  ID del usuario al cual le corresponde el API KEY recibido por parametro.
         */
        public function getUserIdByApiKey($api_key) {

            $params = array(':api_key' => $api_key);

            $query = 'SELECT id as DB_ID
                        from actor_sistema 
                            WHERE api_key = :api_key';

            $result = $this->execute($query, $params);

            $result = $result->fetch();

            return $result['DB_ID'];
        }

        /**
         * Obtener datos de un usuario.
         * @param  string $user     correo o login de un usuario.
         * @return PDO  Objeto PDO con el login, mail, rol, api_key y user_extension de un usuario.
         */
        public function getUser($user){

            $params = array(':user' => $user);

            $this->checkUserApiKey($user);

            $query = 'SELECT login, mail, rol, api_key, user_extension 
                        FROM actor_sistema 
                            WHERE mail = :user OR login = :user';

            $result = $this->execute($query, $params);

            return $result;
        }

        /**
         * Retorna las especialidades que existe en el sistema.
         * @return PDO  Objeto PDO con las especialidades del sistema.
         * @throws PDOException If La consulta arroja 0 resultados.
         */
        public function getEspecialidades(){

            $query = 'SELECT *
                        FROM especialidad';

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
                                        DB_SELECT_NO_RESULT_MSG,
                                        200,
                                        $result->queryString,
                                        JR_SUCCESS,
                                        $result->rowCount()
                                    );

            return $result;
        }

        /**
         * Retorna el conjunto de centros SOS que existen en el sistema.
         * @param  string $centro_id si esta definido un centro_id retornara la informacion de ese centro unicamente, de lo contrario listara todos los centros (Default="")
         * @return PDO            Objeto PDO con la lista de centros sos del sistema.
         */
        public function getCentroSos($centro_id = ""){

            $params = array(':centro_id' => $centro_id);

            $query = 'SELECT *
                        FROM centrosos';

            if(!empty($centro_id))
                $query .= ' WHERE centrosos.id = :centro_id';

            $result = $this->execute($query, $params);

            # Si el SELECT no arroja resultados retorna una respuesta generica.
            if($result->rowCount() == 0)
                API::throwPDOException(
                                        DB_SELECT_NO_RESULT_MSG,
                                        200,
                                        $result->queryString,
                                        JR_SUCCESS,
                                        $result->rowCount()
                                    );

            return $result;
        }

        /**
         * Retorna las especialidades que existe en el sistema.
         * @param  string $caso_id ID de un caso (Default="")
         * @return PDO  Objeto PDO con uno o más casos.
         * @throws PDOException If La consulta arroja 0 resultados.
         */
        public function getCasos($caso_id = ""){

            $params = array();

            $query = 'SELECT 
                        c.id, c.descripcion, c.fecha_inicio, c.fecha_solucion,
                        s.nombre AS status_caso,
                        centro.nombre AS centro_sos,
                        p.fecha_nacimiento,
                        e.nombre AS tipo_especialidad,
                        COUNT(DISTINCT a.id) AS cant_archivos,
                        COUNT(DISTINCT o.id) AS cant_opiniones

                        FROM caso c
                        LEFT JOIN archivo a ON c.id = a.caso_id
                        LEFT JOIN opinion o ON c.id = o.caso_id
                        LEFT JOIN status s ON c.status_id = s.id
                        LEFT JOIN paciente p ON c.paciente_id = p.id
                        LEFT JOIN centrosos centro ON c.centro_id = centro.id
                        LEFT JOIN caso_especialidad c_e ON c.id = c_e.caso_especialidades_id
                        LEFT JOIN especialidad e ON c_e.especialidad_id = e.id';

            if(!empty($caso_id)){
                # Si es un caso especifico no se aplican los filtros
                $params[':casoId'] = $caso_id;
                $query .= ' WHERE c.id = :casoId';
            }else{
                # Condiciones para el filtro
                if ( isset($_GET['own']) || isset($_GET['especialidad']) || isset($_GET['status']) || isset($_GET['centro'])) {

                    if (!empty($_GET['own']) && $_GET['own'] == true){
                        $query .= ' INNER JOIN historial_caso h_c ON c.id = h_c.caso_id
                                    INNER JOIN medico m ON h_c.medico_id = m.id';
                    }

                    $query .= ' WHERE 1 = 1';

                    if (!empty($_GET['own']) && $_GET['own'] == true){
                        global $user_id;
                        $params[':own'] = $user_id;
                        $query .= ' AND m.id = :own'; 
                    }

                    if (!empty($_GET['especialidad'])){
                        $params[':especialidad'] = $_GET['especialidad'];
                        $query .= ' AND e.id = :especialidad';
                    }

                    if (!empty($_GET['status'])){   
                        $params[':status'] = $_GET['status'];
                        $query .= ' AND s.id = :status';
                    }

                    if (!empty($_GET['centro'])){   
                        $params[':centro'] = $_GET['centro'];
                        $query .= ' AND centro.id = :centro'; 
                    }
                }
            }

            $query .= ' GROUP BY c.id';

            $result = $this->execute($query, $params);

            # Si el SELECT no arroja resultados retorna una respuesta generica.
            if($result->rowCount() == 0)
                API::throwPDOException(
                                        DB_SELECT_NO_RESULT_MSG,
                                        200,
                                        $result->queryString,
                                        JR_SUCCESS,
                                        $result->rowCount()
                                    );

            return $result;
        }

        /**
         * Retorna el historial de un caso.
         * @param  string $caso_id ID de un caso (Default="")
         * @return PDO  Objeto PDO con las especialidades del sistema.
         * @throws PDOException If La consulta arroja 0 resultados o si $caso_id esta vacío.
         */
        public function getHistorialCaso($caso_id = ""){

            if (empty($caso_id))
                API::throwPDOException("Falta el ID del caso para retornar el historial.");

            $params = array(':caso_id' => $caso_id);

            $query = 'SELECT *
                        FROM historial_caso h_c
                            WHERE h_c.caso_id = :caso_id
                                ORDER BY h_c.fecha DESC';

            $result = $this->execute($query, $params);

            # Si el SELECT no arroja resultados retorna una respuesta generica.
            if($result->rowCount() == 0)
                API::throwPDOException(
                                        DB_SELECT_NO_RESULT_MSG,
                                        200,
                                        $result->queryString,
                                        JR_SUCCESS,
                                        $result->rowCount()
                                    );

            return $result;
        }

        /**
         * Retorna un archivo en formato blob.
         * @param  string $caso_id ID de un caso (Default="")
         * @param  string $archivo_id Id del archivo (Default="")
         * @return blob  archivo en formato blob.
         * @throws PDOException If La consulta arroja 0 resultados o si $archivo_id esta vacío.
         */
        public function getArchivo($caso_id = "", $archivo_id = ""){

            if (empty($caso_id))
                API::throwPDOException("Falta el ID del caso para obtener los archivos.");

            $params = array(':caso_id' => $caso_id);

            $query = 'SELECT a.*
                        FROM caso c
                            INNER JOIN archivo a ON c.id = a.caso_id
                                WHERE c.id = :caso_id';

            if(!empty($archivo_id)){
                $params[':archivo_id'] = $archivo_id;
                $query .= ' AND a.id = :archivo_id';
            }

            $result = $this->execute($query, $params);

            # Si el SELECT no arroja resultados retorna una respuesta generica.
            if($result->rowCount() == 0)
                API::throwPDOException(
                                        DB_SELECT_NO_RESULT_MSG,
                                        200,
                                        $result->queryString,
                                        JR_SUCCESS,
                                        $result->rowCount()
                                    );

            return $result;
        }

        /**
         * Retorna las opiniones de un caso.
         * @param  string $caso_id ID de un caso (Default="")
         * @param  string $opinion_id Id de la opinion (Default="")
         * @return [type]             [description]
         * @throws PDOException If La consulta arroja 0 resultados o si $opinion_id esta vacío.
         */
        public function getOpiniones($caso_id = "", $opinion_id = ""){

            if (empty($caso_id))
                API::throwPDOException("Falta el ID del caso para obtener las opiniones.");

            $params = array(':caso_id' => $caso_id);

            $query = 'SELECT o.*
                        FROM caso c
                            INNER JOIN opinion o ON c.id = o.caso_id
                                WHERE c.id = :caso_id';

            if(!empty($opinion_id)){
                $params[':opinion_id'] = $opinion_id;
                $query .= ' AND o.id = :opinion_id';
            }

            $result = $this->execute($query, $params);

            # Si el SELECT no arroja resultados retorna una respuesta generica.
            if($result->rowCount() == 0)
                API::throwPDOException(
                                        DB_SELECT_NO_RESULT_MSG,
                                        200,
                                        $result->queryString,
                                        JR_SUCCESS,
                                        $result->rowCount()
                                    );

            return $result;
        }
    #endregion
}
?>