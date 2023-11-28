-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-11-2023 a las 18:40:25
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `parcial_ii`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ajustes`
--

CREATE TABLE `ajustes` (
  `id` int(11) NOT NULL,
  `id_reserva` int(11) NOT NULL,
  `importe_previo` int(11) NOT NULL,
  `importe_nuevo` int(11) NOT NULL,
  `motivo` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ajustes`
--

INSERT INTO `ajustes` (`id`, `id_reserva`, `importe_previo`, `importe_nuevo`, `motivo`) VALUES
(1, 1, 100000, 199999, 'Inflacion pais.'),
(2, 1, 199999, 100000, 'Vuelve valor anterior');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id` int(6) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `clave` varchar(125) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `tipo_doc` varchar(10) NOT NULL,
  `nro_doc` int(11) NOT NULL,
  `tipo_cliente` varchar(50) NOT NULL,
  `pais` varchar(50) NOT NULL,
  `ciudad` varchar(50) NOT NULL,
  `telefono` int(11) NOT NULL,
  `modalidad_pago` varchar(50) NOT NULL,
  `foto_usuario` varchar(50) DEFAULT NULL,
  `fecha_baja` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id`, `usuario`, `clave`, `nombre`, `apellido`, `email`, `tipo_doc`, `nro_doc`, `tipo_cliente`, `pais`, `ciudad`, `telefono`, `modalidad_pago`, `foto_usuario`, `fecha_baja`) VALUES
(100000, 'Tim123', '$2y$10$n2kiEbck7gVrBP./dWqVlu2xQeSEpTqQcqSy9HVyTr5HDb4H6HoEO', 'Tim', 'Henson', 'lalala@mail.com', 'DNI', 40252987, 'indi', 'Argentina', 'Wilde, Avellaneda', 2147483647, 'Credito', '100000INDI-DNI.png', NULL),
(100001, 'test5', '$2y$10$tbCR9vRUgROHmZHD..9AMeIjkGnUs3T8NVfFEinFkg3BKFZgDVnuW', 'Tim', 'Henson', 'yorha@mail.com', 'DNI', 40252987, 'indi', 'Argentina', 'Wilde, Avellaneda', 2147483647, 'Credito', '100001INDI-DNI.png', NULL),
(100002, 'test', '$2y$10$p/s5Lt6WWkVfPSyrxPFIWOtp2nBkwTEz1l8BeYh87AzkZfpHnxUb.', 'Nier2', 'Automata', 'yorha2@mail.com', 'DNI', 99999999, 'CORPO', 'Argentina', 'Wilde, Avellaneda', 2147483647, 'Efectivo', '100002CORPO-DNI.png', NULL),
(100003, 'test2', '$2y$10$SNZ7LhIVseznl5uJ3eYt8O6ykci1WCl4WaoxqWpBFx4FJJBXbI4Fy', 'Nine', 'Ese', 'yorha3@mail.com', 'DNI', 40963124, 'CORPO', 'Argentina', 'Espacio', 123456789, 'Efectivo', '100003CORPO-DNI.png', NULL),
(100004, 'json', '$2y$10$dUKPagA99BQ85WT2.4zXB.cQisqXSzReKZeSAlzmbcsHHHirYCVOW', 'Gordon', 'Freeman', 'gfreeman@gmail.com', 'DNI', 132465798, 'CORPO', 'Argentina', 'Avellaneda', 1133554466, 'Credito', NULL, NULL),
(100005, 'json2', '$2y$10$4dEYMIF7J5TIGA/AOaCb7uZTIvPLOU5FS96kQUyU4Rv6qGB7FzaMO', 'Isaac', 'Kleiner', 'ikleiner@hotmail.com', 'LE', 546813278, 'INDI', 'Argentina', 'Quilmes', 44332211, 'Efectivo', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas`
--

CREATE TABLE `reservas` (
  `id` int(11) NOT NULL,
  `tipo_cliente` varchar(50) NOT NULL,
  `nro_cliente` int(11) NOT NULL,
  `fecha_entrada` date NOT NULL,
  `fecha_salida` date NOT NULL,
  `tipo_habitacion` varchar(50) NOT NULL,
  `importe_total` int(11) NOT NULL,
  `modalidad_pago` varchar(50) NOT NULL,
  `estado` varchar(50) NOT NULL,
  `foto_reserva` varchar(50) DEFAULT NULL,
  `fecha_baja` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reservas`
--

INSERT INTO `reservas` (`id`, `tipo_cliente`, `nro_cliente`, `fecha_entrada`, `fecha_salida`, `tipo_habitacion`, `importe_total`, `modalidad_pago`, `estado`, `foto_reserva`, `fecha_baja`) VALUES
(1, 'INDI', 100000, '2023-10-22', '2023-10-24', 'suite', 100000, 'Credito', 'activa', 'indi_100000_1.png', NULL),
(2, 'INDI', 100000, '2023-10-23', '2023-10-24', 'DOBLE', 255000, 'Efectivo', 'cancelada', 'indi_100000_2.png', '2023-11-27'),
(3, 'CORPO', 100001, '2023-10-24', '2023-10-24', 'DOBLE', 255000, 'Efectivo', 'activa', 'corpo_100001_3.png', '2023-11-26'),
(4, 'INDI', 100001, '2023-10-24', '2023-10-24', 'DOBLE', 15647, 'Credito', 'activa', 'indi_100001_4.png', NULL),
(5, 'INDI', 100001, '2023-10-24', '2023-10-24', 'SUITE', 999999, 'Efectivo', 'activa', 'indi_100001_5.png', '2023-11-27'),
(6, 'INDI', 100001, '2023-11-20', '2023-11-27', 'DOBLE', 250000, 'Efectivo', 'activa', 'indi_100001_6.png', '2023-11-27'),
(7, 'CORPO', 100001, '2023-11-21', '2023-11-29', 'SUITE', 9950000, 'Efectivo', 'activa', 'corpo_100001_7.png', NULL),
(8, 'INDI', 100001, '2023-11-28', '2023-11-28', 'SIMPLE', 9950000, 'Efectivo', 'activa', 'indi_100001_8.png', NULL),
(9, 'INDI', 100000, '2023-11-26', '2023-11-26', 'suite', 255000, 'Credito', 'activa', 'indi_100000_9.png', '2023-11-27'),
(10, 'CORPO', 100000, '2023-11-26', '2023-11-26', 'doble', 555000, 'Efectivo', 'activa', 'corpo_100000_10.png', '2023-11-27'),
(11, 'CORPO', 100000, '2023-11-26', '2023-11-26', 'suite', 955000, 'Credito', 'activa', 'corpo_100000_11.png', NULL),
(12, 'INDI', 100000, '1969-12-31', '1969-12-31', 'suite', 100000, 'Credito', 'activa', NULL, NULL),
(13, 'INDI', 100000, '1969-12-31', '1969-12-31', 'DOBLE', 255000, 'Efectivo', 'cancelada', NULL, NULL),
(14, 'INDI', 100000, '1969-12-31', '1969-12-31', 'suite', 100000, 'Credito', 'activa', NULL, NULL),
(15, 'INDI', 100000, '1969-12-31', '1969-12-31', 'DOBLE', 255000, 'Efectivo', 'cancelada', NULL, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `ajustes`
--
ALTER TABLE `ajustes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `ajustes`
--
ALTER TABLE `ajustes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100006;

--
-- AUTO_INCREMENT de la tabla `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
