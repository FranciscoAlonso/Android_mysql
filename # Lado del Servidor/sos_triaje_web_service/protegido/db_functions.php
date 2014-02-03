
<?php

/**
 * Clase para proveer funcionalidades que usará la aplicación.
 */
class DB_FUNCTIONS {

    private $db;
    
    # Constructor
    function __construct() {
        require_once __DIR__ . '\db_config.php';

        try {
            # *** Conexión fallida ***
            #$this->db = new PDO("mysql:host=localhost;dbname=database", DB_USER , DB_PASSWORD );

            # Modo Silencioso:
            //$this->db = new PDO("mysql:host=" . DB_SERVER .";dbname=" . DB_DATABASE , DB_USER , DB_PASSWORD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT));

            # Modo de Advertencia:
            $this->db = new PDO("mysql:host=" . DB_SERVER .";dbname=" . DB_DATABASE , DB_USER , DB_PASSWORD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
            
            # Modo de Excepción:
            //$this->db = new PDO("mysql:host=" . DB_SERVER .";dbname=" . DB_DATABASE , DB_USER , DB_PASSWORD, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

        } catch ( PDOException $e ) {
            # echo 'Conexi&oacute;n Fallida: <br>' . $e->getMessage();
            exit('Conexi&oacute;n Fallida: <br>' . $e->getMessage());
        }

        #echo '<br><br>se estableci&oacute; la conexi&oacute;n desde function' . '<br>';
        /**/
    }
    
    # Destructor
    function __destruct() {
        $this->db = NULL;
        #echo '<br><br>DESTRUCTOR DESDE FUNCTION' . '<br>';
    }

    /**
     * Get user by email and password
     */
    public function get_all_actor_sistema() {


        // PDO, prepared statement
        $params = array(':id' => 2);

        #$stmt = $this->db->prepare('SELECT * FROM actor_sistema WHERE id = :id');
        
        $query = 
        'SELECT *
        FROM actor_sistema
        WHERE id = :id';

        $query = 
        'SELECT *
        FROM actor_sistema';

        $stmt = $this->db->prepare($query);

        $stmt->execute($params);

        /*
        while( $row = $stmt->fetch(PDO::FETCH_ASSOC) ){
            echo $row['mail'] . '<br>';
        }
        /**/

        $array = $stmt->fetchAll( PDO::FETCH_ASSOC );
        $json = json_encode( $array );
        echo $json;
        /**/

    }

/*
    public function get_historial_caso(){}

    public function get_historial_caso_by_state(){}

    public function get_historial_caso_by_state(){}
/**/

}
?>