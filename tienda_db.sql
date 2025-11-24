-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 24-11-2025 a las 01:22:09
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
-- Estructura de tabla para la tabla `administradores`
--

CREATE TABLE `administradores` (
  `userid` int(11) NOT NULL,
  `numero_admin` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `nombre_completo` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `telefono` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  `telefono_emergencia` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  `direccion` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `administradores`
--

INSERT INTO `administradores` (`userid`, `numero_admin`, `nombre_completo`, `telefono`, `telefono_emergencia`, `direccion`) VALUES
(18, 'ADM001', 'Noisi', '3120000000', '3120000001', 'Colima, México');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id_categoria` int(11) NOT NULL,
  `nombrecategoria` varchar(50) COLLATE utf8mb4_general_ci NOT NULL
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
(10, 'Tés'),
(11, 'Ensaladas');

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

--
-- Volcado de datos para la tabla `cortes_caja`
--

INSERT INTO `cortes_caja` (`id_corte_caja`, `fecha_apertura`, `fecha_cierre`, `saldo_inicial`, `saldo_esperado`, `saldo_real_contado`, `diferencia`, `id_usuario_cierre`, `estado`) VALUES
(1, '2025-11-20 00:00:00', '2025-11-19 23:18:26', '200.00', '200.00', '1444.20', '1244.20', 20, 'cerrado'),
(2, '2025-11-19 23:18:26', '2025-11-20 08:27:21', '29.00', '75.40', '251.72', '176.32', 20, 'cerrado'),
(3, '2025-11-20 08:27:21', '2025-11-20 21:17:40', '3000.00', '4625.16', '5391.92', '766.76', 20, 'cerrado'),
(4, '2025-11-20 21:17:40', '2025-11-22 22:43:20', '100.00', '1312.20', '600.00', '-712.20', 20, 'cerrado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados_cajeros`
--

CREATE TABLE `empleados_cajeros` (
  `userid` int(11) NOT NULL,
  `numero_empleado` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `nombre_completo` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `telefono` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  `telefono_emergencia` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  `direccion` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empleados_cajeros`
--

INSERT INTO `empleados_cajeros` (`userid`, `numero_empleado`, `nombre_completo`, `telefono`, `telefono_emergencia`, `direccion`) VALUES
(20, '10000001', 'Victor Carrillo', '3122265453', '111', 'fesafesafesa');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `listboxes`
--

CREATE TABLE `listboxes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `listboxes`
--

INSERT INTO `listboxes` (`id`, `nombre`) VALUES
(1, 'Tipo de café'),
(2, 'Tipo de leche'),
(3, 'Topping'),
(4, 'Extras'),
(5, 'Sabor adicional'),
(6, 'Relleno'),
(7, 'Cobertura'),
(8, 'Sabor'),
(9, 'Tipo de sexito'),
(10, 'Tipo de muaa');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `listbox_opciones`
--

CREATE TABLE `listbox_opciones` (
  `id` int(11) NOT NULL,
  `listbox_id` int(11) NOT NULL,
  `valor` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `precio` decimal(10,2) DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `listbox_opciones`
--

INSERT INTO `listbox_opciones` (`id`, `listbox_id`, `valor`, `precio`) VALUES
(62, 1, 'Café Americano', '18.00'),
(63, 1, 'Latte', '22.00'),
(64, 1, 'Capuchino', '25.00'),
(65, 1, 'Cold Brew', '28.00'),
(66, 1, 'Té verde', '15.00'),
(67, 1, 'Té chai', '18.00'),
(68, 1, 'Frappé de Oreo', '24.00'),
(69, 1, 'Chocolate caliente', '20.00'),
(70, 2, 'Leche entera', '0.00'),
(71, 2, 'Leche deslactosada', '2.00'),
(72, 2, 'Leche de avena', '3.00'),
(73, 2, 'Leche de soya', '3.00'),
(74, 2, 'Leche de almendra', '4.00'),
(75, 2, 'Sin leche', '0.00'),
(76, 3, 'Canela', '3.00'),
(77, 3, 'Crema batida', '5.00'),
(78, 3, 'Chispas de chocolate', '4.00'),
(79, 3, 'Caramelo', '4.00'),
(80, 3, 'Sirope de vainilla', '3.00'),
(81, 3, 'Trozos de galleta', '4.00'),
(82, 4, 'Aderezo César', '5.00'),
(83, 4, 'Aderezo Ranch', '5.00'),
(84, 4, 'Vinagreta balsámica', '6.00'),
(85, 4, 'Salsa BBQ', '6.00'),
(86, 4, 'Miel mostaza', '5.00'),
(87, 5, 'Vainilla', '4.00'),
(88, 5, 'Avellana', '4.00'),
(89, 5, 'Caramelo', '4.00'),
(90, 5, 'Chocolate', '4.00'),
(91, 5, 'Menta', '5.00'),
(92, 5, 'Pumpkin Spice', '5.00'),
(93, 6, 'Chocolate', '10.00'),
(94, 6, 'Queso crema', '12.00'),
(95, 6, 'Dulce de leche', '14.00'),
(96, 6, 'Frutos rojos', '15.00'),
(97, 6, 'Vainilla', '12.00'),
(98, 7, 'Azúcar glas', '5.00'),
(99, 7, 'Ganache de chocolate', '8.00'),
(100, 7, 'Crema batida', '6.00'),
(101, 7, 'Frutas frescas', '7.00'),
(102, 7, 'Crutones', '4.00'),
(103, 7, 'Nueces', '5.00'),
(104, 8, 'Fresa', '20.00'),
(105, 8, 'Vainilla', '20.00'),
(106, 8, 'Chocolate', '22.00'),
(107, 8, 'Caramelo salado', '23.00'),
(108, 8, 'Menta navideña', '24.00'),
(109, 8, 'Matcha', '25.00'),
(110, 9, 'sexito con mua ', '3.00'),
(111, 9, 'sexito sin mua', '5.00'),
(112, 10, 'con lengua', '5.00'),
(113, 10, 'sin lengua', '2.00');

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
-- Estructura de tabla para la tabla `opciones_categoria`
--

CREATE TABLE `opciones_categoria` (
  `id_categoria` int(11) NOT NULL,
  `id_opcion_predefinida` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `opciones_categoria`
--

INSERT INTO `opciones_categoria` (`id_categoria`, `id_opcion_predefinida`) VALUES
(3, 1),
(3, 2),
(3, 3),
(3, 4),
(3, 5),
(3, 6),
(3, 7),
(3, 8),
(4, 9),
(4, 10),
(4, 11),
(5, 12),
(5, 13),
(5, 14),
(5, 15),
(6, 16),
(6, 17),
(6, 18),
(7, 19),
(7, 20),
(7, 21),
(8, 22),
(8, 23),
(8, 24),
(9, 25),
(9, 26),
(9, 27),
(10, 28),
(10, 29),
(10, 30),
(10, 31),
(10, 32);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `opciones_predefinidas`
--

CREATE TABLE `opciones_predefinidas` (
  `id_opcion_predefinida` int(11) NOT NULL,
  `nombre_opcion` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `valor` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `precio` decimal(10,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `opciones_predefinidas`
--

INSERT INTO `opciones_predefinidas` (`id_opcion_predefinida`, `nombre_opcion`, `valor`, `precio`) VALUES
(1, 'Tipo de café', 'Espresso', '15.00'),
(2, 'Tipo de café', 'Americano', '10.00'),
(3, 'Tipo de café', 'Capuchino', '20.00'),
(4, 'Tipo de leche', 'Entera', '10.00'),
(5, 'Tipo de leche', 'Deslactosada', '10.00'),
(6, 'Tipo de leche', 'Avena', '5.00'),
(7, 'Topping', 'Chocolate', '10.00'),
(8, 'Topping', 'Caramelo', '15.00'),
(9, 'Extras', 'Queso', '8.00'),
(10, 'Extras', 'Salsa', '14.00'),
(11, 'Extras', 'Vegetales', '20.00'),
(12, 'Topping', 'Chocolate', '8.00'),
(13, 'Topping', 'Caramelo', '12.00'),
(14, 'Sabor adicional', 'Vainilla', '10.00'),
(15, 'Sabor adicional', 'Fresa', '10.00'),
(16, 'Relleno', 'Chocolate', '15.00'),
(17, 'Relleno', 'Dulce de leche', '25.00'),
(18, 'Cobertura', 'Azúcar glas', '15.00'),
(19, 'Cobertura', 'Chocolate', '10.00'),
(20, 'Cobertura', 'Fresa', '13.00'),
(21, 'Extras', 'Nueces', '10.00'),
(22, 'Sabor', 'Frutilla', '9.00'),
(23, 'Sabor', 'Chocolate', '15.00'),
(24, 'Sabor', 'Vainilla', '12.00'),
(25, 'Topping', 'Canela', '10.00'),
(26, 'Topping', 'Nuez moscada', '10.00'),
(27, 'Sabor adicional', 'Calabaza', '9.00'),
(28, 'Tipo de té', 'Negro', '15.00'),
(29, 'Tipo de té', 'Manzanilla', '20.00'),
(30, 'Tipo de té', 'Limón', '10.00'),
(31, 'Sabor adicional', 'Miel', '12.00'),
(32, 'Sabor adicional', 'Jengibre', '14.00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id_pedido` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `estado` enum('Completado','En preparación','En espera') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'En espera',
  `metodo_pago` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `tipo_pedido` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `id_pago_stripe` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fecha_pedido` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sucursal` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id_pedido`, `userid`, `total`, `estado`, `metodo_pago`, `tipo_pedido`, `id_pago_stripe`, `fecha_pedido`, `sucursal`) VALUES
(1, 19, '139.20', '', 'Efectivo', 'En Local', NULL, '2025-11-17 05:30:01', NULL),
(2, 19, '85.84', '', 'Tarjeta', 'En Local', 'ch_3SUL7h6xsnAsFl7H0W0gofY0', '2025-11-17 05:37:10', NULL),
(3, 19, '70.76', '', 'Efectivo', 'En Local', NULL, '2025-11-17 05:37:58', NULL),
(4, 19, '85.84', '', 'Tarjeta', 'En Local', 'ch_3SULBg6xsnAsFl7H1EXY6AKu', '2025-11-17 05:41:17', NULL),
(5, 20, '696.00', '', 'Efectivo', 'En Local', NULL, '2025-11-20 04:46:25', NULL),
(6, 20, '80.04', '', 'Tarjeta', 'En Local', 'ch_3SVPm66xsnAsFl7H0QqBcDJe', '2025-11-20 04:47:18', NULL),
(7, 20, '46.40', '', 'Efectivo', 'En Local', NULL, '2025-11-20 05:00:16', NULL),
(8, 20, '482.56', '', 'Tarjeta', 'En Local', 'ch_3SVPz26xsnAsFl7H0TGbey4c', '2025-11-20 05:00:40', NULL),
(9, 20, '46.40', '', 'Efectivo', 'En Local', NULL, '2025-11-20 05:01:13', NULL),
(10, 20, '46.40', '', 'Efectivo', 'En Local', NULL, '2025-11-20 05:03:32', NULL),
(11, 20, '46.40', '', 'Efectivo', 'En Local', NULL, '2025-11-20 05:12:44', NULL),
(12, 20, '46.40', '', 'Efectivo', 'En Local', NULL, '2025-11-20 05:18:40', NULL),
(13, 20, '205.32', '', 'Tarjeta', 'En Local', 'ch_3SVYl66xsnAsFl7H1lfJEFiO', '2025-11-20 14:22:51', NULL),
(14, 20, '46.40', '', 'Efectivo', 'En Local', NULL, '2025-11-20 14:27:33', NULL),
(15, 20, '76.56', '', 'Efectivo', 'En Local', NULL, '2025-11-21 00:59:15', NULL),
(16, 20, '104.40', '', 'Tarjeta', 'En Local', 'ch_3SVj3T6xsnAsFl7H09apM661', '2025-11-21 01:22:30', NULL),
(17, 20, '139.20', '', 'Efectivo', 'En Local', NULL, '2025-11-21 01:22:58', NULL),
(18, 20, '417.60', '', 'Efectivo', 'En Local', NULL, '2025-11-21 01:28:06', NULL),
(19, 20, '365.40', '', 'Efectivo', 'En Local', NULL, '2025-11-21 01:44:44', NULL),
(20, 20, '349.16', '', 'Efectivo', 'En Local', NULL, '2025-11-21 02:09:12', NULL),
(21, 20, '49.88', '', 'Efectivo', 'En Local', NULL, '2025-11-21 03:10:39', NULL),
(22, 20, '446.60', '', 'Tarjeta', 'En Local', 'ch_3SVkkx6xsnAsFl7H0Gxpolb0', '2025-11-21 03:11:31', NULL),
(23, 20, '180.96', '', 'Efectivo', 'En Local', NULL, '2025-11-21 03:15:56', NULL),
(24, 20, '215.76', '', 'Tarjeta', 'En Local', 'ch_3SVkpd6xsnAsFl7H0WVRZGJm', '2025-11-21 03:16:20', NULL),
(25, 20, '254.04', '', 'Efectivo', 'En Local', NULL, '2025-11-21 03:33:07', NULL),
(26, 20, '556.80', '', 'Efectivo', 'En Local', NULL, '2025-11-21 04:06:16', NULL),
(27, 20, '80.04', '', 'Efectivo', 'En Local', NULL, '2025-11-21 13:47:04', NULL),
(28, 20, '121.80', '', 'Efectivo', 'En Local', NULL, '2025-11-21 14:02:21', NULL),
(29, 20, '672.00', '', 'Tarjeta', 'Online', 'ch_3SWO5X6xsnAsFl7H1s3KB7VE', '2025-11-22 21:11:25', NULL),
(30, 20, '1170.00', '', 'Tarjeta', 'Online', 'ch_3SWO9F6xsnAsFl7H05SB6XX6', '2025-11-22 21:15:15', NULL),
(31, 20, '250.00', '', 'Tarjeta', 'Online', 'ch_3SWOag6xsnAsFl7H0wRwcTQH', '2025-11-22 21:43:35', NULL),
(32, 20, '350.00', 'Completado', 'Tarjeta', 'Online', 'ch_3SWOgH6xsnAsFl7H1Sjrggk2', '2025-11-22 21:49:22', NULL),
(33, 20, '45.00', 'En preparación', 'Tarjeta', 'Online', 'ch_3SWP806xsnAsFl7H0PtI6REA', '2025-11-22 22:18:02', NULL),
(34, 20, '45.00', 'En preparación', 'Tarjeta', 'Online', 'ch_3SWPKp6xsnAsFl7H0FyKTAB5', '2025-11-22 22:31:16', NULL),
(35, 20, '180.00', 'En espera', 'Tarjeta', 'Online', 'ch_3SWQqv6xsnAsFl7H0h6kvJ9H', '2025-11-23 00:08:30', NULL),
(36, 20, '700.00', 'Completado', 'Tarjeta', 'Online', 'ch_3SWQzr6xsnAsFl7H0dVAPjln', '2025-11-23 00:17:44', NULL),
(37, 20, '120.00', 'Completado', 'Tarjeta', 'Online', 'ch_3SWRGV6xsnAsFl7H0NXBT8lm', '2025-11-23 00:34:56', NULL),
(38, 20, '60.00', 'Completado', 'Tarjeta', 'Online', 'ch_3SWRoM6xsnAsFl7H0HbqLoXG', '2025-11-23 01:09:54', NULL),
(39, 20, '1120.00', 'En preparación', 'Tarjeta', 'Online', 'ch_3SWS296xsnAsFl7H0bVue1So', '2025-11-23 01:24:10', NULL),
(40, 20, '199.52', 'Completado', 'Efectivo', 'En Local', NULL, '2025-11-23 04:38:18', NULL),
(41, 20, '278.40', 'En espera', 'Tarjeta', 'En Local', 'ch_3SWV9X6xsnAsFl7H11MIWQ4X', '2025-11-22 18:44:00', NULL),
(42, 20, '46.40', 'En espera', 'Efectivo', 'En Local', NULL, '2025-11-23 04:50:17', NULL),
(43, 20, '49.88', '', 'Efectivo', 'En Local', NULL, '2025-11-23 05:00:56', NULL),
(44, 20, '49.88', '', 'Tarjeta', 'En Local', 'ch_3SWVQA6xsnAsFl7H0prqrEyO', '2025-11-23 05:01:11', NULL),
(45, 20, '83.52', '', 'Efectivo', 'En Local', NULL, '2025-11-23 05:09:21', NULL),
(46, 20, '215.76', '', 'Tarjeta', 'En Local', 'ch_3SWVYo6xsnAsFl7H0ptgz6t1', '2025-11-23 05:10:06', NULL),
(47, 20, '42.00', '', 'Tarjeta', 'Online', 'ch_3SWVdr6xsnAsFl7H0AMZdbKu', '2025-11-23 05:15:19', NULL),
(48, 20, '84.00', '', 'Tarjeta', 'Online', 'ch_3SWVhb6xsnAsFl7H05lolBoY', '2025-11-23 05:19:11', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_items`
--

CREATE TABLE `pedido_items` (
  `id_pedido_item` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL DEFAULT '1',
  `precio_unitario` decimal(10,2) NOT NULL,
  `modificadores_desc` text COLLATE utf8mb4_general_ci,
  `producto_nombre` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedido_items`
--

INSERT INTO `pedido_items` (`id_pedido_item`, `id_pedido`, `id_producto`, `cantidad`, `precio_unitario`, `modificadores_desc`, `producto_nombre`) VALUES
(1, 1, 52, 2, '60.00', '250ML, Café Americano, Leche deslactosada', NULL),
(2, 2, 53, 1, '74.00', '250ML, Latte, Leche deslactosada', NULL),
(3, 3, 52, 1, '61.00', '250ML, Café Americano, Leche de avena', NULL),
(4, 4, 53, 1, '74.00', '250ML, Latte, Leche deslactosada', NULL),
(5, 5, 134, 6, '100.00', '500ML, ', NULL),
(6, 6, 54, 1, '69.00', '250ML, Chocolate caliente, Leche de almendra, (ssssssss)', NULL),
(7, 7, 52, 1, '40.00', '250ML, ', NULL),
(8, 8, 58, 4, '104.00', '1L, Menta navideña', NULL),
(9, 9, 52, 1, '40.00', '250ML, ', NULL),
(10, 10, 52, 1, '40.00', '250ML, ', NULL),
(11, 11, 52, 1, '40.00', '250ML, ', NULL),
(12, 12, 52, 1, '40.00', '250ML, ', NULL),
(13, 13, 53, 1, '81.00', '250ML, Cold Brew, Leche de avena', NULL),
(14, 13, 53, 1, '96.00', '1L, Cold Brew, Leche de avena', NULL),
(15, 14, 52, 1, '40.00', '250ML, ', NULL),
(16, 15, 54, 1, '66.00', '250ML, Café Americano, Leche de soya', NULL),
(17, 16, 51, 2, '45.00', '250ML, Crema batida', NULL),
(18, 17, 52, 3, '40.00', '250ML, ', NULL),
(19, 18, 52, 9, '40.00', '250ML, ', NULL),
(20, 19, 51, 7, '45.00', '250ML, Crema batida', NULL),
(21, 20, 51, 7, '43.00', '250ML, Canela', NULL),
(22, 21, 51, 1, '43.00', '250ML, Canela', NULL),
(23, 22, 52, 7, '55.00', '1L, ', NULL),
(24, 23, 53, 2, '78.00', '250ML, Capuchino, Leche de avena', NULL),
(25, 24, 53, 2, '93.00', '1L, Capuchino, Leche de avena, (aaaaaaaaaa)', NULL),
(26, 25, 54, 3, '73.00', '500ML, Café Americano, Leche entera', NULL),
(27, 26, 53, 6, '80.00', '250ML, Cold Brew, Leche deslactosada', NULL),
(28, 27, 54, 1, '69.00', '250ML, Latte, Leche deslactosada', NULL),
(29, 28, 149, 1, '105.00', '250ML, Aderezo César, (unos sentones)', NULL),
(30, 29, 55, 16, '42.00', 'Lechero (Entera) (Café Americano, Leche de almendra) - Chico Chico', NULL),
(31, 30, 58, 18, '65.00', 'Matcha2 (Caramelo salado) - Mediano Mediano', NULL),
(32, 31, 57, 5, '50.00', 'Moka (Café Americano, Leche de almendra) - Chico Chico', NULL),
(33, 32, 57, 7, '50.00', 'Moka (Café Americano, Leche de almendra) - Chico Chico', NULL),
(34, 33, 54, 1, '45.00', 'Capucchino Entero (Café Americano, Leche de almendra) - Mediano Mediano', NULL),
(35, 34, 54, 1, '45.00', 'Capucchino Entero (Café Americano, Leche de almendra) - Chico Chico', NULL),
(36, 35, 54, 4, '45.00', 'Capucchino Entero (Café Americano, Leche de almendra) - Chico Chico', NULL),
(37, 36, 60, 10, '70.00', 'Irlandés (Café Americano, Leche de almendra) - Mediano Mediano', NULL),
(38, 37, 59, 2, '60.00', 'Capucchino (Café Americano, Leche de almendra) - Chico Chico', NULL),
(39, 38, 59, 1, '60.00', 'Capucchino (Café Americano, Leche de almendra) - Chico Chico', NULL),
(40, 39, 59, 7, '60.00', 'Capucchino (Café Americano, Leche de almendra) - Chico Chico', NULL),
(41, 39, 65, 10, '70.00', 'Carajillo (Café Americano, Leche de almendra) - Chico Chico', NULL),
(42, 40, 51, 4, '43.00', '250ML, Canela', NULL),
(43, 41, 52, 6, '40.00', '250ML, ', NULL),
(44, 42, 52, 1, '40.00', '250ML, ', NULL),
(45, 43, 51, 1, '43.00', '250ML, Canela', NULL),
(46, 44, 51, 1, '43.00', '250ML, Canela', NULL),
(47, 45, 53, 1, '72.00', '250ML, Latte, Leche entera', NULL),
(48, 46, 53, 2, '93.00', '1L, Cold Brew, Leche entera', NULL),
(49, 47, 55, 1, '42.00', 'Lechero (Entera) (Café Americano, Leche de almendra) - Chico Chico', NULL),
(50, 48, 55, 2, '42.00', 'Lechero (Entera) (Café Americano, Leche de almendra) - Mediano Mediano', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `idp` int(11) NOT NULL,
  `namep` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `ruta_imagen` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `precio` int(11) NOT NULL,
  `categoria` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sabor` int(11) DEFAULT NULL,
  `tamano_defecto` int(11) NOT NULL DEFAULT '1',
  `VENTAS` int(11) NOT NULL DEFAULT '0',
  `STOCK` int(11) NOT NULL DEFAULT '0',
  `descripcion` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`idp`, `namep`, `ruta_imagen`, `precio`, `categoria`, `sabor`, `tamano_defecto`, `VENTAS`, `STOCK`, `descripcion`) VALUES
(51, 'Americano', '../../Images/CafeAmer.png', 40, 'Bebidas frias', 1, 1, 1, 1, '50% café espresso, 50% agua'),
(52, 'Espresso', '../../Images/691abf179a42e_Cafe expresso.png', 40, 'Bebidas calientes', 1, 1, 0, 0, '100% espresso corto'),
(53, 'Macchiato', '../../Images/Macchi.png', 50, 'Bebidas calientes', 1, 1, 3, 1, '70% espresso, 30% espuma de leche'),
(54, 'Capucchino Entero', '../../Images/CafeCapu.png', 45, 'Bebidas calientes', 2, 1, 0, 6, '40% espresso, 40% leche, 20% espuma'),
(55, 'Lechero (Entera)', '../../Images/Lechero.png', 42, 'Bebidas calientes', 2, 1, 2, 3, '40% café, 60% leche entera'),
(57, 'Moka', '../../Images/Moka.png', 50, 'Bebidas calientes', 1, 1, 0, 2, '50% café, 30% chocolate, 20% leche'),
(58, 'Matcha2', '../../Images/Matchalatte.png', 65, 'Cafés', 1, 1, 0, 8, ''),
(59, 'Capucchino', '../../Images/CafeCapu.png', 55, 'Bebidas calientes', 3, 1, 0, 7, '40% espresso, 40% leche, 20% espuma'),
(60, 'Irlandés', '../../Images/Irlandes.png', 70, 'Bebidas calientes', 1, 1, 0, 6, '50% café, 30% crema, 20% licor'),
(61, 'Latte', '../../Images/691abf353fef5_Cafe latte.png', 45, 'Bebidas calientes', 2, 1, 0, 7, '30% espresso, 65% leche de avena, 5% espuma'),
(65, 'Carajillo', '../../Images/Carajillo.png', 70, 'Bebidas calientes', 1, 1, 0, 4, '50% espresso, 40% licor, 10% hielo/espuma'),
(66, 'Matchalatte', '../../Images/Matchalatte.png', 60, 'Bebidas calientes', 1, 1, 0, 8, '50% leche, 45% matcha, 5% espuma'),
(69, 'Chocolate caliente', '../../Images/691abf4a4799b_Chocolate caliente.png', 30, 'Bebidas calientes', 3, 1, 0, 5, '60% cacao dulce, 35% leche, 5% crema'),
(81, 'Frappé', '../../Images/691abf5f3c53c_Frappé clasico.png', 60, 'Bebidas frias', 2, 1, 0, 8, '40% café, 40% leche, 20% hielo frappeado'),
(83, 'Frappé moka', '../../Images/691abf71129cc_Frappé moka.png', 65, 'Bebidas frias', 2, 1, 0, 6, '40% café, 40% chocolate, 20% hielo frappeado'),
(86, 'Frappé caramel', '../../Images/691abf8266d93_Frappé caramel.png', 65, 'Bebidas frias', 3, 1, 0, 4, '40% caramelo, 40% leche, 20% hielo frappeado'),
(87, 'Frappé cookies n cream', '../../Images/FrappCnC.png', 75, 'Bebidas frias', 2, 1, 0, 10, '30% café, 30% galletas, 30% leche, 10% crema batida'),
(89, 'Frappé matcha', '../../Images/691abf96658bb_Frappé matcha.png', 80, 'Bebidas frias', 2, 1, 0, 10, '50% leche, 35% matcha, 15% hielo frappeado'),
(91, 'Frappé espresso', '../../Images/691abfa9d80d9_Frappé espresso.png', 75, 'Bebidas frias', 2, 1, 0, 10, '50% café espresso frío, 35% leche, 15% hielo'),
(93, 'Iced tea Negro', '../../Images/IcedTeaBlack.png', 45, 'Bebidas frias', 7, 1, 0, 10, '70% té negro, 20% agua fría, 10% hielo'),
(94, 'Iced tea Limón', '../../Images/IcedTea.png', 45, 'Bebidas frias', 8, 1, 0, 10, '60% té, 30% limón, 10% hielo'),
(96, 'Té Manzanilla', '../../Images/Te.png', 35, 'Bebidas calientes', 6, 1, 0, 10, '70% infusión de manzanilla, 30% agua caliente'),
(97, 'Té Negro', '../../Images/TeCali.png', 35, 'Bebidas calientes', 7, 1, 0, 10, '70% infusión de té negro, 30% agua caliente'),
(98, 'Té Limón', '../../Images/Te.png', 35, 'Bebidas calientes', 8, 1, 0, 10, '60% té, 30% limón, 10% hielo'),
(133, 'Panini de aguacate', '../../Images/panini_aguacate.png', 90, '4', 1, 1, 0, 20, '40% aguacate, 30% verduras, 20% pan, 10% queso'),
(134, 'Panini caprese', '../../Images/panini_caprese.png', 90, '4', 1, 1, 0, 20, '40% jitomate, 30% mozzarella, 20% albahaca, 10% pan'),
(135, 'Panini pavo y queso', '../../Images/panini_pavo_queso.png', 100, '4', 1, 1, 0, 20, '45% pavo, 25% queso, 20% pan, 10% mostaza'),
(136, 'Panini de pollo', '../../Images/panini_pollo.png', 100, '4', 1, 1, 0, 20, '40% pollo, 25% queso, 20% pan, 15% verduras'),
(137, 'Panini de queso', '../../Images/panini_queso.png', 100, '4', 1, 1, 0, 20, '50% queso, 25% pan, 15% crema, 10% especias'),
(138, 'Panini serrano', '../../Images/panini_serrano.png', 100, '4', 1, 1, 0, 20, '40% jamón serrano, 30% queso, 20% pan, 10% aceite de oliva'),
(139, 'Sándwich de panela', '../../Images/sandwich_panela.png', 100, '4', 1, 1, 0, 20, '40% panela, 30% verduras, 20% pan, 10% aderezo'),
(140, 'Sándwich de pavo', '../../Images/sandwich_pavo.png', 100, '4', 1, 1, 0, 20, '40% pavo, 30% queso, 20% pan, 10% mayonesa'),
(141, 'Sándwich de queso', '../../Images/sandwich_queso.png', 100, '4', 1, 1, 0, 20, '50% queso, 25% pan, 15% mantequilla, 10% especias'),
(142, 'Sándwich serrano', '../../Images/sandwich_serrano.png', 100, '4', 1, 1, 0, 20, '40% jamón serrano, 30% queso, 20% pan, 10% jitomate'),
(143, 'Sándwich de tocino', '../../Images/sandwich_tocino.png', 100, '4', 1, 1, 0, 20, '40% tocino, 30% queso, 20% pan, 10% jitomate'),
(144, 'Bagel clásico', '../../Images/691ac1527bbe7_Bagel clasico.png', 50, '7', 1, 1, 0, 20, '50% pan de bagel, 30% queso crema, 10% mantequilla, 10% miel'),
(146, 'Brownies', '../../Images/brownies.png', 50, '7', 1, 1, 0, 20, '40% chocolate, 30% mantequilla, 20% harina, 10% nuez'),
(147, 'Pastel de chocolate', '../../Images/pastel.png', 60, '7', 1, 1, 0, 20, '45% chocolate, 25% harina, 20% crema, 10% azúcar'),
(148, 'Galleta casera', '../../Images/galleta_casera.png', 30, '7', 1, 1, 0, 20, '40% harina, 30% mantequilla, 20% chispas, 10% azúcar'),
(149, 'Ensalada Caprese', '../../Images/ensalada_caprese.png', 100, '11', 1, 1, 0, 19, '40% jitomate, 30% mozzarella, 20% albahaca, 10% aceite de oliva'),
(150, 'Ensalada Griega', '../../Images/ensalada_griega.png', 100, '11', 1, 1, 0, 20, '35% pepino, 30% tomate, 25% queso feta, 10% aceitunas'),
(151, 'Ensalada Rusa', '../../Images/ensalada_rusa.png', 100, '11', 1, 1, 0, 20, '40% papa, 30% zanahoria, 20% chícharos, 10% mayonesa'),
(152, 'Ensalada Verde', '../../Images/ensalada_verde.png', 100, '11', 1, 1, 0, 20, '40% lechuga, 30% espinaca, 20% pepino, 10% aderezo');

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
(51, 1),
(52, 1),
(53, 1),
(59, 1),
(65, 1),
(69, 1),
(96, 1),
(97, 1),
(98, 1),
(81, 2),
(83, 2),
(86, 2),
(87, 2),
(91, 2),
(93, 2),
(51, 3),
(52, 3),
(53, 3),
(54, 3),
(55, 3),
(57, 3),
(59, 3),
(60, 3),
(61, 3),
(65, 3),
(81, 3),
(83, 3),
(86, 3),
(87, 3),
(91, 3),
(133, 4),
(134, 4),
(135, 4),
(136, 4),
(137, 4),
(138, 4),
(139, 4),
(140, 4),
(141, 4),
(142, 4),
(143, 4),
(144, 6),
(146, 7),
(147, 7),
(148, 7),
(58, 10),
(66, 10),
(89, 10),
(93, 10),
(94, 10),
(96, 10),
(97, 10),
(98, 10),
(149, 11),
(150, 11),
(151, 11),
(152, 11);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto_listbox`
--

CREATE TABLE `producto_listbox` (
  `id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `listbox_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `producto_listbox`
--

INSERT INTO `producto_listbox` (`id`, `producto_id`, `listbox_id`) VALUES
(42, 51, 3),
(50, 53, 1),
(51, 53, 2),
(52, 54, 1),
(53, 54, 2),
(54, 55, 1),
(55, 55, 2),
(56, 57, 1),
(57, 57, 2),
(58, 58, 8),
(59, 59, 1),
(60, 59, 2),
(61, 60, 1),
(62, 60, 2),
(70, 65, 1),
(71, 65, 2),
(72, 66, 8),
(81, 87, 1),
(82, 87, 2),
(87, 146, 3),
(88, 147, 3),
(89, 149, 4),
(90, 150, 4),
(91, 151, 4),
(92, 152, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto_opciones`
--

CREATE TABLE `producto_opciones` (
  `id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `listbox_opcion_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

--
-- Volcado de datos para la tabla `promocion`
--

INSERT INTO `promocion` (`idPromo`, `nombrePromo`, `imagen_url`, `codigo_promo`, `condiciones`, `tipo_descuento`, `valor_descuento`, `fechaInicio`, `fechaFin`, `activo`, `fecha_creacion`) VALUES
(1, '123', '../img/1761140857_HsetxCc5.jpg', '68f8e0794c5a3', '123', 'porcentaje', '123.00', '0123-03-12', '0123-03-12', 1, '2025-10-22 13:47:37');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `resena`
--

CREATE TABLE `resena` (
  `idr` int(11) NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `comentario` text COLLATE utf8mb4_general_ci,
  `calificacion` int(11) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `imagen_url` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `etiquetas` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `likes` int(11) DEFAULT '0',
  `parent_id` int(11) DEFAULT NULL,
  `userid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `resena`
--

INSERT INTO `resena` (`idr`, `nombre`, `comentario`, `calificacion`, `fecha`, `imagen_url`, `etiquetas`, `likes`, `parent_id`, `userid`) VALUES
(4, 'NoisiUsuario', 'muamua', 4, '2025-11-11 15:50:37', NULL, NULL, 0, NULL, 19),
(5, 'NoisiUsuario', 'isisi', NULL, '2025-11-11 15:51:32', NULL, NULL, 1, 4, 19),
(8, 'NoisiUsuario', 'fdsdD', 4, '2025-11-11 16:07:37', NULL, NULL, 0, NULL, 19),
(9, 'Noisi', 'adasd', 5, '2025-11-11 16:14:21', NULL, NULL, 0, NULL, 18),
(10, 'Noisi', 'AAA', 1, '2025-11-11 16:14:38', NULL, NULL, 0, NULL, 18),
(11, 'NoisiUsuario', 'adsad', 3, '2025-11-11 16:15:21', NULL, NULL, 0, NULL, 19),
(12, 'NoisiUsuario', 'muaaaa', 4, '2025-11-11 16:18:54', NULL, NULL, 0, NULL, 19);

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
  `email` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` int(11) DEFAULT '1',
  `status` tinyint(1) DEFAULT '1',
  `archived` tinyint(1) DEFAULT '0',
  `apellido` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `telefono` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fecha_nac` date DEFAULT NULL,
  `zona_horaria` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Password_Token` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Password_Token_Exp` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`userid`, `profilescreen`, `username`, `email`, `password`, `role`, `status`, `archived`, `apellido`, `telefono`, `fecha_nac`, `zona_horaria`, `Password_Token`, `Password_Token_Exp`) VALUES
(17, NULL, 'mparra12', 'mparra8@ucol.mx', '$2y$10$VLyk4esMBH4GAVnwWE..Me/hjYxmy55lykf6BWEZutbFGHuE0pM.i', 1, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL),
(18, '../../Images/Captura de pantalla 2024-10-16 185653.png', 'Noisi', 'admin@tienda.com', '$2y$10$4LsEhgLYHmvd.43lhq3.yukRT79dAwrpCIMQ64LVggS5CmVm6ROCe', 4, 1, 0, '', '', '0000-00-00', '(UTC -06:00) Guadalajara, CDMX', NULL, NULL),
(19, '../images/profiles/avatar_user_19.png', 'NoisiUsuario', 'noisi@gmail.com', '$2y$10$dNnooKM7L2SGvVAnKqFs3.dlrBv67f5129gIAERWMiMSNXLvvFYC.', 1, 1, 0, '', '', '0000-00-00', '(UTC -06:00) Guadalajara, CDMX', NULL, NULL),
(20, '../../Images/Profiles/20_691e9d0db35d3.png', 'Victor Carrillo', 'carrillobarajasvictormanuel07@gmail.com', '$2y$10$kU3mqNlVAP9zKyH01PGczOnZrqUCA6bjaANncQ40rcEWYeMM.Wi6W', 2, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL);

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
-- Indices de la tabla `listboxes`
--
ALTER TABLE `listboxes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `listbox_opciones`
--
ALTER TABLE `listbox_opciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `listbox_id` (`listbox_id`);

--
-- Indices de la tabla `movimientos`
--
ALTER TABLE `movimientos`
  ADD PRIMARY KEY (`id_movimiento`);

--
-- Indices de la tabla `opciones_categoria`
--
ALTER TABLE `opciones_categoria`
  ADD PRIMARY KEY (`id_categoria`,`id_opcion_predefinida`),
  ADD KEY `id_opcion_predefinida` (`id_opcion_predefinida`);

--
-- Indices de la tabla `opciones_predefinidas`
--
ALTER TABLE `opciones_predefinidas`
  ADD PRIMARY KEY (`id_opcion_predefinida`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id_pedido`),
  ADD KEY `userid_idx` (`userid`);

--
-- Indices de la tabla `pedido_items`
--
ALTER TABLE `pedido_items`
  ADD PRIMARY KEY (`id_pedido_item`),
  ADD KEY `id_pedido_idx` (`id_pedido`),
  ADD KEY `id_producto_idx` (`id_producto`);

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
-- Indices de la tabla `producto_listbox`
--
ALTER TABLE `producto_listbox`
  ADD PRIMARY KEY (`id`),
  ADD KEY `producto_id` (`producto_id`),
  ADD KEY `listbox_id` (`listbox_id`);

--
-- Indices de la tabla `producto_opciones`
--
ALTER TABLE `producto_opciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_producto` (`producto_id`),
  ADD KEY `fk_opcion` (`listbox_opcion_id`);

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
  ADD PRIMARY KEY (`idr`);

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
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `Password_Token` (`Password_Token`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `cortes_caja`
--
ALTER TABLE `cortes_caja`
  MODIFY `id_corte_caja` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `listboxes`
--
ALTER TABLE `listboxes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `listbox_opciones`
--
ALTER TABLE `listbox_opciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;

--
-- AUTO_INCREMENT de la tabla `movimientos`
--
ALTER TABLE `movimientos`
  MODIFY `id_movimiento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `opciones_predefinidas`
--
ALTER TABLE `opciones_predefinidas`
  MODIFY `id_opcion_predefinida` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT de la tabla `pedido_items`
--
ALTER TABLE `pedido_items`
  MODIFY `id_pedido_item` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `idp` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=189;

--
-- AUTO_INCREMENT de la tabla `producto_listbox`
--
ALTER TABLE `producto_listbox`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT de la tabla `producto_opciones`
--
ALTER TABLE `producto_opciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `promocion`
--
ALTER TABLE `promocion`
  MODIFY `idPromo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `resena`
--
ALTER TABLE `resena`
  MODIFY `idr` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

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
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

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
-- Filtros para la tabla `listbox_opciones`
--
ALTER TABLE `listbox_opciones`
  ADD CONSTRAINT `listbox_opciones_ibfk_1` FOREIGN KEY (`listbox_id`) REFERENCES `listboxes` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `opciones_categoria`
--
ALTER TABLE `opciones_categoria`
  ADD CONSTRAINT `opciones_categoria_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id_categoria`) ON DELETE CASCADE,
  ADD CONSTRAINT `opciones_categoria_ibfk_2` FOREIGN KEY (`id_opcion_predefinida`) REFERENCES `opciones_predefinidas` (`id_opcion_predefinida`) ON DELETE CASCADE;

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `fk_pedido_usuario` FOREIGN KEY (`userid`) REFERENCES `usuarios` (`userid`);

--
-- Filtros para la tabla `pedido_items`
--
ALTER TABLE `pedido_items`
  ADD CONSTRAINT `fk_item_pedido` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id_pedido`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_item_producto` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`idp`);

--
-- Filtros para la tabla `producto_categorias`
--
ALTER TABLE `producto_categorias`
  ADD CONSTRAINT `producto_categorias_ibfk_1` FOREIGN KEY (`idp`) REFERENCES `productos` (`idp`) ON DELETE CASCADE,
  ADD CONSTRAINT `producto_categorias_ibfk_2` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id_categoria`) ON DELETE CASCADE;

--
-- Filtros para la tabla `producto_listbox`
--
ALTER TABLE `producto_listbox`
  ADD CONSTRAINT `producto_listbox_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`idp`) ON DELETE CASCADE,
  ADD CONSTRAINT `producto_listbox_ibfk_2` FOREIGN KEY (`listbox_id`) REFERENCES `listboxes` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `producto_opciones`
--
ALTER TABLE `producto_opciones`
  ADD CONSTRAINT `fk_opcion` FOREIGN KEY (`listbox_opcion_id`) REFERENCES `listbox_opciones` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_producto` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`idp`) ON DELETE CASCADE,
  ADD CONSTRAINT `producto_opciones_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`idp`) ON DELETE CASCADE,
  ADD CONSTRAINT `producto_opciones_ibfk_2` FOREIGN KEY (`listbox_opcion_id`) REFERENCES `listbox_opciones` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
