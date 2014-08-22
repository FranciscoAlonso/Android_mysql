<?php
/**
 * Clase estática encargada del CRUD relacionado con la tabla "archivos".
 */
class archivos{

	private function __construct(){}
  	private function __clone(){}

	/**
	 * Invoca al modelo para obtener los archivos de un caso.
	 * @return JSON 		JSON que contiene todos los archivos de un caso.
	 * @return blob 		Blob que contiene el archivo especificado. 	
	 * @throws Exception If Ocurre alguna excepción en el proceso de la obtención de la data.
	 */
	public static function read($caso_id = "", $archivo_id = ""){
		try {
			# Invocar a la clase sos_db_model.
			$DBH_SOS = new sos_db_model();

			if (empty($archivo_id)) {
				# Mostrar todos los archivos del caso
				$result = $DBH_SOS->getArchivo($caso_id);

				# Crear metadata para la consulta exitosa.
				$metadata = 
					new json_response_metadata(
							JR_SUCCESS,
							$result->rowCount(),
							$result->queryString,
							$_SERVER['REQUEST_METHOD']
						);

				# Retorna el resultado de la consulta con información extra en formato JSON.
				return json_response::generate($metadata, $result);
			}else{
				# Obtener un archivo en especifico

				$result = $DBH_SOS->getArchivo($caso_id, $archivo_id);
				
				$result = $result->fetch();

				# Obtener el tipo de archivo blob. 
				//$contentType = $DBH_SOS->getArchivoContentType($archivo_id);
				//if(is_null($contentType))
					$contentType = JPG_CONTENT_TYPE; # JPG por defecto.

		        $app = \Slim\Slim::getInstance();
		        $app->contentType($contentType);

        		return $result['adjunto'];
			}

		} catch (Exception $e) {
            return $e->getMessage();
		}
	}
}
?>