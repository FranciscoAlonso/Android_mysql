<?php
/**
 * Clase estática encargada del CRUD relacionado con la tabla "caso".
 */
class casos{

	private function __construct(){}
  	private function __clone(){}

  	/**
  	 * Invoca al modelo para crear un caso.
  	 * @param  array $form Arreglo con los valores del nuevo caso.
  	 * @param string $especialidad_id ID de la especialidad (Default="").
  	 * @return JSON 		JSON indicando si la inserción del caso fue un éxito o no. 
  	 * @throws Exception If Ocurre alguna excepción en el proceso de la creación de la data.
  	 */
  	public static function create($form, $especialidad_id = "", $fecha_nacimiento = "0000-00-00 00:00:00"){
  		try {
			# Invocar a la clase sos_db_model.
			$DBH_SOS = new sos_db_model();

			# Se inicia la transaction
			$DBH_SOS->beginTransaction();	

			#region Crea el paciente
				$form_paciente = array();
				$form_paciente[':fecha_nacimiento'] = $fecha_nacimiento;
				$paciente = $DBH_SOS->createPaciente($form_paciente);
				$form[':paciente_id'] = $DBH_SOS->getLastInsertId();
			#endregion

			# Crea el caso
			$result = $DBH_SOS->createCaso($form);

			$inserted_caso_id = $DBH_SOS->getLastInsertId();

			if(!empty($especialidad_id))
				$DBH_SOS->setCasoEspecialidad($inserted_caso_id, $especialidad_id);	

			# Commit transaction
			$DBH_SOS->commit();

			# Crear metadata para la consulta exitosa.
			$metadata = 
				new json_response_metadata(
						JR_SUCCESS,
						$result->rowCount(),
						$result->queryString,
						$_SERVER['REQUEST_METHOD']
					);

			# Retorna el resultado de la consulta con información extra en formato JSON.
			return json_response::generate($metadata, DB_INSERT_SUCESS_MSG, $inserted_caso_id);
		} catch (Exception $e) {
			# RollBack transaction
			if(!is_null($DBH_SOS))
				$DBH_SOS->rollBack();
            return $e->getMessage();
		}
  	}

	/**
	 * Invoca al modelo para obtener los casos.
	 * @param string $caso_id 		ID del caso.
	 * @return JSON 		JSON que contiene los casos. 	
	 * @throws Exception If Ocurre alguna excepción en el proceso de la obtención de la data.
	 */
	public static function read($caso_id = ""){
		try {
			# Invocar a la clase sos_db_model.
			$DBH_SOS = new sos_db_model();

			$result = $DBH_SOS->getCasos($caso_id);

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

	//public static function update($form){}

	/**
	 * para eliminar un caso.
	 * @param string $caso_id 		ID del caso.
	 * @return JSON 				JSON indicando si la eliminación del caso fue un éxito o no. 
	 * @throws Exception If Ocurre alguna excepción en el proceso de la eliminación de la data.
	 */
	public static function delete($caso_id = ""){
		try {
			# Invocar a la clase sos_db_model.
			$DBH_SOS = new sos_db_model();

			$result = $DBH_SOS->deleteCaso($caso_id);

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
/*
CASO EJEMPLO:
INSERT INTO `sos_triaje`.`caso` 
	(`id`, `version`, `centro_id`, `descripcion`, `fecha_inicio`, `fecha_solucion`, `id_casosos`, `paciente_id`, `status_id`) 
VALUES 
(	5, '0', '2', 'Descripcion caso ejemplo', '2014-08-24 00:00:00', NULL, NULL, '10', '1');

UPDATE:
UPDATE `caso` SET id = 999 WHERE id = 5

DELETE:
DELETE FROM `caso` WHERE id = 5

--

HISTORIAL EJEMPLO:
INSERT INTO `sos_triaje`.`historial_caso`
	(`id`, `version`, `caso_id`, `estado_caso`, `fecha`, `medico_id`)
VALUES
	('29', '0', '5', 'En espera', '2014-08-24 00:00:00', '7');

DELETE FROM `caso` WHERE id = 5

--
Opinion ejemplo
INSERT INTO `sos_triaje`.`opinion` 
(`id`, `version`, `caso_id`, `cuerpo_opinion`, `estado_opinion`, `fecha_opinion`, `medico_id`, `nombre_opinion`)
VALUES 
( 7 , '111', '5', 'Contenido_opinion', '', '2014-08-24 09:41:56', '7', 'Titulo_Opinion');

 */
?>