<?php
/**
 * Clase estática encargada del CRUD relacionado con la tabla "opinion".
 */
class opiniones{

	private function __construct(){}
  	private function __clone(){}

  	/**
  	 * Invoca al modelo para crear una opinión a un caso.
  	 * @param  array $form Arreglo con los valores de la nueva opinión.
  	 * @return JSON 		JSON indicando si la inserción de la opinión fue un éxito o no. 
  	 * @throws Exception If Ocurre alguna excepción en el proceso de la creación de la data.
  	 */
  	public static function create($form, $user_extension  = ""){
  		try {
			# Invocar a la clase sos_db_model.
			$DBH_SOS = new sos_db_model();

			#region Agrega a la opinion el id de la ultima grabación (solamente si es único)
				try {
					# Invocar a la clase elastix_db_model.
					$DBH_ELASTIX = new elastix_db_model("asteriskcdrdb");
								
					$cdr_record = $DBH_ELASTIX->getLastRecordedCall($user_extension);
					$cdr_record = $cdr_record->fetch();
		      		
					if ( $DBH_SOS->exist_cdr_uniqueid($cdr_record['uniqueid']) ){
						$form[':cdr_uniqueid'] = null;
						$form[':calldate'] = null;
						$form[':recordingfile'] = null;
					}else{
						$form[':cdr_uniqueid'] = $cdr_record['uniqueid'];
						$form[':calldate'] = $cdr_record['calldate'];
						$form[':recordingfile'] = $cdr_record['recordingfile'];
					}
				} catch (Exception $e) {
					# Ha ocurrido un error al intentar conectar con el servidor VoiP, se captura y continua la creación de la opinión.
					$form[':cdr_uniqueid'] = null;
					$form[':calldate'] = null;
					$form[':recordingfile'] = null;

					# Se reestablece el codigo HTTP a 200
					$app = \Slim\Slim::getInstance();
					$app->status(200);  
				}
			#endregion

			$result = $DBH_SOS->createOpinion($form);

			# Crear metadata para la consulta exitosa.
			$metadata = 
				new json_response_metadata(
						JR_SUCCESS,
						$result->rowCount(),
						$result->queryString,
						$_SERVER['REQUEST_METHOD']
					);

			# Retorna el resultado de la consulta con información extra en formato JSON.
			return json_response::generate($metadata, DB_INSERT_SUCESS_MSG, $DBH_SOS->getLastInsertId());
		} catch (Exception $e) {
            return $e->getMessage();
		}
  	}

	/**
	 * Invoca al modelo para  obtener las opiniones.
	 * @param string $caso_id Id del caso.
	 * @param string $opinion_id	ID de la opinion a consultar, o vacio si se desean obtener todas las opiniones relacionandas a $caso_id.
	 * @return JSON 		JSON que contiene las opiniones. 	
	 * @throws Exception If Ocurre alguna excepción en el proceso de la obtención de la data.
	 */
	public static function read($caso_id = "", $opinion_id = ""){
		try {
			# Invocar a la clase sos_db_model.
			$DBH_SOS = new sos_db_model();

			$result = $DBH_SOS->getOpiniones($caso_id, $opinion_id);

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
		} catch (Exception $e) {
            return $e->getMessage();
		}
	}

	/**
  	 * Invoca al modelo para modificar una opinión a un caso.
  	 * @param  array $form Arreglo con los nuevos valores de la opinión.
  	 * @return JSON 		JSON indicando si la actualización de la opinión fue un éxito o no. 
  	 * @throws Exception If Ocurre alguna excepción en el proceso de la modificación de la data.
  	 */
	public static function update($form){
		try {
			# Invocar a la clase sos_db_model.
			$DBH_SOS = new sos_db_model();

			$result = $DBH_SOS->updateOpinion($form);
		
			# Crear metadata para la consulta exitosa.
			$metadata = 
				new json_response_metadata(
						JR_SUCCESS,
						$result->rowCount(),
						$result->queryString,
						$_SERVER['REQUEST_METHOD']
					);

			# Retorna el resultado de la consulta con información extra en formato JSON.
			return json_response::generate($metadata, $result->rowCount() . DB_UPDATE_SUCESS_MSG);
		} catch (Exception $e) {
            return $e->getMessage();
		}
	}

	/**
	 * Invoca al modelo para eliminar una opinión asociada a un caso.
	 * @param string $caso_id 		ID del caso.
	 * @param string $opinion_id	ID de la opinion a eliminar.
	 * @return JSON 				JSON indicando si la eliminación de la opinión fue un éxito o no. 
	 * @throws Exception If Ocurre alguna excepción en el proceso de la eliminación de la data.
	 */
	public static function delete($caso_id = "", $opinion_id = ""){
		try {
			# Invocar a la clase sos_db_model.
			$DBH_SOS = new sos_db_model();

			$result = $DBH_SOS->deleteOpinion($caso_id, $opinion_id);

			# Crear metadata para la consulta exitosa.
			$metadata = 
				new json_response_metadata(
						JR_SUCCESS,
						$result->rowCount(),
						$result->queryString,
						$_SERVER['REQUEST_METHOD']
					);

			# Retorna el resultado de la consulta con información extra en formato JSON.
			return json_response::generate($metadata, $result->rowCount() . DB_DELETE_SUCESS_MSG);
		} catch (Exception $e) {
            return $e->getMessage();
		}
	}
}
?>
