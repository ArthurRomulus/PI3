-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 06-10-2025 a las 05:42:54
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
  `descripcion` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `username` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `nombrecategoria` varchar(50) COLLATE utf8mb4_general_ci NOT NULL
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
  `estado` enum('abierto','cerrado') COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientos`
--

CREATE TABLE `movimientos` (
  `id_movimiento` int(11) NOT NULL,
  `id_corte_caja` int(11) NOT NULL,
  `tipo_movimiento` enum('venta','gasto','devolucion','ingreso_inicial','retiro') COLLATE utf8mb4_general_ci NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `metodo_pago` enum('efectivo','tarjeta','transferencia') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `descripcion` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha_hora` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `idp` int(11) NOT NULL,
  `namep` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `precio` int(11) NOT NULL,
  `categoria` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sabor` int(11) DEFAULT NULL,
  `tamano_defecto` int(11) NOT NULL DEFAULT '1',
  `status` tinyint(1) DEFAULT '1'
) ;

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
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` char(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre_producto` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion_producto` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `foto_producto` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `precio_producto` decimal(10,2) NOT NULL,
  `cantidadProducto` int(11) NOT NULL DEFAULT '0',
  `categoria` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `nombre_producto`, `descripcion_producto`, `foto_producto`, `precio_producto`, `cantidadProducto`, `categoria`) VALUES
