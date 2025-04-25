-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 25, 2025 at 01:45 PM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecomovi`
--

-- --------------------------------------------------------

--
-- Table structure for table `canjeos`
--

DROP TABLE IF EXISTS `canjeos`;
CREATE TABLE IF NOT EXISTS `canjeos` (
  `id_canjeo` int NOT NULL AUTO_INCREMENT,
  `plac_veh` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nom_reco` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_canjeo`),
  KEY `plac_veh` (`plac_veh`),
  KEY `nom_reco` (`nom_reco`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `canjeos`
--

INSERT INTO `canjeos` (`id_canjeo`, `plac_veh`, `nom_reco`, `fecha`) VALUES
(29, 'GAY23H', 'novia', '2025-04-24 20:20:48'),
(28, 'GRX98H', 'hola', '2025-04-24 19:09:08'),
(27, 'GRX98H', 'hola', '2025-04-24 04:08:12'),
(26, 'GRX98H', 'hola', '2025-04-24 03:58:15');

-- --------------------------------------------------------

--
-- Table structure for table `movilidad`
--

DROP TABLE IF EXISTS `movilidad`;
CREATE TABLE IF NOT EXISTS `movilidad` (
  `id_mov` int NOT NULL AUTO_INCREMENT,
  `departamento` varchar(20) NOT NULL,
  `municipio` varchar(40) NOT NULL,
  `plac_veh` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `fecha_inicial` date DEFAULT NULL,
  `hora_inicial` time DEFAULT NULL,
  `fecha_final` date DEFAULT NULL,
  `hora_final` time DEFAULT NULL,
  `foto_inicial` varchar(255) DEFAULT NULL,
  `foto_final` varchar(255) DEFAULT NULL,
  `puntos` int DEFAULT '0',
  PRIMARY KEY (`id_mov`),
  KEY `fk_movilidad_plac_veh` (`plac_veh`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `recompensa`
--

DROP TABLE IF EXISTS `recompensa`;
CREATE TABLE IF NOT EXISTS `recompensa` (
  `nom_reco` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `puntos` int NOT NULL,
  `imagen_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `disponible` tinyint(1) NOT NULL DEFAULT '1',
  `estado` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'activo',
  PRIMARY KEY (`nom_reco`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recompensa`
--

INSERT INTO `recompensa` (`nom_reco`, `descripcion`, `puntos`, `imagen_url`, `disponible`, `estado`) VALUES
('10010101', 'baño', 500, 'uploads/72yuhnca.png', 18, 'activo'),
('df', 'sf', 34, 'uploads/25d4d352-6a4a-4354-83b1-86fcfb078564.jpeg', 34, 'activo'),
('hola', 'h', 10, 'uploads/Landing Page.png', 2, 'activo');

-- --------------------------------------------------------

--
-- Table structure for table `supervisor`
--

DROP TABLE IF EXISTS `supervisor`;
CREATE TABLE IF NOT EXISTS `supervisor` (
  `nombre` varchar(100) NOT NULL,
  `tipo_id` varchar(2) NOT NULL,
  `num_doc_usu` varchar(20) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` varchar(20) NOT NULL,
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `num_doc_usu` (`num_doc_usu`),
  UNIQUE KEY `correo` (`correo`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `supervisor`
--

INSERT INTO `supervisor` (`nombre`, `tipo_id`, `num_doc_usu`, `telefono`, `correo`, `password`, `rol`, `fecha_registro`) VALUES
('kevin adso rivera', 'cc', '1007647711', '3202295786', 'kevinrivera@gmail.com', '$2y$10$XLSiacYDVkWB3Ca7M2hrL.1kWozGbTBREj3HzTEoWGjJHY69.Esoa', 'Supervisor', '2025-04-24 04:22:17');

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `nom_usu` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `apell_usu` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `tipo_documento` varchar(20) NOT NULL,
  `num_doc_usu` varchar(20) NOT NULL,
  `direccion` varchar(200) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `rol` enum('admin','usuario','supervisor') NOT NULL,
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `num_doc_usu` (`num_doc_usu`),
  UNIQUE KEY `num_doc_usu_2` (`num_doc_usu`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`nom_usu`, `apell_usu`, `fecha_nacimiento`, `tipo_documento`, `num_doc_usu`, `direccion`, `email`, `telefono`, `contrasena`, `rol`, `fecha_registro`) VALUES
('Kevin', 'Rivera pro', '2000-02-10', 'Pasaporte', '1007647713', 'Calle 12 24a54', 'lolkevinrivera@gmail.com', '3202295786', '$2y$10$IEpc1erTlOMOQAEQSq0xreiP38GI/SPBjG9tx5Tx5NXO31WmXUfH.', 'usuario', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `vehiculos`
--

DROP TABLE IF EXISTS `vehiculos`;
CREATE TABLE IF NOT EXISTS `vehiculos` (
  `plac_veh` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tip_veh` enum('carro','moto','camion') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tarj_prop_veh` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tecno_m` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `foto_tecno` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `soat` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `foto_soat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `mar_veh` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `lin_veh` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `color_veh` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `num_motor_veh` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `clase_veh` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `combus_veh` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `capaci_veh` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `num_chasis_veh` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `model_veh` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `vehicle_photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `puntos_totales` int DEFAULT '0',
  `num_doc_usu` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`plac_veh`),
  KEY `fk_vehiculos_usuarios` (`num_doc_usu`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehiculos`
--

INSERT INTO `vehiculos` (`plac_veh`, `tip_veh`, `tarj_prop_veh`, `tecno_m`, `foto_tecno`, `soat`, `foto_soat`, `mar_veh`, `lin_veh`, `color_veh`, `num_motor_veh`, `clase_veh`, `combus_veh`, `capaci_veh`, `num_chasis_veh`, `model_veh`, `vehicle_photo`, `puntos_totales`, `num_doc_usu`) VALUES
('GAY23H', 'moto', '12234', '23234', 'uploads/680b8de60aa13_images.png', '12345678', 'uploads/680b8de60ad90_25d4d352-6a4a-4354-83b1-86fcfb078564.jpeg', 'KAWASAKI', 'h2r', 'ROSA', 'W2123234', 'FEO', 'H20', '2', '132423', 'MOTO', '', 0, '1007647713');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `movilidad`
--
ALTER TABLE `movilidad`
  ADD CONSTRAINT `fk_movilidad_plac_veh` FOREIGN KEY (`plac_veh`) REFERENCES `vehiculos` (`plac_veh`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `vehiculos`
--
ALTER TABLE `vehiculos`
  ADD CONSTRAINT `fk_vehiculos_usuarios` FOREIGN KEY (`num_doc_usu`) REFERENCES `usuarios` (`num_doc_usu`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
