-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 21, 2024 at 05:14 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `aplikasi_sjt`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(3, 'admin1', '$2y$12$EuoTSfX4VJDupdAkAq65DO1iTsuEsOJJYiAWegaPKC.WInLdGoRvq', NULL, NULL, NULL),
(4, 'admin2', '$2y$12$4D4MHHBtoXb80We6zEtMP.ii5eWOc4YdNE1q3JvjPKXhoglb7C14i', NULL, NULL, NULL),
(7, 'admin3', '$2y$12$j0nBR2ENRlLnR/sdWWPKcOsW1zKUcntQpTr8bQH7X0/f6lICUiRMu', NULL, '2024-08-04 20:50:20', '2024-08-04 20:50:20');

-- --------------------------------------------------------

--
-- Table structure for table `answers`
--

CREATE TABLE `answers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `question_id` bigint(20) UNSIGNED NOT NULL,
  `answer_text` varchar(255) NOT NULL,
  `score` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `answers`
--

INSERT INTO `answers` (`id`, `question_id`, `answer_text`, `score`, `created_at`, `updated_at`) VALUES
(62, 15, 'Adidas', 4, '2024-07-28 20:42:37', '2024-07-28 20:42:37'),
(63, 15, 'Nike', 3, '2024-07-28 20:42:37', '2024-07-28 20:42:37'),
(64, 15, 'New Balance', 2, '2024-07-28 20:42:37', '2024-07-28 20:42:37'),
(65, 15, 'Puma', 1, '2024-07-28 20:42:37', '2024-07-28 20:42:37'),
(66, 16, 'Apel', 4, '2024-07-28 20:43:21', '2024-07-28 20:43:21'),
(67, 16, 'Pisang', 3, '2024-07-28 20:43:21', '2024-07-28 20:43:21'),
(68, 16, 'Jeruk', 2, '2024-07-28 20:43:21', '2024-07-28 20:43:21'),
(69, 16, 'Apel', 1, '2024-07-28 20:43:21', '2024-07-28 20:43:21'),
(70, 17, 'Wortel', 4, '2024-07-28 20:44:15', '2024-07-28 20:44:15'),
(71, 17, 'Lobak', 3, '2024-07-28 20:44:15', '2024-07-28 20:44:15'),
(72, 17, 'Daun Singkong', 2, '2024-07-28 20:44:15', '2024-07-28 20:44:15'),
(73, 17, 'Sawi', 1, '2024-07-28 20:44:15', '2024-07-28 20:44:15'),
(74, 18, 'Cristiano Ronaldo', 4, '2024-07-28 20:45:16', '2024-07-28 20:45:16'),
(75, 18, 'Lionel Messi', 3, '2024-07-28 20:45:16', '2024-07-28 20:45:16'),
(76, 18, 'Neymar Jr', 2, '2024-07-28 20:45:16', '2024-07-28 20:45:16'),
(77, 18, 'Wayne Rooney', 1, '2024-07-28 20:45:16', '2024-07-28 20:45:16'),
(78, 19, 'Valentino Rossi', 4, '2024-07-28 20:46:10', '2024-07-28 20:46:10'),
(79, 19, 'Nicky Hayden', 3, '2024-07-28 20:46:10', '2024-07-28 20:46:10'),
(80, 19, 'Casey Stoner', 2, '2024-07-28 20:46:10', '2024-07-28 20:46:10'),
(81, 19, 'Jorge Lorenzo', 1, '2024-07-28 20:46:10', '2024-07-28 20:46:10'),
(82, 20, 'Manchester United', 4, '2024-07-28 20:47:08', '2024-07-28 20:47:08'),
(83, 20, 'Real Madrid', 3, '2024-07-28 20:47:08', '2024-07-28 20:47:08'),
(84, 20, 'AC Milan', 2, '2024-07-28 20:47:08', '2024-07-28 20:47:08'),
(85, 20, 'Bayern Munich', 1, '2024-07-28 20:47:08', '2024-07-28 20:47:08'),
(86, 21, 'Komodo', 4, '2024-07-28 20:50:59', '2024-07-28 20:50:59'),
(87, 21, 'Burung', 3, '2024-07-28 20:50:59', '2024-07-28 20:50:59'),
(88, 21, 'Buaya', 2, '2024-07-28 20:50:59', '2024-07-28 20:50:59'),
(89, 21, 'Harimau', 1, '2024-07-28 20:50:59', '2024-07-28 20:50:59'),
(90, 22, 'Semut', 4, '2024-07-28 20:51:26', '2024-07-28 20:51:26'),
(91, 22, 'Nyamuk', 3, '2024-07-28 20:51:26', '2024-07-28 20:51:26'),
(92, 22, 'Belalang', 2, '2024-07-28 20:51:26', '2024-07-28 20:51:26'),
(93, 22, 'Jangkrik', 1, '2024-07-28 20:51:26', '2024-07-28 20:51:26'),
(94, 23, 'Hiu', 4, '2024-07-28 20:51:51', '2024-07-28 20:51:51'),
(95, 23, 'Paus', 3, '2024-07-28 20:51:51', '2024-07-28 20:51:51'),
(96, 23, 'Piranha', 2, '2024-07-28 20:51:51', '2024-07-28 20:51:51'),
(97, 23, 'Megalodon', 1, '2024-07-28 20:51:51', '2024-07-28 20:51:51'),
(98, 24, 'Dangdut', 4, '2024-07-28 20:52:33', '2024-07-28 20:52:33'),
(99, 24, 'Jazz', 3, '2024-07-28 20:52:33', '2024-07-28 20:52:33'),
(100, 24, 'Pop', 2, '2024-07-28 20:52:33', '2024-07-28 20:52:33'),
(101, 24, 'Hiphop', 1, '2024-07-28 20:52:33', '2024-07-28 20:52:33'),
(102, 25, 'Erik Ten Hag', 4, '2024-07-28 20:53:29', '2024-07-28 20:53:29'),
(103, 25, 'Sir Alex Ferguson', 3, '2024-07-28 20:53:29', '2024-07-28 20:53:29'),
(104, 25, 'Indra Sjafri', 2, '2024-07-28 20:53:29', '2024-07-28 20:53:29'),
(105, 25, 'Maman Abdurahman', 1, '2024-07-28 20:53:29', '2024-07-28 20:53:29'),
(106, 26, 'Real Madrid', 4, '2024-07-28 20:54:40', '2024-07-28 20:54:40'),
(107, 26, 'Manchester United', 3, '2024-07-28 20:54:40', '2024-07-28 20:54:40'),
(108, 26, 'AC Milan', 2, '2024-07-28 20:54:40', '2024-07-28 20:54:40'),
(109, 26, 'PSG', 1, '2024-07-28 20:54:40', '2024-07-28 20:54:40'),
(110, 27, '2003', 4, '2024-07-28 20:55:25', '2024-07-28 20:55:25'),
(111, 27, '2001', 3, '2024-07-28 20:55:25', '2024-07-28 20:55:25'),
(112, 27, '1946', 2, '2024-07-28 20:55:25', '2024-07-28 20:55:25'),
(113, 27, '2008', 1, '2024-07-28 20:55:25', '2024-07-28 20:55:25'),
(114, 28, '2009', 4, '2024-07-28 20:56:04', '2024-07-28 20:56:04'),
(115, 28, '2010', 3, '2024-07-28 20:56:04', '2024-07-28 20:56:04'),
(116, 28, '2012', 2, '2024-07-28 20:56:04', '2024-07-28 20:56:04'),
(117, 28, '2003', 1, '2024-07-28 20:56:04', '2024-07-28 20:56:04'),
(118, 29, '3', 4, '2024-07-28 20:57:09', '2024-07-28 20:57:09'),
(119, 29, '2', 3, '2024-07-28 20:57:09', '2024-07-28 20:57:09'),
(120, 29, '1', 2, '2024-07-28 20:57:09', '2024-07-28 20:57:09'),
(121, 29, '15', 1, '2024-07-28 20:57:09', '2024-07-28 20:57:09'),
(122, 30, 'Johan Cruyf', 4, '2024-07-28 20:58:29', '2024-07-28 20:58:29'),
(123, 30, 'Antony Santos \"el-Gasing\"', 3, '2024-07-28 20:58:29', '2024-07-28 20:58:29'),
(124, 30, 'Maradona', 2, '2024-07-28 20:58:29', '2024-07-28 20:58:29'),
(125, 30, 'Pele', 1, '2024-07-28 20:58:29', '2024-07-28 20:58:29'),
(126, 31, 'Thomas Alfa Edison', 4, '2024-07-28 20:59:24', '2024-07-28 20:59:24'),
(127, 31, 'Thomas Alfamart', 3, '2024-07-28 20:59:24', '2024-07-28 20:59:24'),
(128, 31, 'Thomas and his friends', 2, '2024-07-28 20:59:24', '2024-07-28 20:59:24'),
(129, 31, 'Thomas Shelby', 1, '2024-07-28 20:59:24', '2024-07-28 20:59:24'),
(130, 32, 'Bruno Fernandes', 4, '2024-07-28 21:00:55', '2024-07-28 21:00:55'),
(131, 32, 'Harry Maguire', 3, '2024-07-28 21:00:55', '2024-07-28 21:00:55'),
(132, 32, 'Antony Santos \"el gasing\"', 2, '2024-07-28 21:00:55', '2024-07-28 21:00:55'),
(133, 32, 'Marcus Rashford', 1, '2024-07-28 21:00:55', '2024-07-28 21:00:55'),
(134, 33, 'Belanda', 4, '2024-07-28 21:01:44', '2024-07-28 21:01:44'),
(135, 33, 'Inggris', 3, '2024-07-28 21:01:44', '2024-07-28 21:01:44'),
(136, 33, 'Italia', 2, '2024-07-28 21:01:44', '2024-07-28 21:01:44'),
(137, 33, 'Ngawi', 1, '2024-07-28 21:01:44', '2024-07-28 21:01:44'),
(138, 34, 'Instagram', 4, '2024-07-28 21:02:36', '2024-07-28 21:02:36'),
(139, 34, 'Twitter', 3, '2024-07-28 21:02:36', '2024-07-28 21:02:36'),
(140, 34, 'Facebook', 2, '2024-07-28 21:02:36', '2024-07-28 21:02:36'),
(141, 34, 'Linkedin', 1, '2024-07-28 21:02:36', '2024-07-28 21:02:36'),
(142, 35, 'Vario', 4, '2024-07-28 21:03:07', '2024-07-28 21:03:07'),
(143, 35, 'Beat', 3, '2024-07-28 21:03:07', '2024-07-28 21:03:07'),
(144, 35, 'MegaPro', 2, '2024-07-28 21:03:07', '2024-07-28 21:03:07'),
(145, 35, 'Supra-X', 1, '2024-07-28 21:03:07', '2024-07-28 21:03:07'),
(146, 36, 'Nmax', 4, '2024-07-28 21:03:40', '2024-07-28 21:03:40'),
(147, 36, 'Aerox', 3, '2024-07-28 21:03:40', '2024-07-28 21:03:40'),
(148, 36, 'Mio', 2, '2024-07-28 21:03:40', '2024-07-28 21:03:40'),
(149, 36, 'Vega', 1, '2024-07-28 21:03:40', '2024-07-28 21:03:40'),
(150, 37, 'Semarang', 4, '2024-07-28 21:04:40', '2024-07-28 21:04:40'),
(151, 37, 'Depok', 3, '2024-07-28 21:04:40', '2024-07-28 21:04:40'),
(152, 37, 'Bogor', 2, '2024-07-28 21:04:40', '2024-07-28 21:04:40'),
(153, 37, 'Bandung', 1, '2024-07-28 21:04:40', '2024-07-28 21:04:40'),
(154, 38, 'Jawa Barat', 4, '2024-07-28 21:05:23', '2024-07-28 21:05:23'),
(155, 38, 'Jawa Tengah', 3, '2024-07-28 21:05:23', '2024-07-28 21:05:23'),
(156, 38, 'Jawa Timur', 2, '2024-07-28 21:05:23', '2024-07-28 21:05:23'),
(157, 38, 'Indonesia', 1, '2024-07-28 21:05:23', '2024-07-28 21:05:23'),
(158, 39, 'Jawa Tengah', 4, '2024-07-28 21:06:00', '2024-07-28 21:06:00'),
(159, 39, 'Jawa Barat', 3, '2024-07-28 21:06:00', '2024-07-28 21:06:00'),
(160, 39, 'Jawa Timur', 2, '2024-07-28 21:06:00', '2024-07-28 21:06:00'),
(161, 39, 'Bali', 1, '2024-07-28 21:06:00', '2024-07-28 21:06:00'),
(162, 40, 'Surabaya', 4, '2024-07-28 21:06:43', '2024-07-28 21:06:43'),
(163, 40, 'Ngawi', 3, '2024-07-28 21:06:43', '2024-07-28 21:06:43'),
(164, 40, 'Pamekasan', 2, '2024-07-28 21:06:43', '2024-07-28 21:06:43'),
(165, 40, 'Palembang', 1, '2024-07-28 21:06:43', '2024-07-28 21:06:43'),
(166, 41, 'Jawa Timur', 4, '2024-07-28 21:07:37', '2024-07-28 21:07:37'),
(167, 41, 'Jawa Tengah', 3, '2024-07-28 21:07:37', '2024-07-28 21:07:37'),
(168, 41, 'Bali', 2, '2024-07-28 21:07:37', '2024-07-28 21:07:37'),
(169, 41, 'Nusa Tenggara Barat', 1, '2024-07-28 21:07:37', '2024-07-28 21:07:37'),
(170, 42, 'Belanda', 4, '2024-07-28 21:08:23', '2024-07-28 21:08:23'),
(171, 42, 'Indonesia', 3, '2024-07-28 21:08:23', '2024-07-28 21:08:23'),
(172, 42, 'Malaysia', 2, '2024-07-28 21:08:23', '2024-07-28 21:08:23'),
(173, 42, 'Thailand', 1, '2024-07-28 21:08:23', '2024-07-28 21:08:23'),
(190, 47, 'Amsterdam', 4, '2024-07-31 20:35:38', '2024-07-31 20:35:38'),
(191, 47, 'Eindhoven', 3, '2024-07-31 20:35:38', '2024-07-31 20:35:38'),
(192, 47, 'Utrecht', 2, '2024-07-31 20:35:38', '2024-07-31 20:35:38'),
(193, 47, 'Zwolle', 1, '2024-07-31 20:35:38', '2024-07-31 20:35:38'),
(206, 51, 'Memarahi', 1, '2024-08-11 20:21:52', '2024-08-11 20:21:52'),
(207, 51, 'Melapor kepada orangtua murid', 3, '2024-08-11 20:21:52', '2024-08-11 20:21:52'),
(208, 51, 'Mengabaikannya', 2, '2024-08-11 20:21:52', '2024-08-11 20:21:52'),
(209, 51, 'Memberitahu baik-baik', 4, '2024-08-11 20:21:52', '2024-08-11 20:21:52'),
(210, 52, 'A', 4, '2024-08-13 05:04:32', '2024-08-13 05:04:32'),
(211, 52, 'B', 3, '2024-08-13 05:04:32', '2024-08-13 05:04:32'),
(212, 52, 'C', 2, '2024-08-13 05:04:32', '2024-08-13 05:04:32'),
(213, 52, 'D', 1, '2024-08-13 05:04:32', '2024-08-13 05:04:32'),
(214, 53, 'A', 4, '2024-08-13 05:40:32', '2024-08-13 05:40:32'),
(215, 53, 'B', 3, '2024-08-13 05:40:32', '2024-08-13 05:40:32'),
(216, 53, 'C', 2, '2024-08-13 05:40:32', '2024-08-13 05:40:32'),
(217, 53, 'D', 1, '2024-08-13 05:40:32', '2024-08-13 05:40:32');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
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
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2024_07_24_031601_create_questions_table', 1),
(6, '2024_07_24_031602_create_answers_table', 1),
(7, '2024_07_24_031602_create_quiz_attempts_table', 1),
(8, '2024_07_24_031602_create_user_answers_table', 1),
(9, '2024_07_24_032210_create_admins_table', 1),
(10, '2024_07_25_070207_add_time_limit_to_questions_table', 2),
(11, '2024_07_25_071351_create_question_sets_table', 3),
(12, '2024_07_25_084643_add_question_set_id_to_questions_table', 4),
(13, '2024_07_26_024330_add_email_verification_token_to_users_table', 5),
(14, '2024_07_26_065623_add_question_set_id_to_users_table', 6),
(15, '2024_07_30_004609_add_telepon_instansi_role_to_users_table', 7),
(16, '2024_08_01_035816_create_table_admins', 8),
(18, '2024_08_01_065710_create_admins_table', 9),
(19, '2024_08_05_071358_create_useranswers_table', 10),
(20, '2024_08_05_080529_create_user_answers_table', 11),
(21, '2024_08_06_024019_create_user_answers_table', 12),
(22, '2024_08_06_032942_create_user_answers_table', 13),
(23, '2024_08_07_021426_add_start_exam_and_end_exam_to_question_sets_table', 14),
(24, '2024_08_07_023336_add_role_to_question_sets_table', 15),
(25, '2024_08_07_030151_add_unique_constraint_to_question_sets_name', 16),
(26, '2024_08_12_015358_add_status_to_users_table', 17),
(27, '2024_08_19_102910_remove_started_at_from_quiz_attempts_table', 18),
(28, '2024_08_20_085542_remove_role_from_question_sets', 19),
(29, '2024_08_20_085815_add_role_to_question_sets_table', 20);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `question_set_id` bigint(20) UNSIGNED NOT NULL,
  `question_text` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `question_set_id`, `question_text`, `created_at`, `updated_at`) VALUES
(15, 7, 'Sebutkan jenis-jenis sepatu', '2024-07-28 20:42:37', '2024-07-28 20:42:37'),
(16, 7, 'Sebutkan nama-nama buah', '2024-07-28 20:43:21', '2024-07-28 20:43:21'),
(17, 7, 'Sebutkan jenis-jenis sayur', '2024-07-28 20:44:15', '2024-07-28 20:44:15'),
(18, 7, 'Sebutkan nama-nama pemain bola', '2024-07-28 20:45:16', '2024-07-28 20:45:16'),
(19, 7, 'Sebutkan nama-nama pemain moto gp', '2024-07-28 20:46:10', '2024-07-28 20:46:10'),
(20, 7, 'Sebutkan nama-nama klub sepakbola', '2024-07-28 20:47:08', '2024-07-28 20:47:08'),
(21, 7, 'Sebutkan nama-nama hewan', '2024-07-28 20:50:59', '2024-07-28 20:50:59'),
(22, 7, 'Sebutkan nama-nama serangga', '2024-07-28 20:51:26', '2024-07-28 20:51:26'),
(23, 7, 'Sebutkan nama-nama ikan', '2024-07-28 20:51:51', '2024-07-28 20:51:51'),
(24, 7, 'Sebutkan nama-nama genre musik', '2024-07-28 20:52:33', '2024-07-28 20:52:33'),
(25, 7, 'Siapakah nama pelatih Manchester United saat ini', '2024-07-28 20:53:29', '2024-07-28 20:53:29'),
(26, 7, 'Siapakah pemilik trophy UCL terbanyak', '2024-07-28 20:54:40', '2024-07-28 20:54:40'),
(27, 7, 'Tahun berapakah Manchester United pertama kali mendatangkan Cristiano Ronaldo?', '2024-07-28 20:55:25', '2024-07-28 20:55:25'),
(28, 7, 'Tahun berapakah Cristiano Ronaldo pindah ke Real Madrid?', '2024-07-28 20:56:04', '2024-07-28 20:56:04'),
(29, 7, 'Berapa trofi UCL yang dimiliki Manchester United', '2024-07-28 20:57:09', '2024-07-28 20:57:09'),
(30, 7, 'Siapakah penemu skema serangan total football', '2024-07-28 20:58:29', '2024-07-28 20:58:29'),
(31, 7, 'Siapakah penemu bohlam?', '2024-07-28 20:59:24', '2024-07-28 20:59:24'),
(32, 7, 'Siapakah Kapten Manchester United saat ini?', '2024-07-28 21:00:55', '2024-07-28 21:00:55'),
(33, 7, 'ETH berasal dari mana?', '2024-07-28 21:01:44', '2024-07-28 21:01:44'),
(34, 7, 'Sebutkan nama-nama sosial media', '2024-07-28 21:02:36', '2024-07-28 21:02:36'),
(35, 7, 'Sebutkan nama-nama motor honda', '2024-07-28 21:03:07', '2024-07-28 21:03:07'),
(36, 7, 'Sebutkan nama-nama motor Yamaha', '2024-07-28 21:03:40', '2024-07-28 21:03:40'),
(37, 7, 'Kota-kota berikut merupakan kota yang ada di provinsi Jawa Barat, kecuali', '2024-07-28 21:04:40', '2024-07-28 21:04:40'),
(38, 7, 'Bandung merupakan ibukota provinsi apa?', '2024-07-28 21:05:23', '2024-07-28 21:05:23'),
(39, 7, 'Semarang merupakan ibukota provinsi apa?', '2024-07-28 21:06:00', '2024-07-28 21:06:00'),
(40, 7, 'Kota Pahlawan adalah sebutan untuk kota apa?', '2024-07-28 21:06:43', '2024-07-28 21:06:43'),
(41, 7, 'Pulau Madura termasuk ke dalam provinsi apa?', '2024-07-28 21:07:37', '2024-07-28 21:07:37'),
(42, 7, 'Sebutkan negara-negara yang tergabung dalam ASEAN, kecuali', '2024-07-28 21:08:23', '2024-07-28 21:08:23'),
(47, 7, 'Apa ibukota Belanda?', '2024-07-31 20:35:38', '2024-07-31 20:35:38'),
(51, 21, 'Apa yang harus anda lakukan jika anak tantrum di kelas?', '2024-08-11 20:21:52', '2024-08-11 20:21:52'),
(52, 7, 'Apa itu', '2024-08-13 05:04:32', '2024-08-13 05:04:32'),
(53, 9, 'Apa itu', '2024-08-13 05:40:32', '2024-08-13 05:40:32');

-- --------------------------------------------------------

--
-- Table structure for table `question_sets`
--

CREATE TABLE `question_sets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `start_exam` datetime DEFAULT NULL,
  `end_exam` datetime DEFAULT NULL,
  `role` enum('Guru','Kepala Sekolah') NOT NULL DEFAULT 'Guru',
  `time_limit` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `question_sets`
