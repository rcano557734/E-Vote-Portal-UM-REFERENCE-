-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 16, 2026 at 05:27 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `online-voting`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `action_description` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `user_id`, `action_description`, `created_at`, `updated_at`) VALUES
(1, 1, 'Logged into the system.', '2026-04-13 23:01:16', '2026-04-13 23:01:16'),
(2, 2, 'Logged into the system.', '2026-04-13 23:01:45', '2026-04-13 23:01:45'),
(3, 3, 'Logged into the system.', '2026-04-13 23:02:11', '2026-04-13 23:02:11'),
(4, 3, 'Logged into the system.', '2026-04-13 23:02:47', '2026-04-13 23:02:47'),
(5, 2, 'Logged into the system.', '2026-04-13 23:07:06', '2026-04-13 23:07:06'),
(6, 3, 'Logged into the system.', '2026-04-13 23:18:37', '2026-04-13 23:18:37'),
(7, 2, 'Logged into the system.', '2026-04-13 23:18:50', '2026-04-13 23:18:50'),
(8, 1, 'Logged into the system.', '2026-04-13 23:21:54', '2026-04-13 23:21:54'),
(9, 2, 'Logged into the system.', '2026-04-13 23:22:04', '2026-04-13 23:22:04'),
(10, 3, 'Logged into the system.', '2026-04-13 23:22:46', '2026-04-13 23:22:46'),
(11, 1, 'Logged into the system.', '2026-04-13 23:23:15', '2026-04-13 23:23:15'),
(12, 1, 'Successfully cast an official ballot.', '2026-04-13 23:23:26', '2026-04-13 23:23:26'),
(13, 2, 'Logged into the system.', '2026-04-13 23:23:35', '2026-04-13 23:23:35'),
(14, 3, 'Logged into the system.', '2026-04-13 23:23:49', '2026-04-13 23:23:49'),
(15, 2, 'Logged into the system.', '2026-04-13 23:24:10', '2026-04-13 23:24:10'),
(16, 1, 'Logged into the system.', '2026-04-14 00:15:00', '2026-04-14 00:15:00'),
(17, 3, 'Logged into the system.', '2026-04-14 00:15:12', '2026-04-14 00:15:12'),
(18, 2, 'Logged into the system.', '2026-04-14 00:15:29', '2026-04-14 00:15:29'),
(19, 3, 'Logged into the system.', '2026-04-14 00:28:10', '2026-04-14 00:28:10'),
(20, 2, 'Logged into the system.', '2026-04-14 00:30:27', '2026-04-14 00:30:27'),
(21, 3, 'Logged into the system.', '2026-04-14 00:40:22', '2026-04-14 00:40:22'),
(22, 1, 'Logged into the system.', '2026-04-14 00:40:48', '2026-04-14 00:40:48'),
(23, 3, 'Logged into the system.', '2026-04-14 00:56:25', '2026-04-14 00:56:25'),
(24, 2, 'Logged into the system.', '2026-04-14 00:56:49', '2026-04-14 00:56:49'),
(25, 1, 'Logged into the system.', '2026-04-14 00:57:44', '2026-04-14 00:57:44'),
(26, 2, 'Logged into the system.', '2026-04-14 01:01:43', '2026-04-14 01:01:43'),
(27, 3, 'Logged into the system.', '2026-04-14 01:02:02', '2026-04-14 01:02:02'),
(28, 4, 'Logged into the system.', '2026-04-14 01:02:50', '2026-04-14 01:02:50'),
(29, 1, 'Logged into the system.', '2026-04-14 01:04:08', '2026-04-14 01:04:08'),
(30, 2, 'Logged into the system.', '2026-04-14 01:05:27', '2026-04-14 01:05:27'),
(31, 3, 'Logged into the system.', '2026-04-14 01:16:39', '2026-04-14 01:16:39'),
(32, 3, 'Logged into the system.', '2026-04-14 01:22:36', '2026-04-14 01:22:36'),
(33, 4, 'Logged into the system.', '2026-04-14 20:40:53', '2026-04-14 20:40:53'),
(34, 4, 'Successfully cast an official ballot.', '2026-04-14 20:49:42', '2026-04-14 20:49:42'),
(35, 4, 'Logged into the system.', '2026-04-14 20:50:55', '2026-04-14 20:50:55'),
(36, 2, 'Logged into the system.', '2026-04-14 20:53:55', '2026-04-14 20:53:55'),
(37, 3, 'Logged into the system.', '2026-04-14 21:10:02', '2026-04-14 21:10:02'),
(38, 3, 'Logged into the system.', '2026-04-14 21:10:26', '2026-04-14 21:10:26'),
(39, 2, 'Logged into the system.', '2026-04-14 21:15:45', '2026-04-14 21:15:45'),
(40, 4, 'Logged into the system.', '2026-04-14 23:59:25', '2026-04-14 23:59:25'),
(41, 5, 'Logged into the system.', '2026-04-15 00:09:32', '2026-04-15 00:09:32'),
(42, 2, 'Logged into the system.', '2026-04-15 00:09:50', '2026-04-15 00:09:50'),
(43, 2, 'Logged into the system.', '2026-04-15 00:42:04', '2026-04-15 00:42:04'),
(44, 2, 'Logged into the system.', '2026-04-15 00:42:39', '2026-04-15 00:42:39'),
(45, 4, 'Logged into the system.', '2026-04-15 00:56:52', '2026-04-15 00:56:52'),
(46, 5, 'Logged into the system.', '2026-04-15 01:19:10', '2026-04-15 01:19:10'),
(47, 5, 'Logged into the system.', '2026-04-15 01:22:11', '2026-04-15 01:22:11'),
(48, 5, 'Successfully cast an official ballot.', '2026-04-15 01:22:28', '2026-04-15 01:22:28'),
(49, 5, 'Logged into the system.', '2026-04-15 01:23:00', '2026-04-15 01:23:00'),
(50, 2, 'Logged into the system.', '2026-04-15 01:24:07', '2026-04-15 01:24:07'),
(51, 3, 'Logged into the system.', '2026-04-15 01:27:08', '2026-04-15 01:27:08'),
(52, 5, 'Logged into the system.', '2026-04-15 18:39:00', '2026-04-15 18:39:00'),
(53, 3, 'Logged into the system.', '2026-04-15 18:40:00', '2026-04-15 18:40:00'),
(54, 6, 'Logged into the system.', '2026-04-15 18:41:14', '2026-04-15 18:41:14'),
(55, 3, 'Logged into the system.', '2026-04-15 18:42:07', '2026-04-15 18:42:07'),
(56, 6, 'Logged into the system.', '2026-04-15 18:42:31', '2026-04-15 18:42:31'),
(57, 3, 'Logged into the system.', '2026-04-15 18:42:49', '2026-04-15 18:42:49'),
(58, 3, 'Logged into the system.', '2026-04-15 18:43:33', '2026-04-15 18:43:33'),
(59, 2, 'Logged into the system.', '2026-04-15 18:43:50', '2026-04-15 18:43:50'),
(60, 2, 'Logged into the system.', '2026-04-15 18:48:06', '2026-04-15 18:48:06'),
(61, 3, 'Logged into the system.', '2026-04-15 19:07:10', '2026-04-15 19:07:10'),
(62, 6, 'Logged into the system.', '2026-04-15 19:08:11', '2026-04-15 19:08:11'),
(63, 3, 'Logged into the system.', '2026-04-15 19:08:26', '2026-04-15 19:08:26'),
(64, 3, 'Changed election status to: ACTIVE', '2026-04-15 19:10:03', '2026-04-15 19:10:03'),
(65, 6, 'Logged into the system.', '2026-04-15 19:10:26', '2026-04-15 19:10:26'),
(66, 6, 'Successfully cast an encrypted official ballot.', '2026-04-15 19:10:56', '2026-04-15 19:10:56');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `candidates`
--

