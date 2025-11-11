-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 11-11-2025 a las 17:24:05
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
(10, 'ADM001', 'Administrador General', '3120000000', '3120000001', 'Colima, México');

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
(11, 'Ensaladas'),
(5, 'Frappés'),
(6, 'Panes'),
(7, 'Postres'),
(8, 'Sin café'),
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
  `estado` enum('abierto','cerrado') COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `leches`
--

CREATE TABLE `leches` (
  `id_leche` int(10) UNSIGNED NOT NULL,
  `nombre_leche` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `leches`
--

INSERT INTO `leches` (`id_leche`, `nombre_leche`) VALUES
(4, 'Leche Almendras'),
(3, 'Leche Avena'),
(2, 'Leche Deslactosada'),
(1, 'Leche Entera');

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
(8, 'Sabor');

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
(33, 1, 'Espresso', '15.00'),
(34, 1, 'Americano', '10.00'),
(35, 1, 'Capuchino', '20.00'),
(36, 2, 'Entera', '10.00'),
(37, 2, 'Deslactosada', '10.00'),
(38, 2, 'Avena', '5.00'),
(39, 3, 'Chocolate', '10.00'),
(40, 3, 'Caramelo', '15.00'),
(41, 4, 'Queso', '8.00'),
(42, 4, 'Salsa', '14.00'),
(43, 4, 'Vegetales', '20.00'),
(44, 3, 'Chocolate', '8.00'),
(45, 3, 'Caramelo', '12.00'),
(46, 5, 'Vainilla', '10.00'),
(47, 5, 'Fresa', '10.00'),
(48, 6, 'Chocolate', '15.00'),
(49, 6, 'Dulce de leche', '25.00'),
(50, 7, 'Azúcar glas', '15.00'),
(51, 7, 'Chocolate', '10.00'),
(52, 7, 'Fresa', '13.00'),
(53, 4, 'Nueces', '10.00'),
(54, 8, 'Frutilla', '9.00'),
(55, 8, 'Chocolate', '15.00'),
(56, 8, 'Vainilla', '12.00'),
(57, 3, 'Canela', '10.00'),
(58, 3, 'Nuez moscada', '10.00'),
(59, 5, 'Calabaza', '9.00'),
(60, 5, 'Miel', '12.00'),
(61, 5, 'Jengibre', '14.00');

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
  `fecha_pedido` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sucursal` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `estado` enum('Completado','En preparación','Cancelado') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'En preparación'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_items`
--

CREATE TABLE `pedido_items` (
  `id_item` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `producto_nombre` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `cantidad` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `idp` int(11) NOT NULL,
  `namep` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `ruta_imagen` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `precio` int(11) NOT NULL,
  `categoria` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sabor` int(11) DEFAULT NULL,
  `tamano_defecto` int(11) NOT NULL DEFAULT '1',
  `VENTAS` int(11) NOT NULL DEFAULT '0',
  `STOCK` int(11) NOT NULL DEFAULT '0',
  `descripcion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`idp`, `namep`, `ruta_imagen`, `precio`, `categoria`, `sabor`, `tamano_defecto`, `VENTAS`, `STOCK`, `descripcion`) VALUES
(51, 'Americano', '../../Images/CafeAmer.png', 40, '1', 1, 1, 1, 10, '50% café espresso, 50% agua'),
(52, 'Espresso', '../../Images/Espresso.png', 40, '1', 1, 1, 0, 1, '100% espresso corto'),
(53, 'Macchiato', '../../Images/Macchi.png', 50, '1', 1, 1, 0, 10, '70% espresso, 30% espuma de leche'),
(54, 'Capucchino', '../../Images/CafeCapu.png', 45, '1', 2, 1, 0, 10, '40% espresso, 40% leche, 20% espuma'),
(55, 'Cafe Lechero', '../../Images/Lechero.png', 42, '1', 2, 1, 0, 10, '40% café, 60% leche entera'),
(57, 'Moka', '../../Images/Moka.png', 50, '1', 1, 1, 0, 10, '50% café, 30% chocolate, 20% leche'),
(60, 'Irlandés', '../../Images/Irlandes.png', 70, '1', 1, 1, 0, 10, '50% café, 30% crema, 20% licor'),
(63, 'Cafe Latte', '../../Images/Latte.png', 40, '1', 4, 1, 0, 10, '30% espresso, 65% leche de avena, 5% espuma'),
(65, 'Carajillo', '../../Images/Carajillo.png', 70, '1', 1, 1, 0, 10, '50% espresso, 40% licor, 10% hielo/espuma'),
(66, 'Matchalatte', '../../Images/Matchalatte.png', 60, '1', 1, 1, 0, 10, '50% leche, 45% matcha, 5% espuma'),
(68, 'Chocolate caliente', '../../Images/ChocoCali.png', 30, '1', 2, 1, 0, 10, '60% cacao dulce, 35% leche, 5% crema'),
(81, 'Frappé clásico', '../../Images/FrappeClasic.png', 60, '1', 2, 1, 0, 10, '40% café, 40% leche, 20% hielo frappeado'),
(83, 'Frappé moka', '../../Images/FrappMoka.png', 65, '1', 2, 1, 0, 10, '40% café, 40% chocolate, 20% hielo frappeado'),
(85, 'Frappé caramel', '../../Images/FrapCaramel.png', 65, '1', 2, 1, 0, 10, '40% caramelo, 40% leche, 20% hielo frappeado'),
(87, 'Frappé cookies n cream', '../../Images/FrappCnC.png', 75, '1', 2, 1, 0, 10, '30% café, 30% galletas, 30% leche, 10% crema batida'),
(89, 'Frappé matcha', '../../Images/FrappMatcha.png', 80, '1', 2, 1, 0, 10, '50% leche, 35% matcha, 15% hielo frappeado'),
(91, 'Frappé espresso', '../../Images/FrappEspresso.png', 75, '1', 2, 1, 0, 10, '50% café espresso frío, 35% leche, 15% hielo'),
(92, 'Limonada', '../../Images/Limonadas.png', 40, '1', 1, 1, 0, 10, '60% agua, 30% limón, 10% hielo'),
(93, 'Refrescos', '../../Images/refresco.png', 30, '1', 1, 1, 0, 10, 'Diferente tipos de Refrescos'),
(94, 'Agua en botella', '../../Images/agua.png', 20, '1', 1, 1, 0, 10, 'Agua natural (e-Pura)'),
(97, 'Té Negro', '../../Images/TeCali.png', 35, '1', 7, 1, 0, 20, '70% infusión de té negro, 30% agua caliente'),
(100, 'Iced tea Negro', '../../Images/IcedTeaBlack.png', 45, '1', 7, 1, 0, 10, '70% té negro, 20% agua fría, 10% hielo'),
(101, 'Iced tea Limón', '../../Images/IcedTea.png', 45, '1', 8, 1, 0, 10, '60% té, 30% limón, 10% hielo'),
(102, 'Té Manzanilla', '../../Images/Te.png', 35, '1', 6, 1, 0, 10, '70% infusión de manzanilla, 30% agua caliente'),
(133, 'Panini de aguacate', '../../Images/panini_aguacate.png', 90, '1', 1, 1, 0, 20, '40% aguacate, 30% verduras, 20% pan, 10% queso'),
(134, 'Panini caprese', '../../Images/panini_caprese.png', 90, '1', 1, 1, 0, 20, '40% jitomate, 30% mozzarella, 20% albahaca, 10% pan'),
(135, 'Panini pavo y queso', '../../Images/panini_pavo_queso.png', 100, '1', 1, 1, 0, 20, '45% pavo, 25% queso, 20% pan, 10% mostaza'),
(136, 'Panini de pollo', '../../Images/panini_pollo.png', 100, '1', 1, 1, 0, 20, '40% pollo, 25% queso, 20% pan, 15% verduras'),
(137, 'Panini de queso', '../../Images/panini_queso.png', 100, '1', 1, 1, 0, 20, '50% queso, 25% pan, 15% crema, 10% especias'),
(138, 'Panini serrano', '../../Images/panini_serrano.png', 100, '1', 1, 1, 0, 20, '40% jamón serrano, 30% queso, 20% pan, 10% aceite de oliva'),
(139, 'Sándwich de panela', '../../Images/sandwich_panela.png', 100, '1', 1, 1, 0, 20, '40% panela, 30% verduras, 20% pan, 10% aderezo'),
(140, 'Sándwich de pavo', '../../Images/sandwich_pavo.png', 100, '1', 1, 1, 0, 20, '40% pavo, 30% queso, 20% pan, 10% mayonesa'),
(141, 'Sándwich de queso', '../../Images/sandwich_queso.png', 100, '1', 1, 1, 0, 20, '50% queso, 25% pan, 15% mantequilla, 10% especias'),
(142, 'Sándwich serrano', '../../Images/sandwich_serrano.png', 100, '1', 1, 1, 0, 20, '40% jamón serrano, 30% queso, 20% pan, 10% jitomate'),
(143, 'Sándwich de tocino', '../../Images/sandwich_tocino.png', 100, '1', 1, 1, 0, 20, '40% tocino, 30% queso, 20% pan, 10% jitomate'),
(144, 'Bagel clásico', '../../Images/BagelClasic.png', 50, '1', 1, 1, 0, 20, '50% pan de bagel, 30% queso crema, 10% mantequilla, 10% miel'),
(146, 'Brownies', '../../Images/brownies.png', 50, '1', 1, 1, 0, 20, '40% chocolate, 30% mantequilla, 20% harina, 10% nuez'),
(147, 'Pastel de chocolate', '../../Images/pastel.png', 60, '1', 1, 1, 0, 20, '45% chocolate, \r\n25% harina, \r\n20% crema, \r\n10% azúcar'),
(148, 'Galleta casera c/u', '../../Images/galleta_casera.png', 15, '1', 1, 1, 0, 20, '40% harina, 30% mantequilla, 20% chispas, 10% azúcar'),
(149, 'Ensalada Caprese', '../../Images/ensalada_caprese.png', 100, '1', 1, 1, 0, 20, '40% jitomate, 30% mozzarella, 20% albahaca, 10% aceite de oliva'),
(150, 'Ensalada Griega', '../../Images/ensalada_griega.png', 100, '1', 1, 1, 0, 20, '35% pepino, 30% tomate, 25% queso feta, 10% aceitunas'),
(151, 'Ensalada Rusa', '../../Images/ensalada_rusa.png', 100, '1', 1, 1, 0, 20, '40% papa, 30% zanahoria, 20% chícharos, 10% mayonesa'),
(152, 'Ensalada Verde', '../../Images/ensalada_verde.png', 100, '1', 1, 1, 0, 20, '40% lechuga, 30% espinaca, 20% pepino, 10% aderezo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto_categoria`
--

CREATE TABLE `producto_categoria` (
  `idp` int(11) NOT NULL,
  `id_categoria` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `producto_categoria`
--

INSERT INTO `producto_categoria` (`idp`, `id_categoria`) VALUES
(51, 1),
(52, 1),
(53, 1),
(54, 1),
(55, 1),
(57, 1),
(60, 1),
(63, 1),
(65, 1),
(66, 1),
(68, 1),
(92, 2),
(93, 2),
(94, 2),
(51, 3),
(52, 3),
(54, 3),
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
(81, 5),
(83, 5),
(85, 5),
(87, 5),
(89, 5),
(91, 5),
(144, 6),
(146, 7),
(147, 7),
(148, 7),
(97, 10),
(100, 10),
(101, 10),
(102, 10),
(149, 11),
(150, 11),
(151, 11),
(152, 11);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto_leche`
--

CREATE TABLE `producto_leche` (
  `idp` int(11) NOT NULL,
  `id_leche` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `producto_leche`
--

INSERT INTO `producto_leche` (`idp`, `id_leche`) VALUES
(51, 1),
(52, 1),
(53, 1),
(54, 1),
(55, 1),
(57, 1),
(63, 1),
(66, 1),
(68, 1),
(81, 1),
(83, 1),
(85, 1),
(87, 1),
(89, 1),
(91, 1),
(51, 2),
(52, 2),
(53, 2),
(54, 2),
(55, 2),
(57, 2),
(63, 2),
(66, 2),
(68, 2),
(81, 2),
(83, 2),
(85, 2),
(87, 2),
(89, 2),
(91, 2),
(52, 3),
(53, 3),
(54, 3),
(55, 3),
(57, 3),
(63, 3),
(66, 3),
(68, 3),
(81, 3),
(83, 3),
(85, 3),
(87, 3),
(89, 3),
(91, 3),
(52, 4),
(53, 4),
(54, 4),
(55, 4),
(57, 4),
(63, 4),
(66, 4),
(68, 4),
(81, 4),
(83, 4),
(85, 4),
(87, 4),
(89, 4),
(91, 4);

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
(1, 165, 5),
(2, 165, 6),
(3, 166, 5),
(4, 166, 6),
(5, 167, 5),
(6, 167, 6),
(7, 168, 5),
(8, 168, 6),
(11, 171, 5),
(12, 171, 6),
(13, 172, 1),
(14, 172, 2),
(21, 175, 1),
(26, 176, 8);

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
-- Estructura de tabla para la tabla `producto_sabor`
--

CREATE TABLE `producto_sabor` (
  `idp` int(11) NOT NULL,
  `id_sabor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `producto_sabor`
--

INSERT INTO `producto_sabor` (`idp`, `id_sabor`) VALUES
(100, 1),
(102, 1),
(100, 2),
(102, 2),
(100, 3),
(102, 3),
(100, 4),
(102, 4),
(100, 5),
(102, 5),
(92, 6),
(92, 7),
(92, 8),
(92, 9);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto_tamano`
--

CREATE TABLE `producto_tamano` (
  `idp` int(11) NOT NULL,
  `tamano_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `producto_tamano`
--

INSERT INTO `producto_tamano` (`idp`, `tamano_id`) VALUES
(51, 1),
(51, 2),
(51, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto_verdura`
--

CREATE TABLE `producto_verdura` (
  `idp` int(11) NOT NULL,
  `id_verdura` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `producto_verdura`
--

INSERT INTO `producto_verdura` (`idp`, `id_verdura`) VALUES
(133, 1),
(134, 1),
(135, 1),
(136, 1),
(137, 1),
(138, 1),
(139, 1),
(140, 1),
(141, 1),
(142, 1),
(143, 1),
(149, 1),
(150, 1),
(152, 1),
(133, 2),
(134, 2),
(135, 2),
(136, 2),
(137, 2),
(138, 2),
(139, 2),
(140, 2),
(141, 2),
(142, 2),
(143, 2),
(149, 2),
(150, 2),
(151, 2),
(133, 3),
(136, 3),
(140, 3),
(142, 3),
(150, 3),
(152, 3),
(134, 4),
(135, 4),
(136, 4),
(138, 4),
(139, 4),
(140, 4),
(141, 4),
(142, 4),
(143, 4),
(149, 4),
(150, 4),
(151, 4),
(142, 5),
(151, 5),
(133, 6),
(134, 6),
(135, 6),
(136, 6),
(139, 6),
(140, 6),
(141, 6),
(142, 6),
(152, 6),
(134, 7),
(135, 7),
(136, 7),
(137, 7),
(138, 7),
(139, 7),
(140, 7),
(142, 7),
(143, 7),
(150, 7);

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
  `nombre` varchar(80) NOT NULL,
  `tipo` enum('te','limonada') NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `orden` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `sabores`
--

INSERT INTO `sabores` (`id_sabor`, `nombre`, `tipo`, `activo`, `orden`) VALUES
(1, 'Manzanilla', 'te', 1, 1),
(2, 'Limón', 'te', 1, 2),
(3, 'Camellia', 'te', 1, 3),
(4, 'Té verde', 'te', 1, 4),
(5, 'Hierbabuena', 'te', 1, 5),
(6, 'Limón', 'limonada', 1, 1),
(7, 'Fresa', 'limonada', 1, 2),
(8, 'Piña', 'limonada', 1, 3),
(9, 'Pepino', 'limonada', 1, 4);

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
  `apellido` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `telefono` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fecha_nac` date DEFAULT NULL,
  `zona_horaria` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`userid`, `profilescreen`, `username`, `email`, `password`, `role`, `status`, `archived`, `apellido`, `telefono`, `fecha_nac`, `zona_horaria`) VALUES
(0, NULL, 'Koby_Tao', 'karolsinsel@gmail.com', '$2y$10$/z8GdsgLHd5twe4l9Cohtu94cxpPz1HEJVzQ317iJZoF..w0IkuCO', 4, 1, 0, NULL, NULL, NULL, NULL),
(2, '../../Images/DefaultProfile.png', 'mparra8@ucol.mx', 'mparra8@ucol.mx', '$2y$10$YDKyT8b3fa3CXImSQ77cKuEUKik2AiqR1ZguAjma.VQLmACkuLmr2', 4, 1, 0, NULL, NULL, NULL, NULL),
(4, '../../Images/OIP.webp', 'mparra321', 'miguepg06@gmail.com', '$2y$10$1coSCtNYm3JNGGmq3rJ2iefFVqsz.oPy1zlw5wBDw2kUe5UfSgbb6', 2, 1, 0, NULL, NULL, NULL, NULL),
(8, NULL, 'cajero', 'cajero@gmail.com', '$2y$10$P100IYAw.svkWdQQBJTb7ug3pmplcd/q0lZ68fuAafQgw4SmmHX5e', 1, 1, 0, NULL, NULL, NULL, NULL),
(9, '../../Images/Profiles/9_68fba03670427.png', 'vc', 'vc@gmail.com', '$2y$10$QeQ4zKTvgs3fXhBg2aOpPOFADLMBnZQXNcXviq5iQTMK24uFBTV06', 2, 1, 0, NULL, NULL, NULL, NULL),
(10, '../../Images/Captura de pantalla 2024-10-16 185653.png', 'Noisi', 'admin@tienda.com', '$2y$10$qgADfKAr.FHY6miNXvvybO7wi8mdjGeDkf2dmnYdaY0DThD5XAEKm', 4, 1, 0, NULL, NULL, NULL, NULL),
(17, '../images/profiles/avatar_user_17.png', 'randyms', 'randymartinezsanchez314@gimel.com', '$2y$10$1tW4JpV7Kq7vogaNR8Fpd.r73UX5wJd.7bin8xJ1I4zC6Km0nIRyC', 1, 1, 0, 'martinez sanchez', '3141986741', '0000-00-00', '(UTC -06:00) Manzanillo, Colima');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `verduras`
--

CREATE TABLE `verduras` (
  `id_verdura` int(11) NOT NULL,
  `nombre_verdura` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `verduras`
--

INSERT INTO `verduras` (`id_verdura`, `nombre_verdura`) VALUES
(6, 'Aguacate'),
(4, 'Cebolla'),
(2, 'Jitomate'),
(1, 'Lechuga'),
(3, 'Pepino'),
(7, 'queso'),
(5, 'Zanahoria');

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
  ADD PRIMARY KEY (`id_categoria`),
  ADD UNIQUE KEY `uq_nombrecategoria` (`nombrecategoria`);

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
-- Indices de la tabla `leches`
--
ALTER TABLE `leches`
  ADD PRIMARY KEY (`id_leche`),
  ADD UNIQUE KEY `uq_leches_nombre` (`nombre_leche`);

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
  ADD KEY `fk_pedidos_usuario` (`userid`);

--
-- Indices de la tabla `pedido_items`
--
ALTER TABLE `pedido_items`
  ADD PRIMARY KEY (`id_item`),
  ADD KEY `fk_items_pedido` (`id_pedido`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`idp`),
  ADD KEY `categoria` (`categoria`),
  ADD KEY `sabor` (`sabor`),
  ADD KEY `producto_ibfk_3` (`tamano_defecto`);

--
-- Indices de la tabla `producto_categoria`
--
ALTER TABLE `producto_categoria`
  ADD PRIMARY KEY (`idp`,`id_categoria`),
  ADD KEY `idx_pc_categoria` (`id_categoria`);

--
-- Indices de la tabla `producto_leche`
--
ALTER TABLE `producto_leche`
  ADD PRIMARY KEY (`idp`,`id_leche`),
  ADD KEY `idx_pl_leche` (`id_leche`);

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
-- Indices de la tabla `producto_sabor`
--
ALTER TABLE `producto_sabor`
  ADD PRIMARY KEY (`idp`,`id_sabor`),
  ADD KEY `idx_ps_sabor` (`id_sabor`);

--
-- Indices de la tabla `producto_tamano`
--
ALTER TABLE `producto_tamano`
  ADD PRIMARY KEY (`idp`,`tamano_id`),
  ADD KEY `idx_pt_tamano` (`tamano_id`);

--
-- Indices de la tabla `producto_verdura`
--
ALTER TABLE `producto_verdura`
  ADD PRIMARY KEY (`idp`,`id_verdura`),
  ADD KEY `idx_pv_verdura` (`id_verdura`);

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
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `verduras`
--
ALTER TABLE `verduras`
  ADD PRIMARY KEY (`id_verdura`),
  ADD UNIQUE KEY `uq_verdura_nombre` (`nombre_verdura`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `cortes_caja`
--
ALTER TABLE `cortes_caja`
  MODIFY `id_corte_caja` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `leches`
--
ALTER TABLE `leches`
  MODIFY `id_leche` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `listboxes`
--
ALTER TABLE `listboxes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `listbox_opciones`
--
ALTER TABLE `listbox_opciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

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
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `pedido_items`
--
ALTER TABLE `pedido_items`
  MODIFY `id_item` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `producto_listbox`
--
ALTER TABLE `producto_listbox`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

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
  MODIFY `idr` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `sabores`
--
ALTER TABLE `sabores`
  MODIFY `id_sabor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `tamanos`
--
ALTER TABLE `tamanos`
  MODIFY `tamano_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `verduras`
--
ALTER TABLE `verduras`
  MODIFY `id_verdura` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
  ADD CONSTRAINT `fk_pedidos_usuario` FOREIGN KEY (`userid`) REFERENCES `usuarios` (`userid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `pedido_items`
--
ALTER TABLE `pedido_items`
  ADD CONSTRAINT `fk_items_pedido` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id_pedido`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `producto_categoria`
--
ALTER TABLE `producto_categoria`
  ADD CONSTRAINT `fk_pc_categoria` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id_categoria`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pc_producto` FOREIGN KEY (`idp`) REFERENCES `productos` (`idp`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `producto_leche`
--
ALTER TABLE `producto_leche`
  ADD CONSTRAINT `fk_pl_leche` FOREIGN KEY (`id_leche`) REFERENCES `leches` (`id_leche`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pl_producto` FOREIGN KEY (`idp`) REFERENCES `productos` (`idp`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `producto_listbox`
--
ALTER TABLE `producto_listbox`
  ADD CONSTRAINT `producto_listbox_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `blog`.`productos` (`idp`) ON DELETE CASCADE,
  ADD CONSTRAINT `producto_listbox_ibfk_2` FOREIGN KEY (`listbox_id`) REFERENCES `listboxes` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `producto_opciones`
--
ALTER TABLE `producto_opciones`
  ADD CONSTRAINT `fk_opcion` FOREIGN KEY (`listbox_opcion_id`) REFERENCES `listbox_opciones` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_producto` FOREIGN KEY (`producto_id`) REFERENCES `blog`.`productos` (`idp`) ON DELETE CASCADE,
  ADD CONSTRAINT `producto_opciones_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `blog`.`productos` (`idp`) ON DELETE CASCADE,
  ADD CONSTRAINT `producto_opciones_ibfk_2` FOREIGN KEY (`listbox_opcion_id`) REFERENCES `listbox_opciones` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `producto_sabor`
--
ALTER TABLE `producto_sabor`
  ADD CONSTRAINT `fk_ps_prod` FOREIGN KEY (`idp`) REFERENCES `productos` (`idp`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ps_sabor` FOREIGN KEY (`id_sabor`) REFERENCES `sabores` (`id_sabor`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Filtros para la tabla `producto_tamano`
--
ALTER TABLE `producto_tamano`
  ADD CONSTRAINT `fk_pt_producto` FOREIGN KEY (`idp`) REFERENCES `productos` (`idp`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pt_tamano` FOREIGN KEY (`tamano_id`) REFERENCES `tamanos` (`tamano_id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Filtros para la tabla `producto_verdura`
--
ALTER TABLE `producto_verdura`
  ADD CONSTRAINT `fk_pv_producto` FOREIGN KEY (`idp`) REFERENCES `productos` (`idp`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pv_verdura` FOREIGN KEY (`id_verdura`) REFERENCES `verduras` (`id_verdura`) ON DELETE RESTRICT ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