('00001', 'Café Americano', '80% Agua caliente, 20% Espresso', 'assest/drinks/Cafe americano.png', '40.00', 25, 'bebidas_calientes'),
('00002', 'Café Bombón', '50% Espresso, 50% Leche condensada', 'assest/drinks/Cafe bombon.png', '40.00', 20, 'bebidas_calientes'),
('00003', 'Café Capuchino', '33% Espresso, 33% Leche, 33% Espuma de leche', 'assest/drinks/Cafe capuchino.png', '40.00', 18, 'bebidas_calientes'),
('00004', 'Café Carajillo', '60% Café con espresso, 40% licor', 'assest/drinks/Cafe carajillo.png', '50.00', 15, 'bebidas_calientes'),
('00005', 'Café Espresso', '100% Café espresso concentrado con crema', 'assest/drinks/Cafe expresso.png', '60.00', 22, 'bebidas_calientes'),
('00006', 'Café Latte', '20% Espresso, 60% Leche caliente, 20% Espuma ligera', 'assest/drinks/Cafe latte.png', '70.00', 16, 'bebidas_calientes'),
('00007', 'Café Lechero', '30% Café, 70% Leche caliente', 'assest/drinks/Cafe lechero.png', '60.00', 14, 'bebidas_calientes'),
('00008', 'Café Lungo', 'Espresso largo, extracción más prolongada', 'assest/drinks/Cafe lungo.png', '50.00', 12, 'bebidas_calientes'),
('00009', 'Chocolate Caliente', '70% Leche caliente, 30% Chocolate', 'assest/drinks/Chocolate caliente.png', '40.00', 20, 'bebidas_calientes'),
('00010', 'Té Caliente', '90% Agua caliente, 10% Hojas de té', 'assest/drinks/Te caliente.png', '20.00', 30, 'bebidas_calientes'),
('00011', 'Café Vienés', '50% Espresso, 50% Crema batida', 'assest/drinks/Cafe vienes.png', '50.00', 10, 'bebidas_calientes'),
('00012', 'Café Ristretto', 'Espresso más concentrado, menor volumen', 'assest/drinks/Cafe ristretto.png', '45.00', 18, 'bebidas_calientes'),
('00110', 'Café Mocca', '30% espresso, 50% leche, 20% chocolate', 'assest/drinks/Cafe mocca.png', '60.00', 20, 'bebidas_frias'),
('00111', 'Café Macchiato', '80% espresso, 20% espuma de leche', 'assest/drinks/Cafe macchiato.png', '60.00', 18, 'bebidas_frias'),
('00112', 'Frappé Caramel', '35% café frío, 35% leche, 20% hielo, 10% sirope de caramelo', 'assest/drinks/Frappé caramel.png', '55.00', 15, 'bebidas_frias'),
('00113', 'Frappé Clásico', '40% café frío, 40% leche, 20% hielo + crema batida', 'assest/drinks/Frappé clasico.png', '60.00', 25, 'bebidas_frias'),
('00114', 'Frappé Cookies and Cream', '30% leche, 35% crema, 30% hielo, 5% galleta', 'assest/drinks/Frappé cookies and cream.png', '70.00', 14, 'bebidas_frias'),
('00115', 'Frappé Espresso', '50% espresso, 30% leche, 20% hielo', 'assest/drinks/Frappé espresso.png', '60.00', 16, 'bebidas_frias'),
('00116', 'Frappé Matcha', '30% matcha endulzado, 40% leche, 30% hielo', 'assest/drinks/Frappé matcha.png', '50.00', 12, 'bebidas_frias'),
('00117', 'Frappé Moka', '40% café frío, 30% leche, 20% hielo, 10% chocolate', 'assest/drinks/Frappé moka.png', '40.00', 22, 'bebidas_frias'),
('00118', 'Café Irlandés (frío)', '10% crema batida, 60% café, 30% whiskey', 'assest/drinks/Cafe irlandés.png', '50.00', 10, 'bebidas_frias'),
('00119', 'Café Frío', '60% café preparado frío, 30% hielo, 10% leche/azúcar', 'assest/food/iced coffee (expresso).png', '60.00', 26, 'bebidas_frias'),
('00120', 'Té Frío', '80% té infusionado frío, 15% hielo, 5% endulzante', 'assest/food/Te.png', '50.00', 30, 'bebidas_frias'),
('00221', 'Panini Serrano', '20% panini crujiente, 50% jamón serrano, 30% queso fundido', 'assest/food/panini_serrano.png', '110.00', 20, 'paninis'),
('00222', 'Sándwich de Serrano', '20% pan, 5% lechuga/tomate, 45% jamón serrano, 30% queso', 'assest/food/sandwich_serrano.png', '120.00', 20, 'paninis'),
('00223', 'Sándwich de Tocino', '20% pan, 10% vegetales, 40% tocino crujiente, 30% queso', 'assest/food/sandwich_tocino.png', '80.00', 20, 'paninis'),
('00224', 'Panini Pavo y Queso', '25% panini, 40% pavo, 35% queso', 'assest/food/panini_pavo_queso.png', '20.00', 20, 'paninis'),
('00225', 'Panini de Queso', '50% queso (mozzarella/cheddar), 50% pan', 'assest/food/panini_queso.png', '20.00', 20, 'paninis'),
('00226', 'Panini de Pollo', '40% pollo a la plancha, 30% queso, 20% panini, 10% verduras', 'assest/food/panini_pollo.png', '110.00', 20, 'paninis'),
('00227', 'Panini de Aguacate', '40% aguacate, 30% queso, 20% panini, 10% vegetales frescos', 'assest/food/panini_aguacate.png', '110.00', 20, 'paninis'),
('00228', 'Sándwich de Panela', '45% queso panela, 25% pan, 20% vegetales, 10% aderezo ligero', 'assest/food/sandwich_panela.png', '110.00', 20, 'paninis'),
('00229', 'Baguette de Serrano', '45% jamón serrano, 30% queso, 20% pan tipo baguette, 5% vegetales', 'assest/food/baguette_serrano.png', '110.00', 20, 'paninis'),
('00230', 'Panini Caprese', '40% jitomate fresco, 35% mozzarella, 15% albahaca, 10% panini', 'assest/food/panini_caprese.png', '110.00', 20, 'paninis'),
('00301', 'Brownies', '20% harina, 10% nueces, 40% chocolate/cacao, 30% mantequilla/azúcar', 'assest/desserts/brownies.png', '50.00', 20, 'postres'),
('00302', 'Galleta Casera', '20% huevo, 10% vainilla, 40% masa de galleta, 30% chispas de chocolate', 'assest/desserts/galleta_casera.png', '30.00', 20, 'postres'),
('00303', 'Pastel de chocolate', '40% bizcocho de chocolate, 30% betún, 20% huevo/harina base, 10% relleno extra', 'assest/desserts/pastel.png', '40.00', 20, 'postres'),
('00304', 'Bagel Clásico', '70% pan bagel, 20% ajonjolí, 10% relleno básico', 'assest/desserts/bagel_clasico.png', '60.00', 20, 'postres'),
('00401', 'Ensalada Caprese', '40% jitomate fresco, 35% queso mozzarella, 15% albahaca, 10% aderezo balsámico', 'assest/food/ensalada_caprese.png', '50.00', 20, 'ensaladas'),
('00402', 'Ensalada César', '20% crutones, 10% aderezo césar, 40% lechuga romana, 25% pollo a la plancha', 'assest/food/ensalada_cesar.png', '30.00', 20, 'ensaladas'),
('00403', 'Ensalada Rusa', '40% papa cocida en cubos, 25% zanahoria, 20% mayonesa, 10% huevo cocido', 'assest/food/ensalada_rusa.png', '40.00', 20, 'ensaladas'),
('00404', 'Ensalada Verde', '50% mezcla de hojas verdes, 20% pepino, 15% jitomate', 'assest/food/ensalada_verde.png', '60.00', 20, 'ensaladas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `promocion`
--

CREATE TABLE `promocion` (
  `idPromo` int(11) NOT NULL,
  `nombrePromo` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `imagen_url` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `codigo_promo` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `condiciones` text COLLATE utf8mb4_general_ci,
  `tipo_descuento` enum('porcentaje','fijo') COLLATE utf8mb4_general_ci NOT NULL,
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
  `username` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `comentario` text COLLATE utf8mb4_general_ci,
  `producto` int(11) DEFAULT NULL,
  `estrellas` int(11) DEFAULT NULL
) ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL,
  `rolename` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `currentusers` int(11) DEFAULT '0',
  `status` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sabores`
--

CREATE TABLE `sabores` (
  `id_sabor` int(11) NOT NULL,
  `nombre_sabor` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `precio_extra` decimal(5,2) NOT NULL DEFAULT '0.00',
  `tipo_modificador` varchar(20) COLLATE utf8mb4_general_ci NOT NULL
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
  `nombre_tamano` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
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
  `profilescreen` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `role` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `archived` tinyint(1) DEFAULT '0'
) ;

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
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`),
  ADD KEY `idx_productos_categoria` (`categoria`);

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
  MODIFY `idp` int(11) NOT NULL AUTO_INCREMENT;

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