CREATE TABLE `candidates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `position_id` bigint(20) UNSIGNED NOT NULL,
  `candidate_name` varchar(255) NOT NULL,
  `platform_description` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `candidates`
--

INSERT INTO `candidates` (`id`, `position_id`, `candidate_name`, `platform_description`, `created_at`, `updated_at`) VALUES
(2, 1, 'Sara Duterte', 'rarara', '2026-04-13 23:03:06', '2026-04-13 23:03:06'),
(3, 1, 'pacquito', 'pacyaw', '2026-04-13 23:03:18', '2026-04-13 23:03:18'),
(4, 2, 'beni leni', 'bayula', '2026-04-13 23:03:42', '2026-04-13 23:03:42'),
(6, 2, 'kuko panilangin', 'kukikoi', '2026-04-13 23:04:58', '2026-04-13 23:04:58'),
(8, 2, 'tita sotta', 'ohaha', '2026-04-13 23:05:51', '2026-04-13 23:05:51'),
(9, 3, 'aquino boom', 'boom tarattarat', '2026-04-13 23:06:04', '2026-04-13 23:06:04'),
(10, 3, 'bakal dela roso', 'butebakalplastic', '2026-04-13 23:06:17', '2026-04-13 23:06:17'),
(11, 3, 'bong ga', 'binugbung', '2026-04-13 23:06:37', '2026-04-13 23:06:37'),
(12, 3, 'bong ga', 'anditonasibbm', '2026-04-13 23:06:57', '2026-04-13 23:06:57');

-- --------------------------------------------------------

--
-- Table structure for table `elections`
--

CREATE TABLE `elections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `elections`
--

