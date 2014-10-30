-- -- 
-- Se agregan las columnas de "api_key" y "user_extension" a la tabla
-- "actor_sistema" para su uso con el Web Service.
-- --
ALTER TABLE actor_sistema ADD api_key VARCHAR(255);
ALTER TABLE actor_sistema ADD user_extension VARCHAR(255);

-- -- 
-- Se agrega las columnas "content_type" a la tabla
-- "archivo" para su uso con el Web Service.
-- --
ALTER TABLE archivo ADD mime_type VARCHAR(255) default 'image/jpg';

-- -- 
-- Se agrega la columna "cdr_uniqueid" a la tabla
-- "opinion" para su uso con el Web Service.
-- --
ALTER TABLE opinion ADD cdr_uniqueid VARCHAR(255);

-- -- 
-- Se agrega la columna "group_extension" a la tabla
-- "especialidad" para su uso con el Web Service.
-- --
ALTER TABLE especialidad ADD group_extension VARCHAR(255);

-- -- 
-- Se agrega un paciente generico a la tabla "paciente".
-- --
INSERT INTO `sos_triaje`.`paciente` (`id` ,`fecha_nacimiento`)
VALUES ('0', '0000-00-00 00:00:00');

-- -- 
-- Se eliminan todos los FOREIGN KEY para actualizarlos y 
-- agregarles la propiedad "ON DELETE CASCADE ON UPDATE CASCADE".
-- --
ALTER TABLE archivo DROP FOREIGN KEY `FKD368E0CC575F15CE`;

ALTER TABLE caso DROP FOREIGN KEY `FK2E7B3AC4E46216`;
ALTER TABLE caso DROP FOREIGN KEY `FK2E7B3AD8BF583C`;
ALTER TABLE caso DROP FOREIGN KEY `FK2E7B3ADBDDF23E`;

ALTER TABLE caso_especialidad DROP FOREIGN KEY `FK4E7036174A6E53D6`;
ALTER TABLE caso_especialidad DROP FOREIGN KEY `FK4E703617C08DA023`;

ALTER TABLE especialista_especialidades DROP FOREIGN KEY `FK21C94C5421186A7F`;
ALTER TABLE especialista_especialidades DROP FOREIGN KEY `FK21C94C544A6E53D6`;

ALTER TABLE historial_caso DROP FOREIGN KEY `FKD8E3C9CA575F15CE`;
ALTER TABLE historial_caso DROP FOREIGN KEY `FKD8E3C9CADDADB17F`;

ALTER TABLE opinion DROP FOREIGN KEY `FKB4EDB382575F15CE`;
ALTER TABLE opinion DROP FOREIGN KEY `FKB4EDB382DDADB17F`;

ALTER TABLE opinion_opinion DROP FOREIGN KEY `FKDDC86BC5387563EE`;
ALTER TABLE opinion_opinion DROP FOREIGN KEY `FKDDC86BC5CA99929D`;

-- -- 
-- Se agregan nuevamente los FOREIGN KEY con la
-- propiedad "ON DELETE CASCADE ON UPDATE CASCADE".
-- --
ALTER TABLE `archivo`
  ADD CONSTRAINT `FKD368E0CC575F15CE` FOREIGN KEY (`caso_id`) REFERENCES `caso` (`id`) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE `caso`
  ADD CONSTRAINT `FK2E7B3AC4E46216` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK2E7B3AD8BF583C` FOREIGN KEY (`paciente_id`) REFERENCES `paciente` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `FK2E7B3ADBDDF23E` FOREIGN KEY (`centro_id`) REFERENCES `centrosos` (`id`) ON UPDATE CASCADE;

ALTER TABLE `caso_especialidad`
  ADD CONSTRAINT `FK4E7036174A6E53D6` FOREIGN KEY (`especialidad_id`) REFERENCES `especialidad` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
  ADD CONSTRAINT `FK4E703617C08DA023` FOREIGN KEY (`caso_especialidades_id`) REFERENCES `caso` (`id`) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE `especialista_especialidades`
  ADD CONSTRAINT `FK21C94C5421186A7F` FOREIGN KEY (`especialista_id`) REFERENCES `especialista` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
  ADD CONSTRAINT `FK21C94C544A6E53D6` FOREIGN KEY (`especialidad_id`) REFERENCES `especialidad` (`id`) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE `historial_caso`
  ADD CONSTRAINT `FKD8E3C9CA575F15CE` FOREIGN KEY (`caso_id`) REFERENCES `caso` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
  ADD CONSTRAINT `FKD8E3C9CADDADB17F` FOREIGN KEY (`medico_id`) REFERENCES `medico` (`id`) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE `opinion`
  ADD CONSTRAINT `FKB4EDB382575F15CE` FOREIGN KEY (`caso_id`) REFERENCES `caso` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
  ADD CONSTRAINT `FKB4EDB382DDADB17F` FOREIGN KEY (`medico_id`) REFERENCES `medico` (`id`) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE `opinion_opinion`
  ADD CONSTRAINT `FKDDC86BC5387563EE` FOREIGN KEY (`opinion_id`) REFERENCES `opinion` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
  ADD CONSTRAINT `FKDDC86BC5CA99929D` FOREIGN KEY (`opinion_opiniones_id`) REFERENCES `opinion` (`id`) ON UPDATE CASCADE ON DELETE CASCADE;