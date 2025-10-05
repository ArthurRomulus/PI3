-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3307
-- Tiempo de generación: 06-10-2025 a las 01:11:39
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `tienda_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `auditlogs`
--

CREATE TABLE `auditlogs` (
  `id_au` int(11) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `nombrecategoria` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`nombrecategoria`) VALUES
('Bebidas calientes'),
('Bebidas frias');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cortes_caja`
--

CREATE TABLE `cortes_caja` (
  `id_corte_caja` int(11) NOT NULL,
  `fecha_apertura` datetime NOT NULL,
  `fecha_cierre` datetime DEFAULT NULL,
  `saldo_inicial` decimal(10,2) NOT NULL,
  `saldo_esperado` decimal(10,2) DEFAULT NULL,
  `saldo_real_contado` decimal(10,2) DEFAULT NULL,
  `diferencia` decimal(10,2) DEFAULT NULL,
  `id_usuario_cierre` int(11) NOT NULL,
  `estado` enum('abierto','cerrado') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientos`
--

CREATE TABLE `movimientos` (
  `id_movimiento` int(11) NOT NULL,
  `id_corte_caja` int(11) NOT NULL,
  `tipo_movimiento` enum('venta','gasto','devolucion','ingreso_inicial','retiro') NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `metodo_pago` enum('efectivo','tarjeta','transferencia') DEFAULT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha_hora` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `idp` int(11) NOT NULL,
  `namep` varchar(50) NOT NULL,
  `precio` int(11) NOT NULL CHECK (`precio` >= 0),
  `categoria` varchar(50) DEFAULT NULL,
  `sabor` int(11) DEFAULT NULL,
  `tamano_defecto` int(11) NOT NULL DEFAULT 1,
  `status` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`idp`, `namep`, `precio`, `categoria`, `sabor`, `tamano_defecto`, `status`) VALUES
