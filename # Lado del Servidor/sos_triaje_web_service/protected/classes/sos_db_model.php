<?php
/*
* 
*/
class sos_db_model{


	function __construct(){

		require_once '/../consts/db_config.php';

	}

	function __destruct() {
        $this->db = NULL;
        echo '<br><br>DESTRUCTOR DESDE FUNCTION' . '<br>';
    }

}
?>