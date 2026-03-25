-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-03-2026 a las 12:58:03
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
-- Base de datos: `otakustore`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito`
--

CREATE TABLE `carrito` (
  `id_carrito` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) DEFAULT 1,
  `fecha_agregado` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `carrito`
--

INSERT INTO `carrito` (`id_carrito`, `id_usuario`, `id_producto`, `cantidad`, `fecha_agregado`) VALUES
(2, 2, 8, 1, '2026-03-14 10:02:33');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compras`
--

CREATE TABLE `compras` (
  `id_compra` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha_compra` timestamp NOT NULL DEFAULT current_timestamp(),
  `total` decimal(10,2) NOT NULL,
  `estado` enum('pendiente','en camino','enviado','entregado') NOT NULL DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `compras`
--

INSERT INTO `compras` (`id_compra`, `id_usuario`, `fecha_compra`, `total`, `estado`) VALUES
(1, 1, '2026-03-14 09:28:17', 21.98, 'pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_compra`
--

CREATE TABLE `detalles_compra` (
  `id_detalle` int(11) NOT NULL,
  `id_compra` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalles_compra`
--

INSERT INTO `detalles_compra` (`id_detalle`, `id_compra`, `id_producto`, `cantidad`, `precio_unitario`) VALUES
(1, 1, 1, 2, 10.99);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes_contacto`
--

CREATE TABLE `mensajes_contacto` (
  `id_mensaje` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `asunto` varchar(100) DEFAULT NULL,
  `mensaje` text NOT NULL,
  `fecha_envio` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `categoria` enum('Funko','Cómic') NOT NULL,
  `descripcion` text DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `fecha_incorporacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `stock` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `nombre`, `categoria`, `descripcion`, `imagen`, `precio`, `fecha_incorporacion`, `stock`) VALUES
(1, 'Funko Pop! Naruto Uzumaki', 'Funko', 'Figura Funko Pop! de Naruto Uzumaki en su pose clásica con el traje naranja. Perteneciente a la serie Naruto Shippuden. Tamaño aproximado: 9,5 cm.', 'Funko_Naruto_Uzumaki.png', 14.99, '2026-03-13 17:57:01', 40),
(2, 'Funko Pop! Edward Elric', 'Funko', 'Figura Funko Pop! de Edward Elric, el alquimista de acero. De la serie Fullmetal Alchemist: Brotherhood. Tama?o aproximado: 9,5 cm.', 'Funko_Pop_Edward_Elric.png', 15.99, '2026-03-13 17:57:01', 18),
(3, 'Funko Pop! Eren Yeager', 'Funko', 'Figura Funko Pop! de Eren Yeager con su equipamiento de maniobras tridimensionales. De la serie Shingeki no Kyojin. Tama?o aproximado: 9,5 cm.', 'Funko_Pop_Eren_Yeager.png', 14.99, '2026-03-13 17:57:01', 20),
(4, 'Funko Pop! Goku Super Saiyan', 'Funko', 'Figura Funko Pop! de Goku en su transformaci?n Super Saiyan con aura dorada. De la serie Dragon Ball Z. Tama?o aproximado: 9,5 cm.', 'Funko_Pop_Goku_Super_Saiyan.png', 16.99, '2026-03-13 17:57:01', 30),
(5, 'Funko Pop! Ichigo Kurosaki', 'Funko', 'Figura Funko Pop! de Ichigo Kurosaki con su zanpakuto Zangetsu. De la serie Bleach. Tama?o aproximado: 9,5 cm.', 'Funko_Pop_Ichigo_Kurosaki.png', 14.99, '2026-03-13 17:57:01', 15),
(6, 'Funko Pop! Levi Ackerman', 'Funko', 'Figura Funko Pop! del Capit?n Levi Ackerman, el soldado m?s fuerte de la humanidad. De la serie Shingeki no Kyojin. Tama?o aproximado: 9,5 cm.', 'Funko_Pop_Levi_Ackerman.png', 15.99, '2026-03-13 17:57:01', 22),
(7, 'Funko Pop! Luffy', 'Funko', 'Figura Funko Pop! de Monkey D. Luffy con su ic?nico sombrero de paja. De la serie One Piece. Tama?o aproximado: 9,5 cm.', 'Funko_Pop_Luffy.png', 14.99, '2026-03-13 17:57:01', 28),
(8, 'Funko Pop! Nezuko Kamado', 'Funko', 'Figura Funko Pop! de Nezuko Kamado con su bamb? en la boca. De la serie Demon Slayer: Kimetsu no Yaiba. Tama?o aproximado: 9,5 cm.', 'Funko_Pop_Nezuko_Kamado.png', 15.99, '2026-03-13 17:57:01', 20),
(9, 'Funko Pop! Sailor Moon', 'Funko', 'Figura Funko Pop! de Usagi Tsukino transformada en Sailor Moon con su cetro lunar. De la serie Sailor Moon. Tama?o aproximado: 9,5 cm.', 'Funko_Pop_Sailor_Moon.png', 14.99, '2026-03-13 17:57:01', 17),
(10, 'Funko Pop! Sasuke Uchiha', 'Funko', 'Figura Funko Pop! de Sasuke Uchiha con su Sharingan activado. De la serie Naruto Shippuden. Tama?o aproximado: 9,5 cm.', 'Funko_Pop_Sasuke_Uchiha.png', 14.99, '2026-03-13 17:57:01', 23),
(11, 'Funko Pop! Spike Spiegel', 'Funko', 'Figura Funko Pop! de Spike Spiegel, el carism?tico cazarrecompensas espacial. De la serie Cowboy Bebop. Tama?o aproximado: 9,5 cm.', 'Funko_Pop_Spike_Spiegel.png', 16.99, '2026-03-13 17:57:01', 12),
(12, 'Funko Pop! Tanjiro Kamado', 'Funko', 'Figura Funko Pop! de Tanjiro Kamado con su espada de agua. De la serie Demon Slayer: Kimetsu no Yaiba. Tama?o aproximado: 9,5 cm.', 'Funko_Pop_Tanjiro_Kamado.png', 14.99, '2026-03-13 17:57:01', 25),
(13, 'Funko Pop! Totoro', 'Funko', 'Figura Funko Pop! del adorable Totoro, el esp?ritu del bosque. De la pel?cula Mi Vecino Totoro de Studio Ghibli. Tama?o aproximado: 9,5 cm.', 'Funko_Pop_Totoro.png', 17.99, '2026-03-13 17:57:01', 19),
(14, 'Funko Pop! Vegeta', 'Funko', 'Figura Funko Pop! del Pr?ncipe Vegeta en su pose de combate. De la serie Dragon Ball Z. Tama?o aproximado: 9,5 cm.', 'Funko_Pop_Vegeta.png', 15.99, '2026-03-13 17:57:01', 24),
(15, 'Funko Pop! Zoro', 'Funko', 'Figura Funko Pop! de Roronoa Zoro con sus tres espadas. De la serie One Piece. Tama?o aproximado: 9,5 cm.', 'Funko_Pop_Zoro.png', 14.99, '2026-03-13 17:57:01', 21),
(16, 'Bleach Vol. 1', 'Cómic', 'Primer volumen del manga Bleach de Tite Kubo. Ichigo Kurosaki, un joven que puede ver fantasmas, obtiene los poderes de un Shinigami. Edici?n en espa?ol.', 'Bleach_Vol._1.png', 8.99, '2026-03-13 17:57:01', 35),
(17, 'Chainsaw Man Vol. 1', 'Cómic', 'Primer volumen del manga Chainsaw Man de Tatsuki Fujimoto. Denji, un joven cazador de demonios, se fusiona con su demonio motosierra Pochita. Edici?n en espa?ol.', 'Chainsaw_Man_Vol._1.png', 8.99, '2026-03-13 17:57:01', 40),
(18, 'Cowboy Bebop Vol. 1', 'Cómic', 'Primer volumen del manga Cowboy Bebop de Yutaka Nanten. Sigue las aventuras del cazarrecompensas Spike Spiegel y su tripulaci?n a bordo de la Bebop. Edici?n en espa?ol.', 'Cowboy_Bebop_Vol._1.png', 9.99, '2026-03-13 17:57:01', 15),
(19, 'Death Note Vol. 1', 'Cómic', 'Primer volumen del manga Death Note de Tsugumi Ohba y Takeshi Obata. Light Yagami encuentra un cuaderno sobrenatural que puede matar a cualquiera. Edici?n en espa?ol.', 'Death_Note_Vol._1.png', 8.99, '2026-03-13 17:57:01', 38),
(20, 'Demon Slayer Vol. 1', 'Cómic', 'Primer volumen del manga Kimetsu no Yaiba de Koyoharu Gotouge. Tanjiro Kamado emprende un viaje para salvar a su hermana convertida en demonio. Edici?n en espa?ol.', 'Demon_Slayer_Vol._1.png', 8.99, '2026-03-13 17:57:01', 42),
(21, 'Dragon Ball Vol. 1', 'Cómic', 'Primer volumen del manga Dragon Ball de Akira Toriyama. Conoce al joven Son Goku y el inicio de su ?pica aventura en busca de las Bolas de Drag?n. Edici?n en espa?ol.', 'Dragon_Ball_Vol._1.png', 9.99, '2026-03-13 17:57:01', 30),
(22, 'Fullmetal Alchemist Vol. 1', 'Cómic', 'Primer volumen del manga Fullmetal Alchemist de Hiromu Arakawa. Los hermanos Elric buscan la Piedra Filosofal para recuperar sus cuerpos. Edici?n en espa?ol.', 'Fullmetal_Alchemist_Vol._1.png', 8.99, '2026-03-13 17:57:01', 28),
(23, 'Hunter x Hunter Vol. 1', 'Cómic', 'Primer volumen del manga Hunter x Hunter de Yoshihiro Togashi. Gon Freecss se embarca en el examen de cazador para encontrar a su padre. Edici?n en espa?ol.', 'Hunter_x_Hunter_Vol._1.png', 8.99, '2026-03-13 17:57:01', 25),
(24, 'Jujutsu Kaisen Vol. 1', 'Cómic', 'Primer volumen del manga Jujutsu Kaisen de Gege Akutami. Yuji Itadori ingiere un dedo maldito y entra al mundo de la hechicer?a jujutsu. Edici?n en espa?ol.', 'Jujutsu_Kaisen_Vol._1.png', 8.99, '2026-03-13 17:57:01', 45),
(25, 'My Hero Academia Vol. 1', 'Cómic', 'Primer volumen del manga My Hero Academia de Kohei Horikoshi. Izuku Midoriya, un chico sin poderes, sue?a con ser el mayor h?roe del mundo. Edici?n en espa?ol.', 'My_Hero_Academia_Vol._1.png', 8.99, '2026-03-13 17:57:01', 33),
(26, 'Naruto Vol. 1', 'Cómic', 'Primer volumen del manga Naruto de Masashi Kishimoto. Naruto Uzumaki, un joven ninja hiperactivo, sue?a con convertirse en Hokage. Edici?n en espa?ol.', 'Naruto_Vol._1.png', 8.99, '2026-03-13 17:57:01', 36),
(27, 'One Piece Vol. 1', 'Cómic', 'Primer volumen del manga One Piece de Eiichiro Oda. Monkey D. Luffy zarpa en busca del tesoro One Piece para convertirse en el Rey de los Piratas. Edici?n en espa?ol.', 'One_Piece_Vol._1.png', 8.99, '2026-03-13 17:57:01', 40),
(28, 'Sailor Moon Vol. 3', 'Cómic', 'Tercer volumen del manga Sailor Moon de Naoko Takeuchi. Usagi y las Sailor Scouts enfrentan nuevas amenazas mientras descubren m?s sobre su pasado. Edici?n en espa?ol.', 'Sailor_Moon_Vol._3.png', 9.99, '2026-03-13 17:57:01', 20),
(29, 'Shingeki no Kyojin Vol. 1', 'Cómic', 'Primer volumen del manga Attack on Titan de Hajime Isayama. La humanidad lucha por sobrevivir tras las murallas que la protegen de los titanes. Edici?n en espa?ol.', 'Shingeki_no_Kyojin_Vol._1.png', 8.99, '2026-03-13 17:57:01', 32),
(30, 'Tokyo Ghoul Vol. 1', 'Cómic', 'Primer volumen del manga Tokyo Ghoul de Sui Ishida. Ken Kaneki se convierte en un medio ghoul tras un encuentro con una misteriosa chica. Edición en español.', 'Tokyo_Ghoul_Vol._1.png', 8.99, '2026-03-13 17:57:01', 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre_usuario` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `rol` enum('cliente','admin') DEFAULT 'cliente',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre_usuario`, `email`, `contraseña`, `rol`, `fecha_registro`) VALUES
(1, 'RotaruX', 'rotarualex1612@gmail.com', '$2y$10$1nkIKAV4r5tjUmmDY4iA4ONO5XaWlYzeapSf9zHq0P8D1pZgsfoRa', 'admin', '2026-03-13 18:00:09'),
(2, 'Sebas', 'sebastian@gmail.com', '$2y$10$.PQMEVgCyiq9f3n2IRqKm.HiRF/MHnUUewK.oSN1MkEcSoOKRbGKe', 'cliente', '2026-03-14 09:59:05');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD PRIMARY KEY (`id_carrito`),
  ADD UNIQUE KEY `unique_user_product` (`id_usuario`,`id_producto`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `compras`
--
ALTER TABLE `compras`
  ADD PRIMARY KEY (`id_compra`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `detalles_compra`
--
ALTER TABLE `detalles_compra`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `id_compra` (`id_compra`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `mensajes_contacto`
--
ALTER TABLE `mensajes_contacto`
  ADD PRIMARY KEY (`id_mensaje`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `nombre_usuario` (`nombre_usuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `carrito`
--
ALTER TABLE `carrito`
  MODIFY `id_carrito` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `compras`
--
ALTER TABLE `compras`
  MODIFY `id_compra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `detalles_compra`
--
ALTER TABLE `detalles_compra`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `mensajes_contacto`
--
ALTER TABLE `mensajes_contacto`
  MODIFY `id_mensaje` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD CONSTRAINT `carrito_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `carrito_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `compras`
--
ALTER TABLE `compras`
  ADD CONSTRAINT `compras_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalles_compra`
--
ALTER TABLE `detalles_compra`
  ADD CONSTRAINT `detalles_compra_ibfk_1` FOREIGN KEY (`id_compra`) REFERENCES `compras` (`id_compra`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalles_compra_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
