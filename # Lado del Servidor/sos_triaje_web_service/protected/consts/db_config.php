<?php

// INVESTIGAR: E_WARNING y display_errors

# Definición de constantes para su uso en conexiones a BD.

define('SOS_DB_SERVER', 'localhost');	# Servidor de la BD de SOS Triaje
define('SOS_DB_NAME','sos_triaje');		# Nombre de la BD a conectar
define('SOS_DB_USER','root');			# Usuario de la BD
define('SOS_DB_PASSWORD',''); 			# Contraseña de la BD

# Recuerde cambiar la configuración en el servidor Elastix
# para que permita la conexión desde otro servidor (ACL)  
define('ELASTIX_DB_SERVER', '');	# Servidor de la BD de Elastix
define('ELASTIX_DB_NAME','asteriskcdrdb'); 		# Nombre de la BD a conectar
define('ELASTIX_DB_USER','root'); 				# Usuario de la BD
define('ELASTIX_DB_PASSWORD','Tajrh123654'); 	# Contraseña de la BD

# Definición global para los tipos de mensajes de error:
  # PDO::ERRMODE_SILENT (Deafult): The other two methods are more ideal for DRY programming. If you leave it in this mode, you'll have to check for errors in the way you're probably used to if you used the mysql or mysqli extensions.
  # PDO::ERRMODE_WARNING: It's useful for debugging. This mode will issue a standard PHP warning, and allow the program to continue execution.
  # PDO::ERRMODE_EXCEPTION: This is the mode you should want in most situations. It fires an exception, allowing you to handle errors gracefully and hide data that might help someone exploit your system.
define('GLOBAL_PDO_ERROR_MODE', PDO::ERRMODE_WARNING );

# Definición global de la forma como se obtiene la data: 
  # PDO::FETCH_ASSOC: returns an array indexed by column name
  # PDO::FETCH_BOTH (Default): returns an array indexed by both column name and number
  # PDO::FETCH_BOUND: Assigns the values of your columns to the variables set with the ->bindColumn() method
  # PDO::FETCH_CLASS: Assigns the values of your columns to properties of the named class. It will create the properties if matching properties do not exist
  # PDO::FETCH_INTO: Updates an existing instance of the named class
  # PDO::FETCH_LAZY: Combines PDO::FETCH_BOTH/PDO::FETCH_OBJ, creating the object variable names as they are used
  # PDO::FETCH_NUM: returns an array indexed by column number
  # PDO::FETCH_OBJ: returns an anonymous object with property names that correspond to the column names
/**/
//define('GLOBAL_PDO_FETCH_MODE', PDO::FETCH_OBJ); 
define('GLOBAL_PDO_FETCH_MODE', PDO::FETCH_ASSOC); 

/**/
# Lista los drivers de BD para PDO que soporta el servidor 
# (Que están actualmente habilitados). 
echo 'PDO Drivers Enabled: <br>';
foreach (PDO::getAvailableDrivers() as $PDO_Driver){ 
	echo '-' . $PDO_Driver . '<br>';
	}
/**/

?>