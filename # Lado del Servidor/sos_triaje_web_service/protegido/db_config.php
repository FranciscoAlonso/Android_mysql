<?php
 
/*
 * Constantes con las credenciales de la BD a conectar.
 */
/* 
define('DB_SERVER', "localhost"); 		# Servidor de la BD
define('DB_DATABASE', "sos_triaje");	# Nombre de la BD
define('DB_USER', "root"); 				# Usuario de la BD
define('DB_PASSWORD', ""); 				# Contraseña de la BD
/**/

define('DB_SERVER', "192.168.2.13"); 	# Servidor de la BD
define('DB_DATABASE', "asteriskcdrdb");	# Nombre de la BD
define('DB_USER', "root"); 				# Usuario de la BD
define('DB_PASSWORD', "Tajrh123654"); 	# Contraseña de la BD
/**/

/*
# Control de acceso - https://dev.mysql.com/doc/refman/5.0/es/connection-access.html #
# Conectarse a MySQL de Elastix desde otra red - desde WAMP para consultar y crear los webservices #
Fuente: http://www.phpro.org/articles/Database-Connection-Failed-Mysqlnd-Cannot-Connect-To-MySQL.html

# Revisar la longitud del hash de los usuarios
SELECT user, Length(Password) FROM mysql.user;

# Quitar la bandera de contraseñas viejas para que no las siga usando
SET SESSION old_passwords=0;

# Actualizar la contraseña
UPDATE mysql.user SET Password = PASSWORD('Tajrh123654') WHERE user = 'root';

# "... decir al servidor que vuelva a leer las tablas de permisos. De otro modo, los cambios no se tienen en cuenta hasta que se reinicie el servidor."
# fuente: https://dev.mysql.com/doc/refman/5.0/es/adding-users.html
FLUSH PRIVILEGES;

# "... cuentas de superusuario con plenos permisos para hacer cualquier cosa."
# "... Una cuenta ('usuario'@'%') puede usarse para conectarse desde cualquier otro equipo."
# fuente: https://dev.mysql.com/doc/refman/5.0/es/adding-users.html
GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' WITH GRANT OPTION;

# Listar los permisos
SHOW GRANTS;

/**/
?>