--

INSERT INTO `question_sets` (`id`, `name`, `start_exam`, `end_exam`, `role`, `time_limit`, `created_at`, `updated_at`) VALUES
(7, 'Paket A', '2024-08-21 09:00:00', '2024-08-21 10:10:00', 'Kepala Sekolah', 60, '2024-07-25 01:09:31', '2024-07-25 01:09:31'),
(9, 'Paket B', '2024-08-20 13:00:05', '2024-08-20 14:00:08', 'Guru', 60, '2024-07-25 01:55:53', '2024-07-25 01:55:53'),
(21, 'Paket Guru 1', '2024-08-20 09:10:00', '2024-08-20 10:10:00', 'Guru', 60, '2024-08-11 20:20:30', '2024-08-11 20:20:30');

-- --------------------------------------------------------

--
-- Table structure for table `quiz_attempts`
--

CREATE TABLE `quiz_attempts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `ended_at` timestamp NULL DEFAULT NULL,
  `score` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quiz_attempts`
--

INSERT INTO `quiz_attempts` (`id`, `user_id`, `ended_at`, `score`, `created_at`, `updated_at`) VALUES
(92, 24, '2024-08-21 03:01:06', 114, '2024-08-21 03:01:06', '2024-08-21 03:01:06');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `question_set_id` bigint(20) UNSIGNED DEFAULT NULL,
  `email_verification_token` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `telepon` varchar(255) DEFAULT NULL,
  `instansi` varchar(255) DEFAULT NULL,
  `role` enum('guru','kepala sekolah') NOT NULL DEFAULT 'guru',
  `status` enum('not_started','on_going','submitted') NOT NULL DEFAULT 'not_started'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `question_set_id`, `email_verification_token`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `telepon`, `instansi`, `role`, `status`) VALUES
