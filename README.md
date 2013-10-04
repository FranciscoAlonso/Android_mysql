# Android_mysql	

## Descripción

Revisión: 04-10-2013

Web service para comunicar la aplicación con la BD de sos_triaje.

* El módulo del webservice se encuentra en el directorio */sos_triaje_web_service*

* Posee un **.htaccess** básico.

* Archivos sensibles colocados en el directorio */protegido* con su respectivo **.htaccess** con `deny from all`:
	* *db_config.php* 	(Archivo con las constantes para la conexión a la BD)
	* *db_connect.php* 	(Archivo con la clase para el manejo con la BD)
	* *sos_triaje.sql* 	(Script de la BD)

* Definir que operaciones CRUD se podrán realizar desde la app.

* Resolver por que el teléfono no puede acceder al servidor web (posiblemente es el firewall de windows).

----------

### Por hacer

(CRUD = Create Read Update Delete)

* Querys:

	* CRUD Usuarios (medicos, p.e que pueden ingresar a ver info a traves de la app y * - recordar extension)
	* CRUD segunda opinion.
	* CRUD historia medica.
	* CRUD recursos (docs,grabaciones,imagenes,etc) atado a la segunda opinion.
	* ...
  
* Ingresar data ejemplo.

*  Sanitizar y validar lo que llegue al webservice (ataques tipo SQL Injection, XSS, etc).

* Test de cada uno de los metodos CRUD.

* Capturar y manejar los metodos de conexión al fallar.
	* Ver que ocurre con la aplicación si cae en el `die()`.

* Manejar errores y mostrar mensajes apropiados en casos como:
	* Tiempo exedido.
	* Query inválido.
	* No existe el registro.
	* No se realizó la conexión.

* Ejecutar llamadas a la BD de forma asincrona (Android).

* Verificar que el **.htaccess** funcione con la configuración inicial de apache (sin necesidad de configurar modulos, por simplicidad ya que puede no funcionar por el .htaccess - ERROR 500).

* Autenticacion para el webservice (opcional/low priority/de ultimo xD).

* Establecer sesiones para que el webservice sólo puedan ser utilizados por usuarios registrados.
	* Ver como mantener la sesión a traves de la aplicación.

* Descomentar `Options -Indexes` del **.htaccess** al finalizar.

----------

F. Alonso / T. Briceño

U.C.V - 2013
