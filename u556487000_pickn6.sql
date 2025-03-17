-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 20-02-2025 a las 00:28:25
-- Versión del servidor: 10.11.10-MariaDB
-- Versión de PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `u556487000_pickn6`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administrators`
--

CREATE TABLE `administrators` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` enum('admin','editor') NOT NULL DEFAULT 'editor'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `administrators`
--

INSERT INTO `administrators` (`id`, `name`, `email`, `email_verified_at`, `password`, `is_active`, `remember_token`, `created_at`, `updated_at`, `role`) VALUES
(1, 'Admin', 'admin@example.com', '2025-02-18 22:46:34', '$2y$12$wZTIPf1FKRlcmpXecILkteHBETaJdvZjfUTJSCxIpZO/ghODmMyz6', 1, NULL, '2025-02-18 22:46:34', '2025-02-18 22:46:34', 'editor'),
(2, 'mario', 'marioreque81@gmail.com', NULL, '$2y$12$pd89VUep/akla1YJwNRerui6kr2zKL2diGx3hRQpvUEmdqf22vY/m', 1, NULL, '2025-02-18 22:55:28', '2025-02-18 22:55:28', 'admin');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bids`
--

CREATE TABLE `bids` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `bideable_type` varchar(255) NOT NULL,
  `bideable_id` bigint(20) UNSIGNED NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `comentario` text DEFAULT NULL,
  `estado` enum('pendiente','aceptado','rechazado') NOT NULL DEFAULT 'pendiente',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('marioreque88@gmail.com|189.28.64.159', 'i:1;', 1739980116),
('marioreque88@gmail.com|189.28.64.159:timer', 'i:1739980116;', 1739980116),
('mono@mono.com|189.28.64.159', 'i:1;', 1739979658),
('mono@mono.com|189.28.64.159:timer', 'i:1739979658;', 1739979658);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cargo_types`
--

CREATE TABLE `cargo_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cargo_types`
--