(24, 'Andhika Pratama Putra', 'andhika@gmail.com', 7, NULL, '2024-08-20 02:00:39', '$2y$12$Pzi2Z/v/638RN0iuX3up1uq160rTd1pauCkH/1HFvZdswVYJ8UBl6', NULL, '2024-08-20 02:00:27', '2024-08-21 03:01:06', '082294317043', 'PAUD Kartika', 'kepala sekolah', 'submitted'),
(25, 'Entus Azi Bachtiar', 'entus@gmail.com', 21, NULL, '2024-08-20 02:01:59', '$2y$12$L3XKesW5mHI8BXlIFDatoepUZnxZdrJgsRJfbIqdzxvGrC9G93shS', NULL, '2024-08-20 02:01:42', '2024-08-20 02:17:33', '0821299487632', 'PAUD Wijaya', 'guru', 'not_started'),
(26, 'Muhammad Luthfi Ramadhan', 'lutfi@gmail.com', 9, NULL, '2024-08-20 06:03:01', '$2y$12$TjyLZvr5C4s/VxQ7v4o.WOPxMye30bL0kAAHEFywyAqq4P3UW5cMO', NULL, '2024-08-20 06:02:47', '2024-08-20 06:04:17', '082132453223', 'PAUD Terpadu', 'guru', 'not_started'),
(27, 'Alfan Fathoni', 'alfan@gmail.com', 7, NULL, '2024-08-20 06:10:49', '$2y$12$UC5qjPGT/QPdietuMwDiou6zkt3BwOPJ3dn9VGlCHIjYZg/7RScAe', NULL, '2024-08-20 06:10:15', '2024-08-20 06:15:41', '082213454434', 'PAUD Teladan', 'kepala sekolah', 'not_started');

