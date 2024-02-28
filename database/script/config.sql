-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 28-02-2024 a las 21:50:30
-- Versión del servidor: 8.0.30
-- Versión de PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `proto_app_urban_transport`
--

--
-- Volcado de datos para la tabla `config`
--

INSERT INTO `config` (`config_id`, `name`, `value`, `description`, `created_at`, `updated_at`) VALUES
(1, 'time_between_stops', '1.5', 'tiempo de viaje entre parada en minutos es float ', '2024-02-22 18:32:40', '2024-02-22 18:32:40'),
(2, 'time_in_stop', '0.33', 'tiempo de espera en parada medido en minutos es float', '2024-02-22 18:32:40', '2024-02-22 18:32:40');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