(51, 'Americano', 40, 'Bebidas calientes', 1, 1, 1),
(52, 'Espresso', 40, 'Bebidas calientes', 1, 1, 1),
(53, 'Macchiato', 50, 'Bebidas calientes', 1, 1, 1),
(54, 'Capucchino Entero', 45, 'Bebidas calientes', 2, 1, 1),
(55, 'Lechero (Entera)', 42, 'Bebidas calientes', 2, 1, 1),
(56, 'Lechero Deslactosado', 45, 'Bebidas calientes', 3, 1, 1),
(57, 'Moka', 50, 'Bebidas calientes', 1, 1, 1),
(58, 'Matcha', 65, 'Bebidas calientes', 1, 1, 1),
(59, 'Capucchino Deslactos', 55, 'Bebidas calientes', 3, 1, 1),
(60, 'Irlandés', 70, 'Bebidas calientes', 1, 1, 1),
(61, 'Latte Entero', 45, 'Bebidas calientes', 2, 1, 1),
(62, 'Latter Deslactosado', 42, 'Bebidas calientes', 3, 1, 1),
(63, 'Latte Avena', 40, 'Bebidas calientes', 4, 1, 1),
(64, 'Latte Almendra', 45, 'Bebidas calientes', 5, 1, 1),
(65, 'Carajillo', 70, 'Bebidas calientes', 1, 1, 1),
(66, 'Matchalatte', 60, 'Bebidas calientes', 1, 1, 1),
(67, 'Doble', 55, 'Bebidas calientes', 1, 1, 1),
(68, 'Chocolate caliente (Entero)', 30, 'Bebidas calientes', 2, 1, 1),
(69, 'Chocolate caliente Deslactosad', 30, 'Bebidas calientes', 3, 1, 1),
(70, 'Chocolate caliente Avena', 30, 'Bebidas calientes', 4, 1, 1),
(71, 'Latte Entero', 45, 'Bebidas calientes', 2, 1, 1),
(72, 'Latte Deslactosado', 45, 'Bebidas calientes', 3, 1, 1),
(73, 'Latte Avena', 45, 'Bebidas calientes', 4, 1, 1),
(74, 'Latte Almendra', 45, 'Bebidas calientes', 5, 1, 1),
(75, 'Carajillo', 70, 'Bebidas calientes', 1, 1, 1),
(76, 'Matchalatte', 60, 'Bebidas calientes', 1, 1, 1),
(77, 'Doble', 50, 'Bebidas calientes', 1, 1, 1),
(78, 'Chocolate caliente (Entero)', 30, 'Bebidas calientes', 2, 1, 1),
(79, 'Chocolate caliente Deslactosado', 30, 'Bebidas calientes', 3, 1, 1),
(80, 'Chocolate caliente Avena', 30, 'Bebidas calientes', 4, 1, 1),
(81, 'Frappé clásico Entero', 60, 'Bebidas frias', 2, 1, 1),
(82, 'Frappé clásico Deslactosado', 60, 'Bebidas frias', 3, 1, 1),
(83, 'Frappé moka Entero', 65, 'Bebidas frias', 2, 1, 1),
(84, 'Frappé moka Deslactosado', 65, 'Bebidas frias', 3, 1, 1),
(85, 'Frappé caramel Entero', 65, 'Bebidas frias', 2, 1, 1),
(86, 'Frappé caramel Deslactosado', 65, 'Bebidas frias', 3, 1, 1),
(87, 'Frappé cookies n cream Entero', 75, 'Bebidas frias', 2, 1, 1),
(88, 'Frappé cookies n cream Deslactosado', 75, 'Bebidas frias', 3, 1, 1),
(89, 'Frappé matcha Entero', 80, 'Bebidas frias', 2, 1, 1),
(90, 'Frappé matcha Deslactosado', 80, 'Bebidas frias', 3, 1, 1),
(91, 'Frappé espresso Entero', 75, 'Bebidas frias', 2, 1, 1),
(92, 'Frappé espresso Deslactosado', 75, 'Bebidas frias', 3, 1, 1),
(93, 'Iced tea Negro', 45, 'Bebidas frias', 7, 1, 1),
(94, 'Iced tea Limón', 45, 'Bebidas frias', 8, 1, 1),
(95, 'Limonada', 40, 'Bebidas frias', 1, 1, 1),
(96, 'Té Manzanilla', 35, 'Bebidas calientes', 6, 1, 1),
(97, 'Té Negro', 35, 'Bebidas calientes', 7, 1, 1),
(98, 'Té Limón', 35, 'Bebidas calientes', 8, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `promocion`
--

CREATE TABLE `promocion` (
  `idPromo` int(11) NOT NULL,
  `nombrePromo` varchar(255) NOT NULL,
  `imagen_url` varchar(255) DEFAULT NULL,
  `codigo_promo` varchar(50) NOT NULL,
  `condiciones` text DEFAULT NULL,
  `tipo_descuento` enum('porcentaje','fijo') NOT NULL,
  `valor_descuento` decimal(10,2) NOT NULL,
  `fechaInicio` date NOT NULL,
  `fechaFin` date DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `resena`
--

CREATE TABLE `resena` (
  `idr` int(11) NOT NULL,
  `userid` int(11) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `comentario` text DEFAULT NULL,
  `producto` int(11) DEFAULT NULL,
  `estrellas` int(11) DEFAULT NULL CHECK (`estrellas` between 0 and 5)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL,
  `rolename` varchar(50) NOT NULL,
  `currentusers` int(11) DEFAULT 0,
  `status` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sabores`
--

CREATE TABLE `sabores` (
  `id_sabor` int(11) NOT NULL,
  `nombre_sabor` varchar(50) NOT NULL,
  `precio_extra` decimal(5,2) NOT NULL DEFAULT 0.00,
  `tipo_modificador` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `sabores`
--

INSERT INTO `sabores` (`id_sabor`, `nombre_sabor`, `precio_extra`, `tipo_modificador`) VALUES
(1, 'Sin Modificador', 0.00, 'BASE'),
(2, 'Leche Entera', 0.00, 'LECHE_VACA'),
(3, 'Leche Deslactosada', 5.00, 'LECHE_VACA'),
(4, 'Leche de Avena', 10.00, 'LECHE_VEGETAL'),
(5, 'Leche de Almendra', 10.00, 'LECHE_VEGETAL'),
(6, 'Té Manzanilla', 0.00, 'TÉ'),
(7, 'Té Negro', 0.00, 'TÉ'),
(8, 'Té Limón', 0.00, 'TÉ');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tamanos`
--

CREATE TABLE `tamanos` (
  `tamano_id` int(11) NOT NULL,
  `nombre_tamano` varchar(50) NOT NULL,
  `precio_aumento` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tamanos`
--

INSERT INTO `tamanos` (`tamano_id`, `nombre_tamano`, `precio_aumento`) VALUES
(1, 'Chico', 0.00),
(2, 'Mediano', 10.00),
(3, 'Grande', 15.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `userid` int(11) NOT NULL,
  `profilescreen` varchar(255) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(20) NOT NULL CHECK (char_length(`password`) between 1 and 8),
  `role` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `archived` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `auditlogs`
--
ALTER TABLE `auditlogs`
  ADD PRIMARY KEY (`id_au`),
  ADD KEY `username` (`username`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`nombrecategoria`);

--
-- Indices de la tabla `cortes_caja`
--
ALTER TABLE `cortes_caja`
  ADD PRIMARY KEY (`id_corte_caja`);

--
-- Indices de la tabla `movimientos`
--
ALTER TABLE `movimientos`
  ADD PRIMARY KEY (`id_movimiento`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`idp`),
  ADD KEY `categoria` (`categoria`),
  ADD KEY `sabor` (`sabor`),
  ADD KEY `producto_ibfk_3` (`tamano_defecto`);

--
-- Indices de la tabla `promocion`
--
ALTER TABLE `promocion`
  ADD PRIMARY KEY (`idPromo`),
  ADD UNIQUE KEY `idx_codigo_promo` (`codigo_promo`);

--
-- Indices de la tabla `resena`
--
ALTER TABLE `resena`
  ADD PRIMARY KEY (`idr`),
  ADD KEY `userid` (`userid`),
  ADD KEY `username` (`username`),
  ADD KEY `producto` (`producto`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `sabores`
--
ALTER TABLE `sabores`
  ADD PRIMARY KEY (`id_sabor`);

--
-- Indices de la tabla `tamanos`
--
ALTER TABLE `tamanos`
  ADD PRIMARY KEY (`tamano_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`userid`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `role` (`role`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `auditlogs`
--
ALTER TABLE `auditlogs`
  MODIFY `id_au` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cortes_caja`
--
ALTER TABLE `cortes_caja`
  MODIFY `id_corte_caja` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `movimientos`
--
ALTER TABLE `movimientos`
  MODIFY `id_movimiento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `idp` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- AUTO_INCREMENT de la tabla `promocion`
--
ALTER TABLE `promocion`
  MODIFY `idPromo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `resena`
--
ALTER TABLE `resena`
  MODIFY `idr` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sabores`
--
ALTER TABLE `sabores`
  MODIFY `id_sabor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `tamanos`
--
ALTER TABLE `tamanos`
  MODIFY `tamano_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `auditlogs`
--
ALTER TABLE `auditlogs`
  ADD CONSTRAINT `auditlogs_ibfk_1` FOREIGN KEY (`username`) REFERENCES `usuarios` (`username`);

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `producto_ibfk_1` FOREIGN KEY (`categoria`) REFERENCES `categorias` (`nombrecategoria`),
  ADD CONSTRAINT `producto_ibfk_2` FOREIGN KEY (`sabor`) REFERENCES `sabores` (`id_sabor`),
  ADD CONSTRAINT `producto_ibfk_3` FOREIGN KEY (`tamano_defecto`) REFERENCES `tamanos` (`tamano_id`);

--
-- Filtros para la tabla `resena`
--
ALTER TABLE `resena`
  ADD CONSTRAINT `resena_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `usuarios` (`userid`),
  ADD CONSTRAINT `resena_ibfk_2` FOREIGN KEY (`username`) REFERENCES `usuarios` (`username`),
  ADD CONSTRAINT `resena_ibfk_3` FOREIGN KEY (`producto`) REFERENCES `producto` (`idp`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`role`) REFERENCES `roles` (`id_rol`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