-- --------------------------------------------------------

--
-- Table structure for table `user_answers`
--

CREATE TABLE `user_answers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `question_id` bigint(20) UNSIGNED NOT NULL,
  `answer_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_answers`
--

INSERT INTO `user_answers` (`id`, `user_id`, `question_id`, `answer_id`, `created_at`, `updated_at`) VALUES
(354, 25, 51, 207, '2024-08-20 02:17:29', '2024-08-20 02:17:29'),
(355, 24, 23, 97, '2024-08-20 03:01:59', '2024-08-20 03:01:59'),
(356, 24, 28, 114, '2024-08-20 03:02:04', '2024-08-20 03:02:04'),
(357, 24, 29, 118, '2024-08-20 03:02:07', '2024-08-20 03:02:07'),
(358, 24, 39, 158, '2024-08-20 03:02:10', '2024-08-20 03:02:10'),
(359, 24, 15, 63, '2024-08-20 03:02:12', '2024-08-20 03:02:12'),
(360, 24, 38, 154, '2024-08-20 03:02:15', '2024-08-20 03:02:15'),
(361, 24, 19, 78, '2024-08-20 03:02:19', '2024-08-20 03:02:19'),
(362, 24, 17, 70, '2024-08-20 03:02:26', '2024-08-20 03:02:26'),
(363, 24, 27, 110, '2024-08-20 03:02:31', '2024-08-20 03:02:31'),
(364, 24, 25, 102, '2024-08-20 03:02:35', '2024-08-20 03:02:35'),
(365, 24, 26, 106, '2024-08-20 03:02:39', '2024-08-20 03:02:39'),
(366, 24, 33, 134, '2024-08-20 03:02:45', '2024-08-20 03:02:45'),
(367, 24, 47, 190, '2024-08-20 03:02:48', '2024-08-20 03:02:48'),
(368, 24, 42, 170, '2024-08-20 03:02:51', '2024-08-20 03:02:51'),
(369, 24, 22, 91, '2024-08-20 03:02:55', '2024-08-20 03:02:55'),
(370, 24, 40, 162, '2024-08-20 03:02:58', '2024-08-20 03:02:58'),
(371, 24, 32, 130, '2024-08-20 03:03:03', '2024-08-20 03:03:03'),
(372, 24, 41, 166, '2024-08-20 03:03:16', '2024-08-20 03:03:16'),
(373, 24, 34, 138, '2024-08-20 03:03:21', '2024-08-20 03:03:21'),
(374, 24, 36, 146, '2024-08-20 03:03:25', '2024-08-20 03:03:25'),
(375, 24, 35, 142, '2024-08-20 03:03:28', '2024-08-20 03:03:28'),
(376, 24, 52, 210, '2024-08-20 03:03:30', '2024-08-20 03:03:30'),
(377, 24, 20, 82, '2024-08-20 03:03:33', '2024-08-20 03:03:33'),
(378, 24, 37, 150, '2024-08-20 03:03:39', '2024-08-20 03:03:39'),
(379, 24, 24, 98, '2024-08-20 03:03:41', '2024-08-20 03:03:41'),
(380, 24, 16, 67, '2024-08-20 03:03:46', '2024-08-20 03:03:46'),
(381, 24, 21, 86, '2024-08-20 03:03:49', '2024-08-20 03:03:49'),
(382, 24, 18, 74, '2024-08-20 03:03:54', '2024-08-20 03:03:54'),
(383, 24, 30, 122, '2024-08-20 03:03:58', '2024-08-20 03:03:58'),
(384, 24, 31, 126, '2024-08-20 03:04:01', '2024-08-20 03:04:01'),
(385, 26, 53, 214, '2024-08-20 06:04:14', '2024-08-20 06:04:14'),
(386, 27, 15, 65, '2024-08-20 06:13:23', '2024-08-20 06:13:23'),
(387, 27, 24, 98, '2024-08-20 06:13:31', '2024-08-20 06:13:31'),
(388, 27, 27, 110, '2024-08-20 06:13:34', '2024-08-20 06:13:34'),
(389, 27, 30, 122, '2024-08-20 06:13:41', '2024-08-20 06:13:41'),
(390, 27, 22, 92, '2024-08-20 06:13:47', '2024-08-20 06:13:47'),
(391, 27, 34, 138, '2024-08-20 06:13:50', '2024-08-20 06:13:50'),
(392, 27, 19, 78, '2024-08-20 06:13:54', '2024-08-20 06:13:54'),
(393, 27, 31, 126, '2024-08-20 06:13:56', '2024-08-20 06:13:56'),
(394, 27, 33, 134, '2024-08-20 06:13:59', '2024-08-20 06:13:59'),
(395, 27, 32, 130, '2024-08-20 06:14:03', '2024-08-20 06:14:03'),
(396, 27, 20, 82, '2024-08-20 06:14:06', '2024-08-20 06:14:06'),
(397, 27, 23, 94, '2024-08-20 06:14:12', '2024-08-20 06:14:12'),
(398, 27, 16, 67, '2024-08-20 06:14:15', '2024-08-20 06:14:15'),
(399, 27, 29, 118, '2024-08-20 06:14:20', '2024-08-20 06:14:20'),
(400, 27, 42, 170, '2024-08-20 06:14:28', '2024-08-20 06:14:28'),
(401, 27, 36, 146, '2024-08-20 06:14:31', '2024-08-20 06:14:31'),
(402, 27, 17, 70, '2024-08-20 06:14:34', '2024-08-20 06:14:34'),
(403, 27, 47, 190, '2024-08-20 06:14:36', '2024-08-20 06:14:36'),
(404, 27, 18, 74, '2024-08-20 06:14:39', '2024-08-20 06:14:39'),
(405, 27, 21, 86, '2024-08-20 06:14:43', '2024-08-20 06:14:43'),
(406, 27, 35, 142, '2024-08-20 06:14:49', '2024-08-20 06:14:49'),
(407, 27, 37, 150, '2024-08-20 06:14:58', '2024-08-20 06:14:58'),
(408, 27, 26, 106, '2024-08-20 06:15:05', '2024-08-20 06:15:05'),
(409, 27, 25, 102, '2024-08-20 06:15:11', '2024-08-20 06:15:11'),
(410, 27, 39, 158, '2024-08-20 06:15:14', '2024-08-20 06:15:14'),
(411, 27, 40, 162, '2024-08-20 06:15:16', '2024-08-20 06:15:16'),
(412, 27, 52, 210, '2024-08-20 06:15:18', '2024-08-20 06:15:18'),
(413, 27, 41, 166, '2024-08-20 06:15:21', '2024-08-20 06:15:21'),
(414, 27, 28, 114, '2024-08-20 06:15:35', '2024-08-20 06:15:35'),
(415, 27, 38, 154, '2024-08-20 06:15:37', '2024-08-20 06:15:37');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_username_unique` (`username`);

