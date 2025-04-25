-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 20, 2025 at 02:53 AM
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
  `plac_veh` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nom_reco` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_canjeo`),
  KEY `plac_veh` (`plac_veh`),
  KEY `nom_reco` (`nom_reco`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `canjeos`
--

INSERT INTO `canjeos` (`id_canjeo`, `plac_veh`, `nom_reco`, `fecha`) VALUES
(13, 'NKD12H', 'hola', '2025-04-18 03:20:27'),
(12, 'NKD12H', 'hola', '2025-04-18 02:36:12'),
(11, 'NKD12H', 'hola', '2025-04-18 02:36:03'),
(10, 'NKD12H', 'hola', '2025-04-18 02:32:17');

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
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `movilidad`
--

INSERT INTO `movilidad` (`id_mov`, `departamento`, `municipio`, `plac_veh`, `fecha_inicial`, `hora_inicial`, `fecha_final`, `hora_final`, `foto_inicial`, `foto_final`, `puntos`) VALUES
(32, '', '', 'NKD12H', '2025-04-17', '22:16:27', '2025-04-17', '22:18:58', 'Imagen de WhatsApp 2025-03-18 a las 23.06.24_a42a336f.jpg', 'C:\\wamp64\\www\\ECOMOVI-PROYECTO/uploads/final_6801c4a206640.jpg', 190),
(33, '', '', 'NKD12H', '2025-04-17', '22:19:10', '2025-04-17', '22:20:19', 'Imagen de WhatsApp 2025-03-31 a las 12.15.52_3bd2844a.jpg', 'C:\\wamp64\\www\\ECOMOVI-PROYECTO/uploads/final_6801c4f362e69.png', 200),
(34, '', '', 'NKD12H', '2025-04-18', '11:56:07', '2025-04-18', '11:57:24', 'Imagen de WhatsApp 2025-03-31 a las 12.15.52_3bd2844a.jpg', 'C:\\wamp64\\www\\ECOMOVI-PROYECTO/uploads/final_68028474119e0.jpg', 200),
(35, '', '', 'NKD12H', '2025-04-18', '19:58:29', '2025-04-18', '19:59:37', 'Imagen de WhatsApp 2025-03-31 a las 12.15.52_3bd2844a.jpg', 'C:\\wamp64\\www\\ECOMOVI-PROYECTO/uploads/final_6802f57951704.png', 200),
(36, '', '', 'NKD12H', '2025-04-18', '20:03:31', '2025-04-18', '20:06:38', 'Imagen de WhatsApp 2025-03-31 a las 12.15.52_3bd2844a.jpg', 'C:\\wamp64\\www\\ECOMOVI-PROYECTO/uploads/final_6802f71e0d01f.jpg', 200),
(37, '', '', 'NKD12H', '2025-04-18', '20:11:38', '2025-04-18', '20:12:47', '72yuhnca.png', 'C:\\wamp64\\www\\ECOMOVI-PROYECTO/uploads/final_6802f88f8f23b.jpg', 100);

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
('10010101', 'baño', 500, 'uploads/72yuhnca.png', 20, 'activo'),
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
('kevin adso rivera', 'cc', '1007647711', '3202295786', 'kevinrivera123@gmail.com', '$2y$10$QgEBrXKWYHLjAha148TaxeVGeKCWP7uU9zNRZ8oSEBsB2s30Bjz0y', 'Supervisor', '2025-04-20 02:37:31');

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
  `num_doc_usu` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `direccion` varchar(200) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `rol` enum('admin','usuario','supervisor') NOT NULL,
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`num_doc_usu`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`nom_usu`, `apell_usu`, `fecha_nacimiento`, `tipo_documento`, `num_doc_usu`, `direccion`, `email`, `telefono`, `contrasena`, `rol`, `fecha_registro`) VALUES
('kevin stiven', 'rivera quintero', '2000-02-10', 'Cédula', '1007647713', 'calle 108', 'lolkevinrivera@gmail.com', '3202295786', '$2y$10$2/kq9e4mMMVAweGV7uOaJ.ZV7pc0oH.dmt5VBnDZRpP3Gj0l8ThL2', 'usuario', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `vehiculos`
--

DROP TABLE IF EXISTS `vehiculos`;
CREATE TABLE IF NOT EXISTS `vehiculos` (
  `plac_veh` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tip_veh` enum('carro','moto','camion') COLLATE utf8mb4_general_ci NOT NULL,
  `tarj_prop_veh` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `tecno_m` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `foto_tecno` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `soat` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `foto_soat` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `mar_veh` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `lin_veh` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `color_veh` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `num_motor_veh` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `clase_veh` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `combus_veh` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `capaci_veh` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `num_chasis_veh` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `model_veh` varchar(4) COLLATE utf8mb4_general_ci NOT NULL,
  `vehicle_photo` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `puntos_totales` int DEFAULT '0',
  PRIMARY KEY (`plac_veh`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehiculos`
--

INSERT INTO `vehiculos` (`plac_veh`, `tip_veh`, `tarj_prop_veh`, `tecno_m`, `foto_tecno`, `soat`, `foto_soat`, `mar_veh`, `lin_veh`, `color_veh`, `num_motor_veh`, `clase_veh`, `combus_veh`, `capaci_veh`, `num_chasis_veh`, `model_veh`, `vehicle_photo`, `puntos_totales`) VALUES
('NKD12H', 'moto', '1112112123', '132434', 'uploads/68019add78b27_72yuhnca.png', '13235', 'uploads/68019add7908f_Imagen de WhatsApp 2025-03-31 a las 12.15.52_3bd2844a.jpg', 'HONDA', 'BON', 'ROJO', 'BUENI', 'bonito', 'MUCHO', '2', '112334', 'BMW', '', 190);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `movilidad`
--
ALTER TABLE `movilidad`
  ADD CONSTRAINT `fk_movilidad_plac_veh` FOREIGN KEY (`plac_veh`) REFERENCES `vehiculos` (`plac_veh`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
