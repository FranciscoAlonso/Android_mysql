# Android_mysql	

## Descripción

Última revisión: 02-02-2014

Web service para comunicar la aplicación de Android con la BD de sos_triaje.

* El módulo del webservice se encuentra en el directorio */sos_triaje_web_service*

* Posee un **.htaccess** elaborado (De desarrollo, posee comentarios).

* Archivos sensibles colocados en el directorio */protegido* con su respectivo **.htaccess** con `deny from all`:
	* *db_config.php* 	(Archivo con las constantes para la conexión a la BD)
	* *db_connect.php* 	(Archivo con la clase para el manejo con la BD)
	* *sos_triaje.sql* 	(Script de la BD)

----------

### Por hacer

(CRUD = Create Read Update Delete)

* REALIZAR TODO RELACIONADO CON BD con ~~**MYSQLI**~~ **PDO** (A futuro muchos o todos los metodos `mysql` de php serán obsoletos, a partir de PHP 5.5.0).
	* Crear nuevas clases en php para la conección haciendo uso de ~~mysqli~~ PDO.
	
* Definir charset para la BD *http://www.php.net/manual/en/mysqli.set-charset.php*
	
~~* Definir que operaciones CRUD se podrán realizar desde la app.~~

* Querys:
	* Listar historial_caso de un médico (según estado del caso).
	* Mostrar información del caso.
	* Mostrar opiniones asociadas a un caso.
	* Crear caso.
	* Listar médicos (todos o se puede filtrar por especialidad).

* ~~Resolver por que el teléfono no puede acceder al servidor web (posiblemente es el firewall de windows).~~
	* **Soluciones**:
	* Comentar "**Deny from all**" en el archivo de conf httpd.conf dentro del Tag *<Directory "c:/wamp/www">*
	* Si no resulta aún, agregar excepción en el firewall.
	* Si no resulta aún, agregar excepción del puerto en el firewall (puerto 80 a 8080 y 443 a 4430).

* ~~Sanitizar y validar lo que llegue al webservice (ataques tipo SQL Injection, XSS, etc).~~
	* ~~Leer: *http://stackoverflow.com/questions/60174/how-can-i-prevent-sql-injection-in-php*~~
	* ~~usar *http://www.php.net/manual/en/mysqli.real-escape-string.php*~~
	* ~~uso de `sprintf`, `is_numeric()`, `ctype_digit()`... etc. *http://php.net/manual/en/security.database.sql-injection.php*~~
	* ~~*http://www.php.net/manual/en/pdo.quote.php*~~
	
	* **Solución**: Utilizando `prepare` de `mysqli` o de `PDO` evita los ataques. 

* Ingresar data ejemplo.

* Test de cada uno de los metodos CRUD.

* Capturar y manejar los metodos de conexión al fallar.
	* Ver que ocurre con la aplicación si cae en el `die()`.

* Manejar errores y mostrar mensajes apropiados en casos como:
	* Tiempo exedido.
	* Query inválido.
	* No existe el registro.
	* No se realizó la conexión.

* Ejecutar consultas a la BD de forma asincrona (Android), *Link: http://www.androidhive.info/2012/05/how-to-connect-android-with-php-mysql/*
	
* ~~Verificar que el **.htaccess** funcione con la configuración inicial de apache (sin necesidad de configurar modulos, por simplicidad ya que puede no funcionar por el **.htaccess** - **ERROR 500**).~~

	* **Solución fácil**: Quitar el **.htaccess** =)

	* **WAMP**:
		* Copiar el contenido de "**htaccess_template_WAMP.txt**" a **.htaccess**.
		* Se encuentran comentados en el **.htaccess** tres secciones:
		* 1) "Acceder a las rutas sin colocar al final la extensión .php"
		* 2) "Prevent hacks by detecting malicious URL patterns"
		* 3) "Explicitly disable caching for scripts and other dynamic files"
		* Si se desea utilizar algunas de estas características, se realizará una documentación donde se explicará los pasos a seguir (p.e.: que modulos hay que activar, etc.)
	
	* **XAMPP**:
		* Copiar el contenido de "**htaccess_template_XAMPP.txt**" a **.htaccess**.

* Autenticacion para el webservice (opcional/low priority/de ultimo xD). *Link: http://www.androidhive.info/2012/01/android-login-and-registration-with-php-mysql-and-sqlite/*

* Establecer sesiones para que el webservice sólo puedan ser utilizados por usuarios registrados.
	* Ver como mantener la sesión a traves de la aplicación.

* Descomentar `Options -Indexes` del **.htaccess** al finalizar.

----------

F. Alonso / T. Briceño

U.C.V - 2013
