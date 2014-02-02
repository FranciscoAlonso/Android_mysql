
<?php

/**
 * WEB SERVICE PARA LA BD SOS_TRIAJE
 */

    # Muestra que drivers de BD soporta (Se agregan en las extensiones de php)
    //var_dump(PDO::getAvailableDrivers()); 

    # Se establece la conexión a la BD y se incorpora las funcionalidades que usará la aplicación
    require_once __DIR__ . '\protegido\db_functions.php';
    $var = new DB_FUNCTIONS();

    $var->get_all_actor_sistema();

?>