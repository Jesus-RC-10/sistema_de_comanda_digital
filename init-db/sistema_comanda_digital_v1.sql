-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 24-04-2026 a las 03:17:19
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sistema_comanda_digital_v1`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alertas_sistema`
--

CREATE TABLE `alertas_sistema` (
  `id` int(11) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `mensaje` text NOT NULL,
  `nivel` enum('bajo','medio','alto') DEFAULT 'medio',
  `leida` tinyint(1) DEFAULT 0,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias_menu`
--

CREATE TABLE `categorias_menu` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `orden` int(11) DEFAULT 0,
  `activa` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias_menu`
--

INSERT INTO `categorias_menu` (`id`, `nombre`, `descripcion`, `orden`, `activa`) VALUES
(1, 'Tacos', 'Tipo de Taco', 1, 1),
(3, 'Bebidas', 'Refrescos y jugos', 3, 1),
(4, 'Postres', 'Deliciosos postres', 4, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingredientes`
--

CREATE TABLE `ingredientes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `categoria` enum('vegetales','carnes','lacteos','granos','especias','bebidas','otros') DEFAULT 'otros',
  `unidad_medida` enum('kg','gr','lt','ml','unidad','paquete') DEFAULT 'kg',
  `cantidad_actual` decimal(10,3) DEFAULT 0.000,
  `cantidad_minima` decimal(10,3) DEFAULT 1.000,
  `proveedor` varchar(100) DEFAULT NULL,
  `costo_unitario` decimal(10,2) DEFAULT 0.00,
  `activo` tinyint(1) DEFAULT 1,
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ingredientes`
--

INSERT INTO `ingredientes` (`id`, `nombre`, `descripcion`, `categoria`, `unidad_medida`, `cantidad_actual`, `cantidad_minima`, `proveedor`, `costo_unitario`, `activo`, `fecha_actualizacion`) VALUES
(1, 'Cebolla', 'Cebolla blanca para cocina', 'vegetales', 'kg', 5.500, 2.000, 'Proveedor Verduras', 3.50, 1, '2025-09-30 02:17:57'),
(2, 'Ajo', 'Ajo fresco', 'vegetales', 'kg', 2.300, 1.000, 'Proveedor Verduras', 12.00, 1, '2025-09-30 02:17:57'),
(3, 'Tomate', 'Tomate rojo maduro', 'vegetales', 'kg', 8.000, 3.000, 'Proveedor Verduras', 4.80, 1, '2025-09-30 02:17:57'),
(4, 'Pollo', 'Pollo entero fresco', 'carnes', 'kg', 15.000, 5.000, 'Carnicería Central', 18.50, 1, '2025-09-30 02:17:57'),
(5, 'Carne de Res', 'Carne de res para lomo saltado', 'carnes', 'kg', 12.500, 4.000, 'Carnicería Central', 25.00, 1, '2025-09-30 02:17:57'),
(6, 'Arroz', 'Arroz blanco grano largo', 'granos', 'kg', 20.000, 8.000, 'Distribuidora Granos', 5.20, 1, '2025-09-30 02:17:57'),
(7, 'Papas', 'Papas amarillas', 'vegetales', 'kg', 10.000, 4.000, 'Proveedor Verduras', 2.80, 1, '2025-09-30 02:17:57'),
(8, 'Limón', 'Limón verde', 'vegetales', 'kg', 3.000, 1.500, 'Proveedor Verduras', 6.50, 1, '2025-09-30 02:17:57'),
(9, 'Aceite Vegetal', 'Aceite para cocinar', 'otros', 'lt', 8.000, 3.000, 'Distribuidora Alimentos', 12.00, 1, '2025-09-30 02:17:57'),
(10, 'Sal', 'Sal fina', 'especias', 'kg', 4.000, 1.000, 'Distribuidora Alimentos', 2.50, 1, '2025-09-30 02:17:57');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario`
--

CREATE TABLE `inventario` (
  `id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad_actual` int(11) NOT NULL,
  `cantidad_minima` int(11) DEFAULT 5,
  `ultima_actualizacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `inventario`
--

INSERT INTO `inventario` (`id`, `producto_id`, `cantidad_actual`, `cantidad_minima`, `ultima_actualizacion`) VALUES
(1, 1, 20, 5, '2025-09-30 01:24:46'),
(2, 2, 15, 3, '2025-09-30 01:24:46'),
(3, 3, 12, 2, '2025-09-30 01:24:46'),
(4, 4, 50, 10, '2025-09-30 01:24:46'),
(5, 5, 30, 5, '2025-09-30 01:24:46'),
(6, 6, 10, 2, '2025-09-30 01:24:46'),
(7, 7, 8, 2, '2025-09-30 01:24:46');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesas`
--

CREATE TABLE `mesas` (
  `id` int(11) NOT NULL,
  `numero_mesa` varchar(10) NOT NULL,
  `estado` enum('libre','ocupada','reservada','mantenimiento') DEFAULT 'libre',
  `ubicacion` varchar(100) DEFAULT NULL,
  `activa` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `mesas`
--

INSERT INTO `mesas` (`id`, `numero_mesa`, `estado`, `ubicacion`, `activa`) VALUES
(1, 'M01', 'libre', 'Terraza', 1),
(2, 'M02', 'libre', 'Interior', 1),
(3, 'M03', 'libre', 'Sala Principal', 1),
(4, 'M04', 'libre', 'Terraza', 1),
(5, 'M05', 'libre', 'Sala VIP', 1),
(6, 'M06', 'libre', 'Interior', 0),
(10, 'M07', 'libre', 'Terraza', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `mesa_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `estado` enum('pendiente','confirmado','en_preparacion','listo','entregado','cancelado') DEFAULT 'pendiente',
  `total` decimal(10,2) DEFAULT 0.00,
  `notas` text DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `mesa_id`, `usuario_id`, `estado`, `total`, `notas`, `fecha_creacion`, `fecha_actualizacion`) VALUES
(1, 1, 2, 'pendiente', 25.00, NULL, '2026-03-08 09:27:21', '2026-03-08 09:27:21'),
(2, 2, 2, 'pendiente', 251.00, NULL, '2026-03-08 09:36:09', '2026-03-08 09:36:09'),
(3, 1, 2, 'pendiente', 105.00, NULL, '2026-03-10 16:23:54', '2026-03-10 16:23:54'),
(4, 1, 2, 'pendiente', 25.00, NULL, '2026-03-10 16:25:17', '2026-03-10 16:25:17'),
(5, 3, 2, 'pendiente', 35.00, NULL, '2026-03-12 14:59:10', '2026-03-12 14:59:10'),
(6, 1, 2, 'pendiente', 70.00, NULL, '2026-03-12 15:33:40', '2026-03-12 15:33:40'),
(7, 3, 2, 'pendiente', 86.00, NULL, '2026-04-23 22:28:11', '2026-04-23 22:28:11'),
(9, 1, 1, 'pendiente', 42.00, NULL, '2026-04-23 22:35:25', '2026-04-23 22:35:25'),
(10, 1, 1, 'pendiente', 42.00, NULL, '2026-04-23 22:35:42', '2026-04-23 22:35:42'),
(11, 3, 2, 'pendiente', 70.00, NULL, '2026-04-23 22:37:08', '2026-04-23 22:37:08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_detalles`
--

CREATE TABLE `pedido_detalles` (
  `id` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `notas` text DEFAULT NULL,
  `estado` enum('pendiente','en_preparacion','listo','entregado') DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedido_detalles`
--

INSERT INTO `pedido_detalles` (`id`, `pedido_id`, `producto_id`, `cantidad`, `precio_unitario`, `subtotal`, `notas`, `estado`) VALUES
(1, 1, 8, 1, 25.00, 25.00, NULL, 'pendiente'),
(2, 2, 4, 2, 8.00, 16.00, NULL, 'pendiente'),
(3, 2, 8, 1, 25.00, 25.00, NULL, 'pendiente'),
(4, 2, 9, 3, 35.00, 105.00, NULL, 'pendiente'),
(5, 2, 10, 3, 35.00, 105.00, NULL, 'pendiente'),
(6, 3, 9, 1, 35.00, 35.00, NULL, 'pendiente'),
(7, 3, 10, 2, 35.00, 70.00, NULL, 'pendiente'),
(8, 4, 8, 1, 25.00, 25.00, NULL, 'pendiente'),
(9, 5, 9, 1, 35.00, 35.00, NULL, 'pendiente'),
(10, 6, 9, 2, 35.00, 70.00, NULL, 'pendiente'),
(11, 7, 4, 2, 8.00, 16.00, NULL, 'pendiente'),
(12, 7, 10, 2, 35.00, 70.00, NULL, 'pendiente'),
(13, 9, 1, 2, 15.00, 30.00, NULL, 'pendiente'),
(14, 9, 4, 1, 12.00, 12.00, NULL, 'pendiente'),
(15, 10, 1, 2, 15.00, 30.00, NULL, 'pendiente'),
(16, 10, 4, 1, 12.00, 12.00, NULL, 'pendiente'),
(17, 11, 9, 1, 35.00, 35.00, NULL, 'pendiente'),
(18, 11, 10, 1, 35.00, 35.00, NULL, 'pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `categoria_id` int(11) DEFAULT NULL,
  `imagen_url` varchar(500) DEFAULT NULL,
  `stock` int(11) DEFAULT 0,
  `activo` tinyint(1) DEFAULT 1,
  `tiempo_preparacion` int(11) DEFAULT 15,
  `imagen` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `precio`, `categoria_id`, `imagen_url`, `stock`, `activo`, `tiempo_preparacion`, `imagen`) VALUES
(1, 'Taco campechano', 'taco campechano sin verduras ni queso', 25.00, 1, NULL, 20, 0, 15, NULL),
(2, 'Taco de costilla', 'taco de costilla sin verduras', 35.00, 1, NULL, 15, 0, 15, NULL),
(3, 'Taco de suadero con queso', 'Taco de suadero con queso sin verduras', 28.00, 1, NULL, 12, 0, 15, NULL),
(4, 'Inca Kola', 'Refresco peruano 500ml', 8.00, 3, NULL, 50, 1, 15, NULL),
(5, 'Chicha Morada', 'Bebida tradicional de maíz morado', 7.00, 3, NULL, 30, 0, 15, NULL),
(6, 'Mazamorra Morada', 'Postre tradicional peruano', 12.00, 4, NULL, 10, 0, 15, NULL),
(7, 'Arroz con Leche', 'Postre de arroz con canela', 10.00, 4, NULL, 8, 0, 15, NULL),
(8, 'Taco al pastor', 'taco al pastor sencillo sin verdura ni queso', 25.00, 1, NULL, 50, 1, 15, NULL),
(9, 'Taco al pastor con queso', 'taco al pastor con queso sin verduras', 35.00, 1, NULL, 50, 1, 15, NULL),
(10, 'Taco de suadero', 'Taco de suadero sin queso ni verduras', 35.00, 1, NULL, 50, 0, 15, NULL),
(11, 'taco de perro', 'orden de taco de perro', 40.00, 1, NULL, 4, 1, 15, '1776984695_taco.jpg'),
(12, 'Taco Uriel', 'Taquito Uva', 15.00, 1, NULL, 20, 1, 15, '1776992752_tacouri.jpeg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recetas_producto`
--

CREATE TABLE `recetas_producto` (
  `id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `ingrediente_id` int(11) NOT NULL,
  `cantidad` decimal(10,3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `recetas_producto`
--

INSERT INTO `recetas_producto` (`id`, `producto_id`, `ingrediente_id`, `cantidad`) VALUES
(1, 11, 5, 0.130),
(2, 12, 6, 1.000);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `rol` enum('admin','mesero','cocina','caja') NOT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `ultimo_login` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `password_hash`, `nombre`, `rol`, `activo`, `fecha_creacion`, `ultimo_login`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrador Principal', 'admin', 1, '2025-09-30 01:24:45', '2025-11-23 18:49:00'),
(2, 'mesero1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Juan Pérez', 'mesero', 1, '2025-09-30 01:24:45', NULL),
(3, 'cocina1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'María García', 'cocina', 1, '2025-09-30 01:24:45', NULL),
(4, 'caja', '$2y$10$d/5wQy2tJcgRxKBv7LEYq.8lt8QwMpGwZ533JM6VEY91Mjbx.AVMa', 'Uriel', 'caja', 1, '2026-04-23 22:25:05', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `metodo_pago` enum('efectivo','tarjeta','transferencia','mixto') NOT NULL,
  `estado` enum('pendiente','pagado','cancelado') DEFAULT 'pendiente',
  `fecha_pago` timestamp NULL DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `usuario_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id`, `pedido_id`, `total`, `metodo_pago`, `estado`, `fecha_pago`, `fecha_creacion`, `usuario_id`) VALUES
(1, 7, 86.00, 'efectivo', 'pendiente', NULL, '2026-04-23 22:28:11', NULL),
(2, 10, 42.00, 'efectivo', 'pendiente', NULL, '2026-04-23 22:35:43', NULL),
(3, 11, 70.00, 'efectivo', 'pendiente', NULL, '2026-04-23 22:37:08', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alertas_sistema`
--
ALTER TABLE `alertas_sistema`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `categorias_menu`
--
ALTER TABLE `categorias_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ingredientes`
--
ALTER TABLE `ingredientes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `inventario`
--
ALTER TABLE `inventario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `mesas`
--
ALTER TABLE `mesas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero_mesa` (`numero_mesa`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `mesa_id` (`mesa_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `pedido_detalles`
--
ALTER TABLE `pedido_detalles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedido_id` (`pedido_id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- Indices de la tabla `recetas_producto`
--
ALTER TABLE `recetas_producto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `producto_id` (`producto_id`),
  ADD KEY `ingrediente_id` (`ingrediente_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedido_id` (`pedido_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `alertas_sistema`
--
ALTER TABLE `alertas_sistema`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `categorias_menu`
--
ALTER TABLE `categorias_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `ingredientes`
--
ALTER TABLE `ingredientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `inventario`
--
ALTER TABLE `inventario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `mesas`
--
ALTER TABLE `mesas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `pedido_detalles`
--
ALTER TABLE `pedido_detalles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `recetas_producto`
--
ALTER TABLE `recetas_producto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `inventario`
--
ALTER TABLE `inventario`
  ADD CONSTRAINT `inventario_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`mesa_id`) REFERENCES `mesas` (`id`),
  ADD CONSTRAINT `pedidos_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `pedido_detalles`
--
ALTER TABLE `pedido_detalles`
  ADD CONSTRAINT `pedido_detalles_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pedido_detalles_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias_menu` (`id`);

--
-- Filtros para la tabla `recetas_producto`
--
ALTER TABLE `recetas_producto`
  ADD CONSTRAINT `recetas_producto_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `recetas_producto_ibfk_2` FOREIGN KEY (`ingrediente_id`) REFERENCES `ingredientes` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`),
  ADD CONSTRAINT `ventas_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