INSERT INTO `cargo_types` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Carga General', 'Carga general', '2025-02-18 22:46:34', '2025-02-18 22:46:34'),
(2, 'Productos Perecederos', 'Productos perecederos', '2025-02-18 22:46:34', '2025-02-18 22:46:34'),
(3, 'Materiales Peligrosos', 'Materiales peligrosos', '2025-02-18 22:46:34', '2025-02-18 22:46:34');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_02_15_000000_create_bids_table', 1),
(5, '2025_02_13_042638_add_user_verified_to_users_table', 1),
(6, '2025_02_13_045820_create_truck_types_table', 1),
(7, '2025_02_13_045821_create_cargo_types_table', 1),
(8, '2025_02_13_153704_oferta_rutas', 1),
(9, '2025_02_14_000000_create_administrators_table', 1),
(10, '2025_02_14_220914_ofertas_carga', 1),
(11, '2025_02_15_000000_add_role_to_administrators_table', 2),
(12, '2025_02_15_000001_create_required_documents_table', 3),
(13, '2024_02_20_000000_create_user_documents_table', 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ofertas_carga`
--

CREATE TABLE `ofertas_carga` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `tipo_carga` varchar(255) NOT NULL,
  `origen` varchar(255) NOT NULL,
  `destino` varchar(255) NOT NULL,
  `peso` decimal(8,2) NOT NULL,
  `fecha_inicio` datetime NOT NULL,
  `presupuesto` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `ofertas_carga`
--

INSERT INTO `ofertas_carga` (`id`, `user_id`, `tipo_carga`, `origen`, `destino`, `peso`, `fecha_inicio`, `presupuesto`, `created_at`, `updated_at`) VALUES
(1, 2, '2', 'Wisconsin, EE. UU.', 'Wisconsin, EE. UU.', 1232.00, '2333-03-12 00:00:00', 213123.00, '2025-02-19 02:00:53', '2025-02-19 02:00:53');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ofertas_ruta`
--

CREATE TABLE `ofertas_ruta` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `tipo_camion` varchar(255) NOT NULL,
  `origen` varchar(255) NOT NULL,
  `destino` varchar(255) NOT NULL,
  `fecha_inicio` datetime NOT NULL,
  `capacidad` int(11) NOT NULL,
  `precio_referencial` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `required_documents`
--

CREATE TABLE `required_documents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `required_documents`
--

INSERT INTO `required_documents` (`id`, `name`, `description`, `notes`, `active`, `created_at`, `updated_at`) VALUES
(1, 'Carnet de identidad', 'carnet de identidad', 'carnet de identidad', 1, '2025-02-18 23:54:07', '2025-02-18 23:54:07'),
(8, 'adasd', 'adasdasd', 'adasdasd', 1, '2025-02-19 19:12:31', '2025-02-19 19:12:31'),
(9, 'asdasd', 'asdas', 'asdsad', 1, '2025-02-19 19:24:49', '2025-02-19 19:24:49');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('3hneOFFJJpc97szwKE52WdD4n2e9UWwFLS8iFZrN', NULL, '189.28.64.159', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoidUZDbm4ydFRDazRUNTE0S2dFQzRiaG1QaGVDbGE4SHdGMzZZYUVPRyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1739980857),
('7JY8PFFEFIbTEuz11cRhEjPydqZ2vakAPfQ9CxDR', NULL, '189.28.64.159', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoidnZ0WkRlMWhEZmpUUWpWdW9jTm16S0dNb0tnOHNPMUZlcmRGUXJ6VSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1739980823),
('8j7fOKliuA1Y5BD0vyBVyFM8WXIOyyeZZIiIDkyY', NULL, '189.28.64.159', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiaWRkRjRmYWlDQ3RHZ016ZGVWNmt4SHpqODZ2SE5EelFFVkROVndsSCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly9tYW5hZ2VyLnBpY2tudHJ1Y2suY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1739980763),
('aW1uKvOoBAAUZSXAf7ZeJPORz2e02VleKKzkZJwp', 4, '2803:9400:3:dbf0:1ba:cad4:c811:820', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Mobile Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiY1lNdXJ2M1JvU0RydjZDU3FmTm5nQzZMQkc4cllzcVlLN0d6UmxNaiI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIyOiJodHRwczovL3BpY2tudHJ1Y2suY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NDt9', 1739984992),
('BNCMAxzqWv9PlrDM5pYRIx6bUJ28VPq3gLgC3bKc', NULL, '189.28.64.159', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiVHBPcmF6VnlEbGlJN1NZNTdMRGRHcjJPd2h5SnB2cFg2cTRORDcwVCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozOToiaHR0cDovL21hbmFnZXIucGlja250cnVjay5jb20vZGFzaGJvYXJkIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly9tYW5hZ2VyLnBpY2tudHJ1Y2suY29tL2Rhc2hib2FyZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1739980984),
('Chuz31tFaYwb90OPJukn8oYCQsQXLuY1xt7smVJX', 4, '189.28.64.159', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoic2RuaFBhQlR4SWY5MEgwc0RBbW1PdUtoUDhXWTdNNW5uVlV0VnRESyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjI6Imh0dHBzOi8vcGlja250cnVjay5jb20iO31zOjM6InVybCI7YToxOntzOjg6ImludGVuZGVkIjtzOjIyOiJodHRwczovL3BpY2tudHJ1Y2suY29tIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NDt9', 1739980241),
('DOBS4ImXGw6wL4E6XhLzP2joYekYtpWJOuwfFk1U', NULL, '189.28.64.159', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiMVJDemtLVkthcmJ2WlFWRHNtbFE2ZFk3Z005NFZNMGV5QmZJVmNOTyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1739980808),
('EPBJ05jVU5SczQL2QiRAft38pljjKYNUQDlaNm1P', NULL, '189.28.64.159', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNEFtcElhQ1d6ZTd6YUd2bmJMSTJNeFUyRlhuV1pOQlFyTHliYVhMMyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly9tYW5hZ2VyLnBpY2tudHJ1Y2suY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1739980927),
('eS7Lelv3pMzMGe6SzFnVmoR4cHqUIuCLZWDFyDy6', NULL, '189.28.64.159', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiMkdzVjJ2VHlKQ2UxUG15RWdvTks3QTkweE10NUVmZGtEdHpYbXJtOCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1739980938),
('G2AxSTq9bqyfhgCQACIHUyDdYMAiLCLQaSBj4yrM', 2, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoidVR1bm5oNjNBMlphS1A0YzRMV2dtY252TEZoYXNxODdjNWowS3E3UCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMSI7fXM6MzoidXJsIjthOjA6e31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO30=', 1739978420),
('J8aW7zGw9WvpJ999Su3bOJoGJuRGDNGJd2Ng5gOA', 5, '189.28.83.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiM2NUTHQ0NGxxVDYydHhJbnN4SkJ3a3lmRWxIUExydERKRVhaZDk2MiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzY6Imh0dHBzOi8vcGlja250cnVjay5jb20vb2ZlcnRhc19jYXJnYSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjU7fQ==', 1739981976),
('L4wC2qQB2DnM5oqueLkyxLHUO9ReGDXMePWHy9xO', NULL, '189.28.64.159', 'WhatsApp/2.23.20.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMVJXWmUxTTA2SUp2NmM3emg5WFZpdjZoQmFVODYxUHRnR0hoNzVCeSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHBzOi8vcGlja250cnVjay5jb20vcmVnaXN0ZXIiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1739980108),
('N72gazFxoo9H8tJBoNzVLQUMoHxTklIzoMGz8SBw', NULL, '2a02:4780:b:7::9', 'Go-http-client/1.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiOTFnUHlUaDBYaHlPR2lkQ3Y0M0pKRjJ5aWpnWWthbnh4YlV0RVdyWSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly9tYW5hZ2VyLnBpY2tudHJ1Y2suY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1740011136),
('OAojmEqRzl8NjXz8oAkq0UvmibprDjuVmjLJCodF', NULL, '189.28.64.159', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZGFhUjVkb2pwekRtTWtMQVR0WE10aGZnQWkzdVFOWFJicVh0Q1J2aCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly9tYW5hZ2VyLnBpY2tudHJ1Y2suY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1739980829),
('Oaw7xyMNnzkyz1FhU59e8HqmH2tMl37YR9yq6lhL', NULL, '189.28.64.159', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoib05qOU9TZjdvTWVtSUJFVHdOY0FUMmRGV0tLeDlzVk9xQVVKOFRQZyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly9tYW5hZ2VyLnBpY2tudHJ1Y2suY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1739980970),
('ozzTszrSPg6sfrzmHlLwjyyLGvKPSTSKfvpm3o2M', NULL, '2a02:4780:b:7::9', 'Go-http-client/1.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNkxLUmNWcG1nQ0VxNG1KZzBEOTY3b0ZmSnR6eUR3b0xmaWVSdVNJSCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly9tYW5hZ2VyLnBpY2tudHJ1Y2suY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1740011136),
('PxLiCcd5BqDwYkwCmoSsymsT3unm73qXB5QrWMN6', 2, '189.28.64.159', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRUppT0pyWmJ4YldsZE1sbHR5ODVuNWZGQm51VVRjbkdTVzZNZGpBYyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly9tYW5hZ2VyLnBpY2tudHJ1Y2suY29tL2RvY3VtZW50cyI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7fQ==', 1740004522),
('QOWiVxRr9hnkay9dRTDzRafqnQ8Vr19yKMtN9jEj', NULL, '189.28.64.159', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMHNrbHdnQ1NxdXN0OW9HZllZd0NRNXZlMU41TmJ4MzlaSGR3UjcwTyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly9tYW5hZ2VyLnBpY2tudHJ1Y2suY29tL3JlZ2lzdGVyIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1739980788),
('T9UB9VAhYPL3VchaFNhZrBpbyUMpVcNzB12H7THA', NULL, '189.28.64.159', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZmg2bmtNU2tzR3BFTmZkeUgzRG56S282bnpKV0l6dThGeVk3aHp4bSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly9tYW5hZ2VyLnBpY2tudHJ1Y2suY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1739980763),
('Ucpq5qDMOpF2nVXjIgvWsIacy0Uktgp5imTkmnGX', NULL, '189.28.64.159', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiREt1Y3M0elZWTWh5d3FzTnhZNmtnaVlRVGplZnRMQjR5RTZTSlhKciI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly9tYW5hZ2VyLnBpY2tudHJ1Y2suY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1739980829),
('UeHuYm05JgHC0ZlgwDl8t6akIdQQmGOAKhm2yYnL', 1, '189.28.83.58', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/132.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiczRqWks1aTcxRTBRVHdWQ29GczNyYnpTUDEwSVBVNW1xdzl1eHNhZSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly9tYW5hZ2VyLnBpY2tudHJ1Y2suY29tL2Rhc2hib2FyZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1739999770),
('WyRGXxr738SOxeoJ6LWWyFdLYZH6rCe9m62a4I6O', NULL, '189.28.64.159', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidjNDUVh3UHJ6UFR2Y25kNUxMQ1ZoT3ZvdVh4S0hLeWhOMlh4bTM3RSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly9tYW5hZ2VyLnBpY2tudHJ1Y2suY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1739980984),
('X3sFJgJrtJCeECB7HHAfXMDm6KEO2Al7KY3BBUhA', NULL, '189.28.64.159', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiSVMyZ2ZlMUtUbWJXZFVzaUFyRFAxSU9tZ2hkOENjRFU0andkS0NYMCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1739980923),
('XJz9XZTPNVXnKsK5KoswTDuWdiNWawzwrA35jk3M', NULL, '2001:4860:7:302::fb', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiYWRockNzQjRsZ0FLYmVtMlRCSVhiRXRsZ1JGRk5UZFpEeGZiU2N0ZiI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyMjoiaHR0cHM6Ly9waWNrbnRydWNrLmNvbSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1739985938),
('yJrGKd59eyjKaSjcRmLhk5vJZHXuDTopQFipM8ul', NULL, '200.87.246.137', 'Mozilla/5.0 (Linux; Android 13; TECNO BG6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Mobile Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNFp2ODZVM0xid2RiUk9keHVSaFdFbGpFdkY2bWc4bEt1Q0ZxNExVUyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjg6Imh0dHBzOi8vcGlja250cnVjay5jb20vbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1739984992);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_cargas`
--

CREATE TABLE `tipo_cargas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `truck_types`
--

CREATE TABLE `truck_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `truck_types`
--

INSERT INTO `truck_types` (`id`, `name`, `description`, `active`, `created_at`, `updated_at`) VALUES
(1, 'Camión Plataforma', 'Camión con plataforma', 1, '2025-02-18 22:46:34', '2025-02-18 22:46:34'),
(2, 'Camión Caja', 'Camión con caja cerrada', 1, '2025-02-18 22:46:34', '2025-02-18 22:46:34'),
(3, 'Camión Refrigerado', 'Camión con refrigeración', 1, '2025-02-18 22:46:34', '2025-02-18 22:46:34');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `user_verified` tinyint(1) NOT NULL DEFAULT 0,
  `verified` tinyint(1) NOT NULL DEFAULT 0,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `user_verified`, `verified`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'hola', 'hola@bola.com', NULL, '$2y$12$A7x16HBLLj2V8uxtIP4Y9OUhP/M9N0LsUd31YjwBfcFBL.KmqOmBW', 0, 0, NULL, '2025-02-18 22:48:15', '2025-02-19 18:05:13'),
