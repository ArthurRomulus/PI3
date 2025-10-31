-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 31-10-2025 a las 03:36:44
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
  `numero_admin` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nombre_completo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `telefono` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `telefono_emergencia` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `direccion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
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
  `nombrecategoria` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
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
  `estado` enum('abierto','cerrado') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleados_cajeros`
--

CREATE TABLE `empleados_cajeros` (
  `userid` int(11) NOT NULL,
  `numero_empleado` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nombre_completo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `telefono` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `telefono_emergencia` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `direccion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
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
  `nombre_opcion` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `valor` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
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
(1, 'Café Americano', '../../Images/Cafe_americano.png', 50, '1', NULL, 0, 0, 20, '50% café espresso, 50% agua caliente'),
(2, 'Café Capuchino', '../../Images/cafe_capuchino.png', 60, '1', NULL, 0, 0, 20, '40% espresso, 30% leche vaporizada, 30% espuma'),
(3, 'Café Latte', '../../Images/cafe_latte.png', 70, '1', NULL, 0, 0, 20, '40% espresso suave, 50% leche caliente, 10% espuma ligera'),
(4, 'Café Espresso', '../../Images/Espresso.png', 60, '1', NULL, 0, 0, 20, '100% espresso concentrado en shot corto'),
(5, 'Espresso Doble', '../../Images/EspreDoble.png', 70, '1', NULL, 0, 0, 20, '70% espresso base, 30% refuerzo extra de shot'),
(6, 'Café Lechero', '../../Images/Lechero.png', 60, '1', NULL, 0, 0, 20, '40% café, 60% leche caliente cremosa'),
(7, 'Chocolate Caliente', '../../Images/ChocoCali.png', 35, '1', NULL, 0, 0, 20, '60% cacao dulce, 30% leche caliente, 10% crema'),
(8, 'Café Irlandés', '../../Images/Irlandes.png', 40, '1', NULL, 0, 0, 20, '50% café, 30% crema suave, 20% toque licorizado'),
(9, 'Café Lungo', '../../Images/cafe_lungo.png', 40, '1', NULL, 0, 0, 20, '70% espresso extraído largo, 30% agua caliente'),
(10, 'Café Bombón', '../../Images/Cafe_bombon.png', 45, '1', NULL, 0, 0, 20, '60% espresso intenso, 40% leche condensada dulce'),
(11, 'Té Caliente', '../../Images/Tecali.png', 45, '1', NULL, 0, 0, 20, '70% infusión natural, 30% agua caliente filtrada'),
(12, 'Té de Manzanilla', '../../Images/Te.png', 30, '1', NULL, 0, 0, 20, '60% flor de manzanilla, 40% agua caliente'),
(13, 'Frappé Clásico', '../../Images/FrappeClasic.png', 90, '2', NULL, 0, 0, 20, '40% café frío, 40% leche, 20% hielo frappeado'),
(14, 'Frappé Matcha', '../../Images/FrappMatcha.png', 95, '2', NULL, 0, 0, 20, '50% matcha, 30% leche fría, 20% hielo frappeado'),
(15, 'Frappé Espresso', '../../Images/FrappEspresso.png', 95, '2', NULL, 0, 0, 20, '50% espresso frío, 30% leche, 20% hielo frappeado'),
(16, 'Frappé Caramel', '../../Images/FrapCaramel.png', 100, '2', NULL, 0, 0, 20, '40% caramelo, 40% leche fría, 20% hielo frappeado'),
(17, 'Frappé Moka', '../../Images/frappe_moka.png', 100, '2', NULL, 0, 0, 20, '40% café oscuro, 40% chocolate, 20% hielo frappeado'),
(18, 'Frappé Cookies & Cream', '../../Images/FrappCnC.png', 105, '2', NULL, 0, 0, 20, '50% galleta, 30% leche fría, 20% hielo frappeado'),
(19, 'Iced Coffee', '../../Images/icedCoffee.png', 85, '2', NULL, 0, 0, 20, '50% café frío, 30% leche, 20% hielo'),
(20, 'Iced Tea Black', '../../Images/IcedTeaBlack.png', 60, '2', NULL, 0, 0, 20, '60% té negro, 30% agua fría, 10% hielo'),
(21, 'Iced Tea', '../../Images/IcedTea.png', 60, '2', NULL, 0, 0, 20, '60% té, 30% limón, 10% hielo'),
(22, 'Café Carajillo', '../../Images/cafe_carajillo.png', 90, '1', NULL, 0, 0, 20, '50% espresso, 40% licor, 10% espuma de leche'),
(23, 'Café Caribeño', '../../Images/cafe_caribeño.png', 95, '1', NULL, 0, 0, 20, '40% espresso, 40% leche de coco, 20% azúcar morena'),
(24, 'Café Macchiato', '../../Images/cafe_macchiato.png', 80, '1', NULL, 0, 0, 20, '60% espresso, 30% leche espumada, 10% crema'),
(25, 'Café Mocca', '../../Images/cafe_mocca.png', 85, '1', NULL, 0, 0, 20, '50% espresso, 30% chocolate, 20% leche'),
(26, 'Café Ristretto', '../../Images/cafe_ristretto.png', 75, '1', NULL, 0, 0, 20, '80% espresso concentrado, 20% espuma ligera'),
(27, 'Café Vienés', '../../Images/cafe_vienes.png', 90, '1', NULL, 0, 0, 20, '50% espresso, 30% crema batida, 20% leche'),
(28, 'Matcha Latte', '../../Images/matchalatte.png', 85, '1', NULL, 0, 0, 20, '50% matcha, 40% leche caliente, 10% espuma'),
(58, 'Matcha2', '../../Images/Matchalatte.png', 65, 'Cafés', 1, 1, 0, 0, NULL),
(131, 'cafesittiitti', '../../Images/6902957bd3840_Cafe latte.png', 65, NULL, 2, 1, 0, 0, 'contiene mas café y café y cafeee'),
(132, 'Café sion sion', '../../Images/690295b025e7d_Cafe lechero.png', 58, NULL, 2, 1, 0, 0, 'la pension sion sion'),
(133, 'Panini de aguacate', '../../Images/panini_aguacate.png', 90, '4', 1, 1, 0, 20, '40% aguacate, 20% verduras, 20% pan, 20% queso'),
(134, 'Panini caprese', '../../Images/panini_caprese.png', 90, '4', 1, 1, 0, 20, '40% jitomate, 30% mozzarella, 20% albahaca, 10% pan'),
(135, 'Panini pavo y queso', '../../Images/panini_pavo_queso.png', 100, '4', 1, 1, 0, 20, '45% pavo, 25% queso, 20% pan, 10% mostaza'),
(136, 'Panini de pollo', '../../Images/panini_pollo.png', 100, '4', 1, 1, 0, 20, '40% pollo, 25% queso, 20% pan, 15% verduras'),
(137, 'Panini de queso', '../../Images/panini_queso.png', 100, '4', 1, 1, 0, 20, '50% queso, 25% pan, 15% crema, 10% especias'),
(138, 'Panini serrano', '../../Images/panini_serrano.png', 100, '4', 1, 1, 0, 20, '40% jamón serrano, 30% queso, 20% pan, 10% aceite de oliva'),
(139, 'Sándwich de panela', '../../Images/sandwich_panela.png', 100, '4', 1, 1, 0, 20, '40% queso panela, 30% verduras, 20% pan, 10% aderezo'),
(140, 'Sándwich de pavo', '../../Images/sandwich_pavo.png', 100, '4', 1, 1, 0, 20, '40% pavo, 30% queso, 20% pan, 10% mayonesa'),
(141, 'Sándwich de queso', '../../Images/sandwich_queso.png', 100, '4', 1, 1, 0, 20, '50% queso, 25% pan, 15% mantequilla, 10% especias'),
(142, 'Sándwich serrano', '../../Images/sandwich_serrano.png', 100, '4', 1, 1, 0, 20, '40% jamón serrano, 30% queso, 20% pan, 10% tomate'),
(143, 'Sándwich de tocino', '../../Images/sandwich_tocino.png', 100, '4', 1, 1, 0, 20, '40% tocino, 30% queso, 20% pan, 10% jitomate'),
(144, 'Bagel clásico', '../../Images/BagelClasic.png', 50, '7', 1, 1, 0, 20, '50% pan de bagel, 30% queso crema, 10% mantequilla, 10% miel'),
(146, 'Brownies', '../../Images/brownies.png', 50, '7', 1, 1, 0, 20, '40% chocolate, 30% mantequilla, 20% harina, 10% nuez'),
(147, 'Pastel de chocolate', '../../Images/pastel.png', 60, '7', 1, 1, 0, 20, '45% chocolate, 25% harina, 20% crema, 10% azúcar'),
(148, 'Galleta casera', '../../Images/galleta_casera.png', 30, '7', 1, 1, 0, 20, '40% harina, 30% mantequilla, 20% chispas, 10% azúcar'),
(149, 'Ensalada Caprese', '../../Images/ensalada_caprese.png', 100, '11', 1, 1, 0, 20, '40% jitomate, 30% queso mozzarella, 20% albahaca, 10% aceite de oliva'),
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
(131, 3),
(132, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto_opciones`
--

CREATE TABLE `producto_opciones` (
  `id_opcion` int(11) NOT NULL,
  `idp` int(11) NOT NULL,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `opciones` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `producto_opciones`
--

INSERT INTO `producto_opciones` (`id_opcion`, `idp`, `nombre`, `opciones`) VALUES
(1, 117, 'Bebidas calientes', '[\"Entera\"]'),
(2, 117, 'Cafés', '[\"Americano\"]'),
(3, 118, 'Bebidas calientes', '[\"Entera\"]'),
(4, 118, 'Cafés', '[\"Avena\"]'),
(5, 120, 'Bebidas calientes', '[\"Deslactosada\"]'),
(6, 121, 'Bebidas calientes', '[\"Entera\"]'),
(9, 122, 'Bebidas calientes', '[\"Deslactosada\",\"Avena\"]'),
(10, 123, 'Bebidas calientes', '[\"Entera\"]');

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
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `comentario` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `calificacion` int(11) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `imagen_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `etiquetas` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
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
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `role` int(11) DEFAULT '1',
  `status` tinyint(1) DEFAULT '1',
  `archived` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`userid`, `profilescreen`, `username`, `email`, `password`, `role`, `status`, `archived`) VALUES
(0, NULL, 'Koby_Tao', 'karolsinsel@gmail.com', '$2y$10$/z8GdsgLHd5twe4l9Cohtu94cxpPz1HEJVzQ317iJZoF..w0IkuCO', 4, 1, 0),
(2, '../../Images/DefaultProfile.png', 'mparra8@ucol.mx', 'mparra8@ucol.mx', '$2y$10$YDKyT8b3fa3CXImSQ77cKuEUKik2AiqR1ZguAjma.VQLmACkuLmr2', 4, 1, 0),
(4, '../../Images/OIP.webp', 'mparra321', 'miguepg06@gmail.com', '$2y$10$1coSCtNYm3JNGGmq3rJ2iefFVqsz.oPy1zlw5wBDw2kUe5UfSgbb6', 2, 1, 0),
(8, NULL, 'cajero', 'cajero@gmail.com', '$2y$10$P100IYAw.svkWdQQBJTb7ug3pmplcd/q0lZ68fuAafQgw4SmmHX5e', 1, 1, 0),
(9, '../../Images/Profiles/9_68fba03670427.png', 'vc', 'vc@gmail.com', '$2y$10$QeQ4zKTvgs3fXhBg2aOpPOFADLMBnZQXNcXviq5iQTMK24uFBTV06', 2, 1, 0),
(10, '../../Images/Captura de pantalla 2024-10-16 185653.png', 'Noisi', 'admin@tienda.com', '$2y$10$qgADfKAr.FHY6miNXvvybO7wi8mdjGeDkf2dmnYdaY0DThD5XAEKm', 4, 1, 0);

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
-- Indices de la tabla `producto_opciones`
--
ALTER TABLE `producto_opciones`
  ADD PRIMARY KEY (`id_opcion`),
  ADD KEY `idx_idp` (`idp`);

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
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `idp` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `producto_opciones`
--
ALTER TABLE `producto_opciones`
  MODIFY `id_opcion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
-- Filtros para la tabla `opciones_categoria`
--
ALTER TABLE `opciones_categoria`
  ADD CONSTRAINT `opciones_categoria_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id_categoria`) ON DELETE CASCADE,
  ADD CONSTRAINT `opciones_categoria_ibfk_2` FOREIGN KEY (`id_opcion_predefinida`) REFERENCES `opciones_predefinidas` (`id_opcion_predefinida`) ON DELETE CASCADE;

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
