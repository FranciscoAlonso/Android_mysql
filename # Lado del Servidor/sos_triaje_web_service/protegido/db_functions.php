
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
     * TEST
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

    /**
     * 
     */
/*
public function getUserByEmailAndPassword($email, $password) {
        $result = mysql_query("SELECT * FROM users WHERE email = '$email'") or die(mysql_error());
        // check for result 
        $no_of_rows = mysql_num_rows($result);
        if ($no_of_rows > 0) {
            $result = mysql_fetch_array($result);
            $salt = $result['salt'];
            $encrypted_password = $result['encrypted_password'];
            $hash = $this->checkhashSSHA($salt, $password);
            // check for password equality
            if ($encrypted_password == $hash) {
                // user authentication details are correct
                return $result;
            }
        } else {
            // user not found
            return false;
        }
    }
 /**/

}
?>