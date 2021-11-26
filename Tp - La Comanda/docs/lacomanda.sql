-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 23-11-2021 a las 06:23:10
-- Versión del servidor: 10.4.21-MariaDB
-- Versión de PHP: 7.4.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `lacomanda`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados`
--

CREATE TABLE `empleados` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `clave` varchar(200) NOT NULL,
  `idTipo` int(11) NOT NULL,
  `tipo` varchar(20) NOT NULL,
  `idSector` int(11) NOT NULL,
  `sector` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `empleados`
--

INSERT INTO `empleados` (`id`, `nombre`, `clave`, `idTipo`, `tipo`, `idSector`, `sector`) VALUES
(1, 'Octavio', '$2y$10$fnOMWPxl9GOQwg6AkYW/i.72Pqzj/ca2cikup4l1QKvWw6gbd0Uu2', 0, 'Socio', 0, 'Todos'),
(2, 'Mariano', '$2y$10$fnOMWPxl9GOQwg6AkYW/i.72Pqzj/ca2cikup4l1QKvWw6gbd0Uu2', 0, 'Socio', 0, 'Todos'),
(3, 'Guido', '$2y$10$fnOMWPxl9GOQwg6AkYW/i.72Pqzj/ca2cikup4l1QKvWw6gbd0Uu2', 3, 'Mozo', 5, 'Mesas'),
(4, 'Pedro', '$2y$10$fnOMWPxl9GOQwg6AkYW/i.72Pqzj/ca2cikup4l1QKvWw6gbd0Uu2', 3, 'Mozo', 5, 'Mesas'),
(5, 'Arlando', '$2y$10$fnOMWPxl9GOQwg6AkYW/i.72Pqzj/ca2cikup4l1QKvWw6gbd0Uu2', 3, 'Mozo', 5, 'Mesas'),
(7, 'Mariana', '$2y$10$fnOMWPxl9GOQwg6AkYW/i.72Pqzj/ca2cikup4l1QKvWw6gbd0Uu2', 2, 'Bartender', 2, 'Tragos'),
(8, 'Esteban', '$2y$10$fnOMWPxl9GOQwg6AkYW/i.72Pqzj/ca2cikup4l1QKvWw6gbd0Uu2', 2, 'Bartender', 1, 'Cervezas'),
(9, 'Franco', '$2y$10$fnOMWPxl9GOQwg6AkYW/i.72Pqzj/ca2cikup4l1QKvWw6gbd0Uu2', 1, 'Cocinero', 4, 'Cocina'),
(10, 'Bernardo', '$2y$10$fnOMWPxl9GOQwg6AkYW/i.72Pqzj/ca2cikup4l1QKvWw6gbd0Uu2', 1, 'Cocinero', 3, 'CandyBar'),
(31, 'Pedro', '$2y$10$8MhG/ca1tQTtUuWVHDJbSewz5UucZ39iPgHtbGUdKivSwptOrhotm', 3, 'Mozo', 5, 'Mesas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `encuestas`
--

CREATE TABLE `encuestas` (
  `id` int(11) NOT NULL,
  `mozo` int(11) NOT NULL,
  `comida` int(11) NOT NULL,
  `cervezas` int(11) NOT NULL,
  `tragosyvino` int(11) NOT NULL,
  `candyBar` int(11) NOT NULL,
  `comentarios` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesas`
--

CREATE TABLE `mesas` (
  `id` int(11) NOT NULL,
  `estado` varchar(20) NOT NULL,
  `mozo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `mesas`
--

INSERT INTO `mesas` (`id`, `estado`, `mozo`) VALUES
(1, 'Cerrada', 4),
(2, 'Cerrada', 4),
(3, 'Cerrada', 4),
(4, 'Cerrada', 1),
(5, 'Cerrada', 1),
(8, 'Cerrada', 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `numero` int(11) NOT NULL,
  `codigo` varchar(5) NOT NULL,
  `producto` int(11) NOT NULL,
  `idSector` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `mesa` int(11) NOT NULL,
  `mozo` int(11) NOT NULL,
  `demora` int(11) NOT NULL,
  `foto` varchar(120) NOT NULL,
  `fecha` varchar(20) NOT NULL,
  `estado` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`numero`, `codigo`, `producto`, `idSector`, `cantidad`, `mesa`, `mozo`, `demora`, `foto`, `fecha`, `estado`) VALUES
(172, 'DaAbT', 11, 3, 2, 3, 4, 20, 'ImagenesDeMesas/DaAbT-3 - 4_2021-11-22.jpg', '2021-11-23', 'En preparacion'),
(173, 'DaAbT', 1, 4, 1, 3, 4, 0, 'ImagenesDeMesas/DaAbT-3 - 4_2021-11-22.jpg', '2021-11-22', 'Pendiente'),
(174, 'DaAbT', 2, 4, 1, 3, 4, 30, 'ImagenesDeMesas/DaAbT-3 - 4_2021-11-22.jpg', '2021-11-23', 'En preparacion'),
(175, 'y8XKu', 3, 1, 4, 1, 5, 0, 'ImagenesDeMesas/y8XKu-1 - 5_2021-11-23.jpg', '2021-11-23', 'Pendiente'),
(176, 'X4j9q', 4, 2, 3, 6, 3, 0, 'ImagenesDeMesas/X4j9q-6 - 3_2021-11-23.jpg', '2021-11-23', 'Pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(50) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` int(11) NOT NULL,
  `tiempo` varchar(20) NOT NULL,
  `sector` varchar(20) NOT NULL,
  `idSector` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `descripcion`, `cantidad`, `precio`, `tiempo`, `sector`, `idSector`) VALUES
(1, 'Milanesa a Caballo', 50, 250, '30', 'Cocina', 4),
(2, 'Hamburguesa de Garbanzo', 50, 200, '30', 'Cocina', 4),
(3, 'Corona 440ml', 150, 150, '10', 'Cervezas', 1),
(4, 'Daikiri', 10, 120, '30', 'Tragos', 2),
(11, 'Lemon Pie', 8, 300, '10', 'CandyBar', 3);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `empleados`
--
ALTER TABLE `empleados`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `encuestas`
--
ALTER TABLE `encuestas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `mesas`
--
ALTER TABLE `mesas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`numero`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `empleados`
--
ALTER TABLE `empleados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `encuestas`
--
ALTER TABLE `encuestas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `mesas`
--
ALTER TABLE `mesas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `numero` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=177;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
