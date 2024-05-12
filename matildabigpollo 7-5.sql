-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 07-05-2024 a las 17:58:38
-- Versión del servidor: 10.11.7-MariaDB-cll-lve
-- Versión de PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `u598064194_matildebig`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturas`
--

CREATE TABLE `facturas` (
  `id` int(11) NOT NULL,
  `nro_factura` varchar(50) NOT NULL,
  `id_proveedor` int(11) NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `tipo` varchar(50) DEFAULT NULL,
  `monto` decimal(10,2) DEFAULT NULL,
  `observaciones` text DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `facturas`
--

INSERT INTO `facturas` (`id`, `nro_factura`, `id_proveedor`, `fecha`, `tipo`, `monto`, `observaciones`) VALUES
(33, '6', 19, '2024-05-04 22:36:27', '1', '110461.80', 'SALDO INICIAL 4/5/24'),
(32, '5', 18, '2024-05-04 22:35:33', '1', '58.54', 'SALDO INICIAL 4/5/24'),
(31, '4', 14, '2024-05-04 22:34:44', '1', '72463.95', 'SALDO INICIAL 4/5/24'),
(30, '3', 12, '2024-05-04 22:33:52', '1', '39181.47', 'SALDO INICIAL'),
(29, '2', 9, '2024-05-04 22:33:24', '1', '18370596.05', 'SALDO INICIAL 4/5/24'),
(28, '1', 8, '2024-05-04 22:32:40', '1', '3377322.00', 'SALDO INICIAL AL 4/5/24'),
(34, '7', 23, '2024-05-04 22:37:06', '1', '12200.00', 'SALDO INICIAL 4/5/24'),
(35, '8', 26, '2024-05-04 22:37:36', '1', '19550264.83', 'SALDO INICIAL 4/5/24'),
(36, '00002-00017364', 14, '2024-05-07 17:23:57', '1', '72515.30', 'Descartables');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `facturas`
--
ALTER TABLE `facturas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
