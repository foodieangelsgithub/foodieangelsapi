-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         8.0.19 - MySQL Community Server - GPL
-- SO del servidor:              Linux
-- HeidiSQL Versión:             11.0.0.5919
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Volcando datos para la tabla foodie_angels.ingresos: ~7 rows (aproximadamente)
DELETE FROM `ingresos`;
/*!40000 ALTER TABLE `ingresos` DISABLE KEYS */;
INSERT INTO `ingresos` (`id`, `nombre`) VALUES
	(1, '0 a 500'),
	(2, '5000 a 10000'),
	(3, '10000 a 15000'),
	(4, '15000 a 20000'),
	(5, '20000 a 25000'),
	(6, '25000 a 30000'),
	(7, 'más de 30000');
/*!40000 ALTER TABLE `ingresos` ENABLE KEYS */;

-- Volcando datos para la tabla foodie_angels.proc_ingresos: ~6 rows (aproximadamente)
DELETE FROM `proc_ingresos`;
/*!40000 ALTER TABLE `proc_ingresos` DISABLE KEYS */;
INSERT INTO `proc_ingresos` (`id`, `nombre`) VALUES
	(1, 'Proc. ingreso 1'),
	(2, 'Proc. ingreso 2'),
	(3, 'Proc. ingreso 3'),
	(4, 'Proc. ingreso 4'),
	(5, 'Proc. ingreso 5'),
	(6, 'Proc. ingreso 6');
/*!40000 ALTER TABLE `proc_ingresos` ENABLE KEYS */;

-- Volcando datos para la tabla foodie_angels.producto: ~4 rows (aproximadamente)
DELETE FROM `producto`;
/*!40000 ALTER TABLE `producto` DISABLE KEYS */;
INSERT INTO `producto` (`id`, `nombre`) VALUES
	(1, 'Carne'),
	(2, 'Pescado'),
	(3, 'Fruta'),
	(4, 'Verdura');
/*!40000 ALTER TABLE `producto` ENABLE KEYS */;

-- Volcando datos para la tabla foodie_angels.relacion: ~6 rows (aproximadamente)
DELETE FROM `relacion`;
/*!40000 ALTER TABLE `relacion` DISABLE KEYS */;
INSERT INTO `relacion` (`id`, `nombre`) VALUES
	(1, 'Relación 1'),
	(2, 'Relación 2'),
	(3, 'Relación 3'),
	(4, 'Relación 4'),
	(5, 'Relación 5'),
	(6, 'Relación 6');
/*!40000 ALTER TABLE `relacion` ENABLE KEYS */;

-- Volcando datos para la tabla foodie_angels.situacion_laboral: ~6 rows (aproximadamente)
DELETE FROM `situacion_laboral`;
/*!40000 ALTER TABLE `situacion_laboral` DISABLE KEYS */;
INSERT INTO `situacion_laboral` (`id`, `nombre`) VALUES
	(1, 'Sit.laboral 1'),
	(2, 'Sit.laboral 2'),
	(3, 'Sit.laboral 3'),
	(4, 'Sit.laboral 4'),
	(5, 'Sit.laboral 5'),
	(6, 'Sit.laboral 6');
/*!40000 ALTER TABLE `situacion_laboral` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
