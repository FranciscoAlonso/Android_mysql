-- -- 
-- Se agregan las columnas de "api_key" y "user_extension" a la tabla
-- "actor_sistema" para su uso con el Web Service.
-- --
ALTER TABLE actor_sistema ADD api_key VARCHAR(255);
ALTER TABLE actor_sistema ADD user_extension VARCHAR(255);
--