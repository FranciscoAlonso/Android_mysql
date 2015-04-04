<?php
/**
 * Este archivo contiene las definición de constantes para 
 * su uso en conexiones a BD y al Asterisk Manager Interface.
 */

define('SOS_DB_SERVER'  , 'localhost'  ); # Servidor de la BD de SOS Triaje.
define('SOS_DB_NAME'    , 'sos_triaje' );	# Nombre de la BD a conectar.
define('SOS_DB_USER'    , 'root'       );	# Usuario de la BD.
define('SOS_DB_PASSWORD', ''           ); # Contraseña de la BD.

# Recuerde cambiar la configuración en el servidor Elastix
# para que permita la conexión desde otro servidor (ACL). 
define('ELASTIX_DB_SERVER'  , '190.201.142.217' );  # Servidor de la BD de Elastix.
define('ELASTIX_DB_NAME'    , 'asterisk'        );  # Nombre de la BD por defecto a conectar.
define('ELASTIX_DB_USER'    , 'root'            );  # Usuario de la BD.
define('ELASTIX_DB_PASSWORD', '123456'          );  # Contraseña de la BD.

# Constantes para acceder al Asterisk Manager Interface (AMI)
define('ELASTIX_AMI_HOST'     , ELASTIX_DB_SERVER   ); # AMI Host.
define('ELASTIX_AMI_USER'     , 'admin'             ); # AMI User.
define('ELASTIX_AMI_PASSWORD' , ELASTIX_DB_PASSWORD ); # AMI Password.
define('ELASTIX_AMI_PORT'     , '5038'              ); # Port (Default: 5038).
define('ELASTIX_AMI_TIMEOUT'  , 30                  ); # Connection timeout. 
define('ELASTIX_AMI_PEER_TYPE', 'sip'               ); # The type of peer (i.e. iax2 or sip).

# Definición global para los tipos de mensajes de error:
  # PDO::ERRMODE_SILENT (Deafult): The other two methods are more ideal for DRY programming. If you leave it in this mode, you'll have to check for errors in the way you're probably used to if you used the mysql or mysqli extensions.
  # PDO::ERRMODE_WARNING: It's useful for debugging. This mode will issue a standard PHP warning, and allow the program to continue execution.
  # PDO::ERRMODE_EXCEPTION: This is the mode you should want in most situations. It fires an exception, allowing you to handle errors gracefully and hide data that might help someone exploit your system.
define('GLOBAL_PDO_ERROR_MODE', PDO::ERRMODE_EXCEPTION);

# Definición global de la forma como se obtiene la data: 
  # PDO::FETCH_ASSOC: returns an array indexed by column name.
  # PDO::FETCH_BOTH (Default): returns an array indexed by both column name and number.
  # PDO::FETCH_BOUND: Assigns the values of your columns to the variables set with the ->bindColumn() method.
  # PDO::FETCH_CLASS: Assigns the values of your columns to properties of the named class. It will create the properties if matching properties do not exist.
  # PDO::FETCH_INTO: Updates an existing instance of the named class.
  # PDO::FETCH_LAZY: Combines PDO::FETCH_BOTH/PDO::FETCH_OBJ, creating the object variable names as they are used.
  # PDO::FETCH_NUM: returns an array indexed by column number.
  # PDO::FETCH_OBJ: returns an anonymous object with property names that correspond to the column names.
  //define('GLOBAL_PDO_FETCH_MODE', PDO::FETCH_OBJ); 
define('GLOBAL_PDO_FETCH_MODE', PDO::FETCH_ASSOC);

# Definición de los mensajes de feedback para el usuario.
define('DB_INSERT_SUCESS_MSG', 'Se ha insertado el registro con éxito.');
define('DB_UPDATE_SUCESS_MSG', ' registro(s) se ha(n) actualizado con éxito.');
define('DB_DELETE_SUCESS_MSG', ' registro(s) se ha(n) eliminado con éxito.');

define('DB_SELECT_NO_RESULT_MSG', 'La operación no produjo resultados.');
define('DB_INSERT_NO_RESULT_MSG', 'Ocurrio un error al intentar crear.');
define('DB_UPDATE_NO_RESULT_MSG', 'Ocurrio un error al intentar actualizar.');
define('DB_DELETE_NO_RESULT_MSG', 'Ocurrio un error al intentar eliminar.');

/*
# Lista los drivers de BD para PDO que soporta 
# el servidor que están actualmente habilitados. 
echo 'PDO Drivers Enabled: <br>';
foreach (PDO::getAvailableDrivers() as $PDO_Driver){ 
	echo '-' . $PDO_Driver . '<br>';
	}
/**/
?>