INSERT INTO `elections` (`id`, `title`, `start_date`, `end_date`, `status`, `created_at`, `updated_at`) VALUES
(1, '2026 General Elections', '2026-04-14', '2026-04-21', 'active', '2026-04-13 22:47:32', '2026-04-15 19:10:03');

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
-- Table structure for table `jobs`
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
-- Table structure for table `job_batches`
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
(1, '0000_01_01_000000_create_roles_table', 1),
(2, '0001_01_01_000000_create_users_table', 1),
(3, '0001_01_01_000001_create_cache_table', 1),
(4, '0001_01_01_000002_create_jobs_table', 1),
(5, '2026_04_13_100001_create_elections_table', 1),
(6, '2026_04_13_100002_create_positions_table', 1),
(7, '2026_04_13_100003_create_candidates_table', 1),
(8, '2026_04_13_100004_create_votes_table', 1),
(9, '2026_04_13_100005_create_audit_logs_table', 1);

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
-- Table structure for table `positions`
--

CREATE TABLE `positions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `election_id` bigint(20) UNSIGNED NOT NULL,
  `position_name` varchar(255) NOT NULL,
  `max_winners` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `positions`
--

INSERT INTO `positions` (`id`, `election_id`, `position_name`, `max_winners`, `created_at`, `updated_at`) VALUES
(1, 1, 'President', 1, NULL, NULL),
(2, 1, 'Vice President', 1, NULL, NULL),
(3, 1, 'Senator', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role_name`, `created_at`, `updated_at`) VALUES
(1, 'Admin', NULL, NULL),
(2, 'Auditor', NULL, NULL),
(3, 'Voter', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
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
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('skNiXFOzZXzdQ8sQ6AoWCG8hMdebG6vdLXTuUbdN', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoib2lqNnBrM095YmNvM1pRa3Q1czFUQXVIMWxnU2dHNURONzkyOG16aCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7Tjt9fQ==', 1776309227);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL DEFAULT 3,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role_id`, `name`, `email`, `email_verified_at`, `password`, `is_verified`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 3, 'user', 'user@gmail.com', NULL, '$2y$12$I6KywCn3YYw7GtRbbPBVZuM9WufnVPrM5IEqcApwqlJY4ACkNmkUG', 0, NULL, '2026-04-13 23:01:16', '2026-04-13 23:01:16'),
(2, 2, 'auditor', 'audi@gmail.com', NULL, '$2y$12$UB6SNb.GqSBT1TXPiux3iOcTTM6VDLid0TDkNaAQoiSRhwUsGbYw.', 0, NULL, '2026-04-13 23:01:45', '2026-04-13 23:01:45'),
(3, 1, 'admin', 'admin@gmail.com', NULL, '$2y$12$Qn3pQ4x0eTDUpPT2lNp1UexcAaSktiy9Ml8pz.jmcFoCzvDz.FKD.', 0, NULL, '2026-04-13 23:02:11', '2026-04-13 23:02:11'),
(4, 3, 'user1', 'user1@gmail.com', NULL, '$2y$12$p0p9/DnLARmZ6shSSjc02eAWMqGdFZpi1glDEI8626SN7pU75dVgC', 0, NULL, '2026-04-14 01:02:50', '2026-04-14 01:02:50'),
(5, 3, 'voter', 'voter@gmail.com', NULL, '$2y$12$FdnWo5fR9pH4UjornXy0jugZ4rcyTBu421xN973GV6kumPjt2RnI6', 0, NULL, '2026-04-15 00:09:32', '2026-04-15 00:09:32'),
(6, 3, 'ralfh', 'ralfh@gmail.com', NULL, '$2y$12$Dl3./5UDLHUebrjfEyS8vuEl6leBa8p38Z2M26xBROBHuBueL2EUq', 0, NULL, '2026-04-15 18:41:13', '2026-04-15 18:41:13');

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

CREATE TABLE `votes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `candidate_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `votes`
--

INSERT INTO `votes` (`id`, `user_id`, `candidate_id`, `created_at`, `updated_at`) VALUES
(2, 1, 4, '2026-04-13 23:23:26', '2026-04-13 23:23:26'),
(3, 1, 9, '2026-04-13 23:23:26', '2026-04-13 23:23:26'),
(4, 4, 2, '2026-04-14 20:49:42', '2026-04-14 20:49:42'),
(5, 4, 4, '2026-04-14 20:49:42', '2026-04-14 20:49:42'),
(6, 4, 10, '2026-04-14 20:49:42', '2026-04-14 20:49:42'),
(8, 5, 4, '2026-04-15 01:22:28', '2026-04-15 01:22:28'),
(9, 5, 10, '2026-04-15 01:22:28', '2026-04-15 01:22:28'),
(10, 6, 2, '2026-04-15 19:10:56', '2026-04-15 19:10:56'),
(11, 6, 8, '2026-04-15 19:10:56', '2026-04-15 19:10:56'),
(12, 6, 9, '2026-04-15 19:10:56', '2026-04-15 19:10:56');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `audit_logs_user_id_foreign` (`user_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `candidates`
--
ALTER TABLE `candidates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `candidates_position_id_foreign` (`position_id`);

--
-- Indexes for table `elections`
--
ALTER TABLE `elections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `positions`
--
ALTER TABLE `positions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `positions_election_id_foreign` (`election_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- Indexes for table `votes`
--
ALTER TABLE `votes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `votes_user_id_foreign` (`user_id`),
  ADD KEY `votes_candidate_id_foreign` (`candidate_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `candidates`
--
ALTER TABLE `candidates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `elections`
--
ALTER TABLE `elections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `positions`
--
ALTER TABLE `positions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `votes`
--
ALTER TABLE `votes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `candidates`
--
ALTER TABLE `candidates`
  ADD CONSTRAINT `candidates_position_id_foreign` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `positions`
--
ALTER TABLE `positions`
  ADD CONSTRAINT `positions_election_id_foreign` FOREIGN KEY (`election_id`) REFERENCES `elections` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);

--
-- Constraints for table `votes`
--
ALTER TABLE `votes`
  ADD CONSTRAINT `votes_candidate_id_foreign` FOREIGN KEY (`candidate_id`) REFERENCES `candidates` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `votes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
