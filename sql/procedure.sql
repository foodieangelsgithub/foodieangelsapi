DELIMITER //
CREATE PROCEDURE `cancelar_donaciones`()
LANGUAGE SQL
NOT DETERMINISTIC
CONTAINS SQL
SQL SECURITY DEFINER
COMMENT ''
BEGIN
DECLARE done BOOLEAN DEFAULT 0;
DECLARE num_rows, donacion_id, donacion_estado, servicio_id, estado, dias, voluntario_id  INT(11);
DECLARE listado CURSOR FOR
	SELECT
		d.id AS donacion_id, d.estado AS donacion_estado, s.id AS servicio_id,  s.estado, timestampdiff(DAY, d.fecha, NOW()) dias, s.voluntario_id
	FROM donacion d
		LEFT JOIN servicio s ON s.donacion_id=d.id;

	DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done=1;
	OPEN listado;
		SELECT FOUND_ROWS()+1 INTO num_rows;
		WHILE num_rows != 0 DO
			FETCH listado INTO donacion_id, donacion_estado, servicio_id, estado, dias, voluntario_id;
			IF donacion_estado<4 AND dias>0 AND donacion_estado!= 6 THEN
				INSERT IGNORE INTO `donacion_log` (`id_donacion`, `estado_donacion`,`id_servicio`, `estado_servicio`, `user_id`, `fecha`) VALUES (donacion_id, donacion_estado, servicio_id, estado, voluntario_id, NOW());
				UPDATE donacion SET `estado`=6, fechaModi=NOW() WHERE `id`=donacion_id;
				COMMIT;
			END IF;
			IF  estado<4 AND estado!= 6 AND dias>0 THEN
				INSERT IGNORE INTO `donacion_log` (`id_donacion`, `estado_donacion`,`id_servicio`, `estado_servicio`, `user_id`, `fecha`) VALUES (donacion_id, donacion_estado, servicio_id, estado, voluntario_id, NOW());
				UPDATE servicio SET `estado`=6, fechaModi=NOW() WHERE `id`=servicio_id;
				COMMIT;
			ELSEIF  estado>3 AND estado!= 6 AND estado!= 5 AND dias>1 THEN
				INSERT IGNORE INTO `donacion_log` (`id_donacion`, `estado_donacion`,`id_servicio`, `estado_servicio`, `user_id`, `fecha`)
				VALUES (donacion_id, donacion_estado, servicio_id, estado, voluntario_id, NOW());
				UPDATE servicio SET `estado`=6, fechaModi=NOW() WHERE `id`=servicio_id;
				UPDATE donacion SET `estado`=6, fechaModi=NOW() WHERE `id`=donacion_id;
				COMMIT;
			END IF;

			SET num_rows = num_rows - 1;
		END WHILE;

	CLOSE listado;
END

//
CREATE EVENT `cancelar_donaciones`
	ON SCHEDULE
		EVERY 1 DAY STARTS '2020-06-10 10:39:13'
	ON COMPLETION NOT PRESERVE
	ENABLE
	COMMENT ''
	DO BEGIN
CALL `cancelar_donaciones`();
END
//