(2, 'mario', 'marioreque88@hotmail.com', '2025-02-19 18:23:31', '$2y$12$SU93ao5IgEAX2Pz5opgRD.E1iejr1G.ZPgfA7wGwV5SxX2ZFnJkMK', 0, 1, NULL, '2025-02-18 23:04:38', '2025-02-19 18:23:31'),
(3, 'mario', 'marioreque88@gmail.com', NULL, '$2y$12$j.mNcXp4hhSz7gu3VF/Qa.gLGYksFaorm8zK2GQCw8j8v7A8gZgQq', 0, 0, NULL, '2025-02-19 15:40:39', '2025-02-19 15:40:39'),
(4, 'Mario Roberto Reque Santivañez', 'marioreque81@gmail.com', NULL, '$2y$12$WLoGeGYTpVucc2h6ABXAf.nmkJEe8V5hQbopAt5jTMr.ad2cLHDOu', 0, 0, NULL, '2025-02-19 15:49:18', '2025-02-19 15:49:18'),
(5, 'Operador', 'touchandimport@hotmail.com', NULL, '$2y$12$dEas4gOs99pCc3OASwKA6OEMPCXqSBm8Ne7ANPBG.Gn2d0Juz26.y', 0, 0, NULL, '2025-02-19 16:13:40', '2025-02-19 16:13:40');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_documents`
--

CREATE TABLE `user_documents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `required_document_id` bigint(20) UNSIGNED NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `status` enum('pendiente','aprobado','rechazado') NOT NULL DEFAULT 'pendiente',
  `comments` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `user_documents`