--
-- Indexes for table `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `answers_question_id_foreign` (`question_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `questions_question_set_id_foreign` (`question_set_id`);

--
-- Indexes for table `question_sets`
--
ALTER TABLE `question_sets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `question_sets_name_unique` (`name`);

--
-- Indexes for table `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quiz_attempts_user_id_foreign` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_question_set_id_foreign` (`question_set_id`);

--
-- Indexes for table `user_answers`
--
ALTER TABLE `user_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_answers_user_id_foreign` (`user_id`),
  ADD KEY `user_answers_question_id_foreign` (`question_id`),
  ADD KEY `user_answers_answer_id_foreign` (`answer_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `answers`
--
ALTER TABLE `answers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=218;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `question_sets`
--
ALTER TABLE `question_sets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `user_answers`
--
ALTER TABLE `user_answers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=417;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `answers`
--
ALTER TABLE `answers`
  ADD CONSTRAINT `answers_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_question_set_id_foreign` FOREIGN KEY (`question_set_id`) REFERENCES `question_sets` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  ADD CONSTRAINT `quiz_attempts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_question_set_id_foreign` FOREIGN KEY (`question_set_id`) REFERENCES `question_sets` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `user_answers`
--
ALTER TABLE `user_answers`
  ADD CONSTRAINT `user_answers_answer_id_foreign` FOREIGN KEY (`answer_id`) REFERENCES `answers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_answers_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `answers` (`question_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_answers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
