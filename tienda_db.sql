-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 22-10-2025 a las 05:35:21
-- Versión del servidor: 8.0.17
-- Versión de PHP: 7.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
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
  `descripcion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `nombrecategoria` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`nombrecategoria`) VALUES
('Bebidas calientes'),
('Bebidas frias'),
('Cafés'),
('Comida'),
('Frappés'),
('Panes'),
('Postres'),
('Sin café'),
('Temporada');

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
  `estado` enum('abierto','cerrado') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientos`
--

CREATE TABLE `movimientos` (
  `id_movimiento` int(11) NOT NULL,
  `id_corte_caja` int(11) NOT NULL,
  `tipo_movimiento` enum('venta','gasto','devolucion','ingreso_inicial','retiro') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `metodo_pago` enum('efectivo','tarjeta','transferencia') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `descripcion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha_hora` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `idp` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `namep` varchar(100) NOT NULL,
  `descripcion_producto` varchar(255) DEFAULT NULL,
  `ruta_imagen` varchar(255) NOT NULL,
  `precio` decimal(6,2) NOT NULL,
  `categoria` varchar(50) NOT NULL,
  `sabor` int(11) DEFAULT NULL,
  `tamano_defecto` int(11) DEFAULT '1',
  `status` tinyint(1) DEFAULT '1',
  `stock` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`idp`, `namep`, `descripcion_producto`, `ruta_imagen`, `precio`, `categoria`, `sabor`, `tamano_defecto`, `status`, `stock`) VALUES
('00001', 'Café Americano', '80% Agua caliente, 20% Espresso', '../../Images/CafeAmer.png', '40.00', 'Bebidas calientes', NULL, 1, 1, 25),
('00003', 'Café Capuchino', '33% Espresso, 33% Leche, 33% Espuma de leche', '../../Images/CafeCapu.png', '45.00', 'Bebidas calientes', NULL, 1, 1, 18),
('00004', 'Café Carajillo', '60% Café con espresso, 40% licor', '../../Images/Carajillo.png', '70.00', 'Bebidas calientes', NULL, 1, 1, 15),
('00005', 'Café Espresso', '100% Café espresso concentrado con crema', '../../Images/Espresso.png', '40.00', 'Bebidas calientes', NULL, 1, 1, 22),
('00006', 'Café Latte', '20% Espresso, 60% Leche caliente, 20% Espuma ligera', '../../Images/Latte.png', '45.00', 'Bebidas calientes', NULL, 1, 1, 16),
('00007', 'Café Lechero', '30% Café, 70% Leche caliente', '../../Images/Lechero.png', '42.00', 'Bebidas calientes', NULL, 1, 1, 14),
('00009', 'Chocolate Caliente', '70% Leche caliente, 30% Chocolate', '../../Images/ChocoCali.png', '30.00', 'Bebidas calientes', NULL, 1, 1, 20),
('00010', 'Té Caliente', '90% Agua caliente, 10% Hojas de té', '../../Images/Te.png', '35.00', 'Bebidas calientes', NULL, 1, 1, 30),
('00110', 'Café Mocca', '30% espresso, 50% leche, 20% chocolate', '../../Images/Moka.png', '50.00', 'Bebidas calientes', NULL, 1, 1, 20),
('00111', 'Café Macchiato', '80% espresso, 20% espuma de leche', '../../Images/Macchi.png', '50.00', 'Bebidas calientes', NULL, 1, 1, 18),
('00112', 'Frappé Caramel', '35% café frío, 35% leche, 20% hielo, 10% sirope de caramelo', '../../Images/FrapCaramel.png', '65.00', 'Bebidas frias', NULL, 1, 1, 15),
('00113', 'Frappé Clásico', '40% café frío, 40% leche, 20% hielo + crema batida', '../../Images/FrappeClasic.png', '60.00', 'Bebidas frias', NULL, 1, 1, 25),
('00114', 'Frappé Cookies and Cream', '30% leche, 35% crema, 30% hielo, 5% galleta', '../../Images/FrappCnC.png', '75.00', 'Bebidas frias', NULL, 1, 1, 14),
('00115', 'Frappé Espresso', '50% espresso, 30% leche, 20% hielo', '../../Images/FrappEspresso.png', '75.00', 'Bebidas frias', NULL, 1, 1, 16),
('00116', 'Frappé Matcha', '30% matcha endulzado, 40% leche, 30% hielo', '../../Images/FrappMatcha.png', '80.00', 'Bebidas frias', NULL, 1, 1, 12),
('00117', 'Frappé Moka', '40% café frío, 30% leche, 20% hielo, 10% chocolate', '../../Images/FrappMoka.png', '65.00', 'Bebidas frias', NULL, 1, 1, 22),
('00118', 'Café Irlandés (frío)', '10% crema batida, 60% café, 30% whiskey', '../../Images/Irlandes.png', '70.00', 'Bebidas calientes', NULL, 1, 1, 10),
('00120', 'Té Frío', '80% té infusionado frío, 15% hielo, 5% endulzante', '../../Images/IcedTeaBlack.png', '45.00', 'Bebidas frias', NULL, 1, 1, 30),
('00901', 'Cappuccino', 'Bolsa de 250 g con notas de cacao y toque cremoso.', '../../Images/cappuccinobag.png', '75.00', 'Bebidas calientes', NULL, 1, 1, 25),
('00902', 'Black Coffee', 'Tostado intenso 100% arábica, aroma profundo.', '../../Images/blackcoffeebag.png', '80.00', 'Bebidas calientes', NULL, 1, 1, 30),
('00903', 'Pods', 'Cápsulas naturales de fibra para espresso o moka.', '../../Images/podsbag.png', '85.00', 'Bebidas calientes', NULL, 1, 1, 20),
('00904', 'Mokka', 'Mezcla artesanal con aroma suave y cuerpo balanceado.', '../../Images/mokkabag.png', '70.00', 'Bebidas calientes', NULL, 1, 1, 25);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `promocion`
--