--

INSERT INTO `user_documents` (`id`, `user_id`, `required_document_id`, `file_path`, `status`, `comments`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 'documents/2/XBm2cvbyvCnfjNA2gihxZ8ni6kb08elhxcQ3WZqj.pdf', 'aprobado', 'me emputas', '2025-02-19 01:55:40', '2025-02-19 19:11:36'),
(2, 4, 1, 'documents/4/wtxzfunVvdhwSSzC7R6jknAy8eoAfAI5Ur1xkTah.jpg', 'pendiente', NULL, '2025-02-19 15:49:42', '2025-02-19 15:49:42');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administrators`
--
ALTER TABLE `administrators`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `administrators_email_unique` (`email`);

--
-- Indices de la tabla `bids`
--
ALTER TABLE `bids`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bids_bideable_type_bideable_id_index` (`bideable_type`,`bideable_id`),
  ADD KEY `bids_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `cargo_types`
--
ALTER TABLE `cargo_types`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indices de la tabla `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indices de la tabla `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ofertas_carga`
--
ALTER TABLE `ofertas_carga`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ofertas_carga_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `ofertas_ruta`
--
ALTER TABLE `ofertas_ruta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ofertas_ruta_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indices de la tabla `required_documents`
--
ALTER TABLE `required_documents`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indices de la tabla `tipo_cargas`
--
ALTER TABLE `tipo_cargas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `truck_types`
--
ALTER TABLE `truck_types`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indices de la tabla `user_documents`
--
ALTER TABLE `user_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_documents_user_id_foreign` (`user_id`),
  ADD KEY `user_documents_required_document_id_foreign` (`required_document_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `administrators`
--
ALTER TABLE `administrators`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `bids`
--
ALTER TABLE `bids`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cargo_types`
--
ALTER TABLE `cargo_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `ofertas_carga`
--
ALTER TABLE `ofertas_carga`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `ofertas_ruta`
--
ALTER TABLE `ofertas_ruta`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `required_documents`
--
ALTER TABLE `required_documents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `tipo_cargas`
--
ALTER TABLE `tipo_cargas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `truck_types`
--
ALTER TABLE `truck_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `user_documents`
--
ALTER TABLE `user_documents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `bids`
--
ALTER TABLE `bids`
  ADD CONSTRAINT `bids_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `ofertas_carga`
--
ALTER TABLE `ofertas_carga`
  ADD CONSTRAINT `ofertas_carga_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `ofertas_ruta`
--
ALTER TABLE `ofertas_ruta`
  ADD CONSTRAINT `ofertas_ruta_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Filtros para la tabla `user_documents`
--
ALTER TABLE `user_documents`
  ADD CONSTRAINT `user_documents_required_document_id_foreign` FOREIGN KEY (`required_document_id`) REFERENCES `required_documents` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_documents_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
