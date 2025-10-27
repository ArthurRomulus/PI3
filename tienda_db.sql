-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-10-2025 a las 01:36:14
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
-- Estructura de tabla para la tabla `administradores`
--

CREATE TABLE `administradores` (
  `userid` int(11) NOT NULL,
  `numero_admin` varchar(20) NOT NULL,
  `nombre_completo` varchar(100) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `telefono_emergencia` varchar(15) NOT NULL,
  `direccion` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id_categoria` int(11) NOT NULL,
  `nombrecategoria` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id_categoria`, `nombrecategoria`) VALUES
(1, 'Bebidas calientes'),
(2, 'Bebidas frias'),
(3, 'Cafés'),
(4, 'Comida'),
(5, 'Frappés'),
(6, 'Panes'),
(7, 'Postres'),
(8, 'Sin café'),
(9, 'Temporada'),
(10, 'Tés');

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
-- Estructura de tabla para la tabla `empleados_cajeros`
--

CREATE TABLE `empleados_cajeros` (
  `userid` int(11) NOT NULL,
  `numero_empleado` varchar(20) NOT NULL,
  `nombre_completo` varchar(100) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `telefono_emergencia` varchar(15) NOT NULL,
  `direccion` varchar(255) NOT NULL
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
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `idp` int(11) NOT NULL,
  `namep` varchar(50) NOT NULL,
  `ruta_imagen` varchar(255) DEFAULT NULL,
  `precio` int(11) NOT NULL CHECK (`precio` >= 0),
  `categoria` varchar(50) DEFAULT NULL,
  `sabor` int(11) DEFAULT NULL,
  `tamano_defecto` int(11) NOT NULL DEFAULT 1,
  `VENTAS` int(11) NOT NULL DEFAULT 0,
  `STOCK` int(11) NOT NULL DEFAULT 0,
  `descripcion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`idp`, `namep`, `ruta_imagen`, `precio`, `categoria`, `sabor`, `tamano_defecto`, `VENTAS`, `STOCK`, `descripcion`) VALUES
(51, 'Americano', '../../Images/CafeAmer.png', 40, 'Bebidas frias', 1, 1, 1, 0, NULL),
(52, 'Espresso', '../../Images/Espresso.png', 40, 'Bebidas calientes', 1, 1, 0, 1, NULL),
(53, 'Macchiato', '../../Images/Macchi.png', 50, 'Bebidas calientes', 1, 1, 0, 0, NULL),
(54, 'Capucchino Entero', '../../Images/CafeCapu.png', 45, 'Bebidas calientes', 2, 1, 0, 0, NULL),
(55, 'Lechero (Entera)', '../../Images/Lechero.png', 42, 'Bebidas calientes', 2, 1, 0, 0, NULL),
(57, 'Moka', '../../Images/Moka.png', 50, 'Bebidas calientes', 1, 1, 0, 0, NULL),
(58, 'Matcha2', '../../Images/Matchalatte.png', 65, 'Cafés', 1, 1, 0, 0, NULL),
(59, 'Capucchino Deslactos', '../../Images/CafeCapu.png', 55, 'Bebidas calientes', 3, 1, 0, 0, NULL),
(60, 'Irlandés', '../../Images/Irlandes.png', 70, 'Bebidas calientes', 1, 1, 0, 0, NULL),
(61, 'Latte Entero', '../../Images/Latte.png', 45, 'Bebidas calientes', 2, 1, 0, 0, NULL),
(62, 'Latter Deslactosado', '../../Images/Latte.png', 42, 'Bebidas calientes', 3, 1, 0, 0, NULL),
(63, 'Latte Avena', '../../Images/Latte.png', 40, 'Bebidas calientes', 4, 1, 0, 0, NULL),
(64, 'Latte Almendra', '../../Images/Latte.png', 45, 'Bebidas calientes', 5, 1, 0, 0, NULL),
(65, 'Carajillo', '../../Images/Carajillo.png', 70, 'Bebidas calientes', 1, 1, 0, 0, NULL),
(66, 'Matchalatte', '../../Images/Matchalatte.png', 60, 'Bebidas calientes', 1, 1, 0, 0, NULL),
(67, 'Doble', '../../Images/EspreDoble.png', 55, 'Bebidas calientes', 1, 1, 0, 0, NULL),
(68, 'Chocolate caliente (Entero)', '../../Images/ChocoCali.png', 30, 'Bebidas calientes', 2, 1, 0, 0, NULL),
(69, 'Chocolate caliente Deslactosad', '../../Images/ChocoCali.png', 30, 'Bebidas calientes', 3, 1, 0, 0, NULL),
(80, 'Chocolate caliente Avena', '../../Images/ChocoCali.png', 30, 'Bebidas calientes', 4, 1, 0, 0, NULL),
(81, 'Frappé clásico Entero', '../../Images/FrappeClasic.png', 60, 'Bebidas frias', 2, 1, 0, 0, NULL),
(82, 'Frappé clásico Deslactosado', '../../Images/FrappeClasic.png', 60, 'Bebidas frias', 3, 1, 0, 0, NULL),
(83, 'Frappé moka Entero', '../../Images/FrappMoka.png', 65, 'Bebidas frias', 2, 1, 0, 0, NULL),
(84, 'Frappé moka Deslactosado', '../../Images/FrappMoka.png', 65, 'Bebidas frias', 3, 1, 0, 0, NULL),
(85, 'Frappé caramel Entero', '../../Images/FrapCaramel.png', 65, 'Bebidas frias', 2, 1, 0, 0, NULL),
(86, 'Frappé caramel Deslactosado', '../../Images/FrapCaramel.png', 65, 'Bebidas frias', 3, 1, 0, 0, NULL),
(87, 'Frappé cookies n cream Entero', '../../Images/FrappCnC.png', 75, 'Bebidas frias', 2, 1, 0, 0, NULL),
(88, 'Frappé cookies n cream Deslactosado', '../../Images/FrappeClasic.png', 75, 'Bebidas frias', 3, 1, 0, 0, NULL),
(89, 'Frappé matcha Entero', '../../Images/FrappMatcha.png', 80, 'Bebidas frias', 2, 1, 0, 0, NULL),
(90, 'Frappé matcha Deslactosado', '../../Images/FrappMatcha.png', 80, 'Bebidas frias', 3, 1, 0, 0, NULL),
(91, 'Frappé espresso Entero', '../../Images/FrappEspresso.png', 75, 'Bebidas frias', 2, 1, 0, 0, NULL),
(92, 'Frappé espresso Deslactosado', '../../Images/FrappEspresso.png', 75, 'Bebidas frias', 3, 1, 0, 0, NULL),
(93, 'Iced tea Negro', '../../Images/IcedTeaBlack.png', 45, 'Bebidas frias', 7, 1, 0, 0, NULL),
(94, 'Iced tea Limón', '../../Images/IcedTea.png', 45, 'Bebidas frias', 8, 1, 0, 0, NULL),
(95, 'Limonada', '../../Images/Limonadas.png', 40, 'Bebidas frias', 1, 1, 0, 0, NULL),
(96, 'Té Manzanilla', '../../Images/Te.png', 35, 'Bebidas calientes', 6, 1, 0, 0, NULL),
(97, 'Té Negro', '../../Images/TeCali.png', 35, 'Bebidas calientes', 7, 1, 0, 0, NULL),
(98, 'Té Limón', '../../Images/Te.png', 35, 'Bebidas calientes', 8, 1, 0, 0, NULL),
(100, 'the cofi', '../../Images/68fbfd1963946_Frappé matcha.png', 69, NULL, 1, 1, 0, 0, NULL),
(102, 'cofff', '../../Images/68fbfe1aa4b68_Cafe carajillo.png', 67, NULL, 2, 1, 0, 0, NULL),
(103, 'theex', '../../Images/68fbfe420c7d7_Cafe latte.png', 67, NULL, 2, 1, 0, 0, NULL),
(104, 'Cafeee', '../../Images/68fbffd94b439_Cafe bombon.png', 67, NULL, 1, 1, 0, 0, NULL),
(106, 'late', '../../Images/68fc00971d167_Cafe latte.png', 45, NULL, 2, 1, 0, 0, NULL),
(108, 'Frappé', '../../Images/68fc09e70216a_Cafe latte.png', 69, 'Array', 2, 1, 0, 0, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto_categorias`
--

CREATE TABLE `producto_categorias` (
  `idp` int(11) NOT NULL,
  `id_categoria` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `producto_categorias`
--

INSERT INTO `producto_categorias` (`idp`, `id_categoria`) VALUES
(102, 4),
(103, 4),
(104, 6),
(106, 2),
(106, 3),
(108, 2),
(108, 3),
(108, 4);

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

--
-- Volcado de datos para la tabla `promocion`
--

INSERT INTO `promocion` (`idPromo`, `nombrePromo`, `imagen_url`, `codigo_promo`, `condiciones`, `tipo_descuento`, `valor_descuento`, `fechaInicio`, `fechaFin`, `activo`, `fecha_creacion`) VALUES
(1, '123', '../img/1761140857_HsetxCc5.jpg', '68f8e0794c5a3', '123', 'porcentaje', 123.00, '0123-03-12', '0123-03-12', 1, '2025-10-22 13:47:37');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `resena`
--

CREATE TABLE `resena` (
<<<<<<< HEAD
  `idr` int(11) NOT NULL,
  `userid` int(11) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `comentario` text DEFAULT NULL,
  `producto` int(11) DEFAULT NULL,
  `estrellas` int(11) DEFAULT NULL CHECK (`estrellas` between 0 and 5),
  `date` date NOT NULL DEFAULT current_timestamp()
=======
  `idr` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `nombre` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `comentario` text COLLATE utf8mb4_general_ci,
  `calificacion` int(11) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `imagen_url` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `etiquetas` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `likes` int(11) DEFAULT 0,
  `parent_id` int(11) NULL DEFAULT NULL
>>>>>>> karol
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `resena`
--

INSERT INTO `resena` (`idr`, `userid`, `username`, `comentario`, `producto`, `estrellas`, `date`) VALUES
(123, 4, '123', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.\n', 51, 4, '2025-10-15');

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
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` int(11) DEFAULT 1,
  `status` tinyint(1) DEFAULT 1,
  `archived` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`userid`, `profilescreen`, `username`, `email`, `password`, `role`, `status`, `archived`) VALUES
(2, '../../Images/DefaultProfile.png', 'mparra8@ucol.mx', 'mparra8@ucol.mx', '$2y$10$YDKyT8b3fa3CXImSQ77cKuEUKik2AiqR1ZguAjma.VQLmACkuLmr2', 4, 1, 0),
(4, '../../Images/OIP.webp', 'mparra321', 'miguepg06@gmail.com', '$2y$10$1coSCtNYm3JNGGmq3rJ2iefFVqsz.oPy1zlw5wBDw2kUe5UfSgbb6', 2, 1, 0),
(8, NULL, 'cajero', 'cajero@gmail.com', '$2y$10$P100IYAw.svkWdQQBJTb7ug3pmplcd/q0lZ68fuAafQgw4SmmHX5e', 1, 1, 0),
(9, '../../Images/Profiles/9_68fba03670427.png', 'vc', 'vc@gmail.com', '$2y$10$QeQ4zKTvgs3fXhBg2aOpPOFADLMBnZQXNcXviq5iQTMK24uFBTV06', 2, 1, 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administradores`
--
ALTER TABLE `administradores`
  ADD PRIMARY KEY (`userid`),
  ADD UNIQUE KEY `numero_admin` (`numero_admin`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `cortes_caja`
--
ALTER TABLE `cortes_caja`
  ADD PRIMARY KEY (`id_corte_caja`);

--
-- Indices de la tabla `empleados_cajeros`
--
ALTER TABLE `empleados_cajeros`
  ADD PRIMARY KEY (`userid`),
  ADD UNIQUE KEY `numero_empleado` (`numero_empleado`);

--
-- Indices de la tabla `movimientos`
--
ALTER TABLE `movimientos`
  ADD PRIMARY KEY (`id_movimiento`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`idp`),
  ADD KEY `categoria` (`categoria`),
  ADD KEY `sabor` (`sabor`),
  ADD KEY `producto_ibfk_3` (`tamano_defecto`);

--
-- Indices de la tabla `producto_categorias`
--
ALTER TABLE `producto_categorias`
  ADD PRIMARY KEY (`idp`,`id_categoria`),
  ADD KEY `id_categoria` (`id_categoria`);

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
  ADD UNIQUE KEY `idr` (`idr`),
  ADD UNIQUE KEY `idr_2` (`idr`),
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
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `idp` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT de la tabla `promocion`
--
ALTER TABLE `promocion`
  MODIFY `idPromo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `resena`
--
ALTER TABLE `resena`
  MODIFY `idr` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=127;

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
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `administradores`
--
ALTER TABLE `administradores`
  ADD CONSTRAINT `administradores_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `usuarios` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `empleados_cajeros`
--
ALTER TABLE `empleados_cajeros`
  ADD CONSTRAINT `empleados_cajeros_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `usuarios` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `producto_categorias`
--
ALTER TABLE `producto_categorias`
  ADD CONSTRAINT `producto_categorias_ibfk_1` FOREIGN KEY (`idp`) REFERENCES `productos` (`idp`) ON DELETE CASCADE,
  ADD CONSTRAINT `producto_categorias_ibfk_2` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id_categoria`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