CREATE TABLE `promocion` (
  `idPromo` int(11) NOT NULL,
  `nombrePromo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `imagen_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `codigo_promo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `condiciones` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `tipo_descuento` enum('porcentaje','fijo') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `valor_descuento` decimal(10,2) NOT NULL,
  `fechaInicio` date NOT NULL,
  `fechaFin` date DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `resena`
--

CREATE TABLE `resena` (
  `idr` int(11) NOT NULL,
  `userid` int(11) DEFAULT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `comentario` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `producto` int(11) DEFAULT NULL,
  `estrellas` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL,
  `rolename` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `currentusers` int(11) DEFAULT '0',
  `status` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `rolename`, `currentusers`, `status`) VALUES
(1, 'defaultuser', 0, 1),
(2, 'cajero', 0, 1),
(3, 'Gerente', 0, 1),
(4, 'Administrator', 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sabores`
--

CREATE TABLE `sabores` (
  `id_sabor` int(11) NOT NULL,
  `nombre_sabor` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `precio_extra` decimal(5,2) NOT NULL DEFAULT '0.00',
  `tipo_modificador` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `sabores`
--

INSERT INTO `sabores` (`id_sabor`, `nombre_sabor`, `precio_extra`, `tipo_modificador`) VALUES
(1, 'Sin Modificador', '0.00', 'BASE'),
(2, 'Leche Entera', '0.00', 'LECHE_VACA'),
(3, 'Leche Deslactosada', '5.00', 'LECHE_VACA'),
(4, 'Leche de Avena', '10.00', 'LECHE_VEGETAL'),
(5, 'Leche de Almendra', '10.00', 'LECHE_VEGETAL'),
(6, 'Té Manzanilla', '0.00', 'TÉ'),
(7, 'Té Negro', '0.00', 'TÉ'),
(8, 'Té Limón', '0.00', 'TÉ');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tamanos`
--

CREATE TABLE `tamanos` (
  `tamano_id` int(11) NOT NULL,
  `nombre_tamano` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `precio_aumento` decimal(10,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tamanos`
--

INSERT INTO `tamanos` (`tamano_id`, `nombre_tamano`, `precio_aumento`) VALUES
(1, 'Chico', '0.00'),
(2, 'Mediano', '10.00'),
(3, 'Grande', '15.00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `userid` int(11) NOT NULL,
  `profilescreen` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `role` int(11) DEFAULT '1',
  `status` tinyint(1) DEFAULT '1',
  `archived` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`userid`, `profilescreen`, `username`, `email`, `password`, `role`, `status`, `archived`) VALUES
(4, '../../Images/OIP.webp', 'mparra321', 'miguepg06@gmail.com', '$2y$10$1coSCtNYm3JNGGmq3rJ2iefFVqsz.oPy1zlw5wBDw2kUe5UfSgbb6', 2, 1, 0),
(5, '../../Images/DefaultProfile.png', 'mparra8@ucol.mx', 'mparra8@ucol.mx', '$2y$10$YDKyT8b3fa3CXImSQ77cKuEUKik2AiqR1ZguAjma.VQLmACkuLmr2', 4, 1, 0);

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
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`idp`);

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
  ADD PRIMARY KEY (`userid`);

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
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
