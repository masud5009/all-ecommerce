-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 30, 2026 at 08:57 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `grocery`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` bigint DEFAULT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role_id` bigint DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `details` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `first_name`, `last_name`, `role`, `username`, `password`, `image`, `role_id`, `address`, `email`, `details`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Masud Rana', NULL, NULL, NULL, 'admin', '$2y$12$Riz2m0zRW1zMJsMPB6KR2OJ9Bxw1vEzdLSMKZ7OFg49oLJiz3hztm', '6661d06b3c60f.jpg', NULL, 'Sonatal, Santhia, Pabna', 'admin@gmail.com', NULL, 1, NULL, '2024-06-06 09:06:19'),
(2, NULL, NULL, NULL, NULL, 'xamone', '$2y$12$Dfvw3zwAydd0GxagDVejeOzC4p1frwztgHxyt2E0cNkrC1VNg7BOa', NULL, NULL, NULL, 'rypugetok@mailinator.com', NULL, 1, '2024-04-28 09:20:18', '2024-04-28 09:20:18'),
(8, NULL, 'role', NULL, 6, 'role', '$2y$12$dGDkx5iOmWytyRZ3qSvMOe1nXdG3mGQkqmZEfmRL.uztc7sGhjgoq', '670a7cac46375.jpg', NULL, NULL, 'role@gmail.com', NULL, 1, '2024-10-12 07:42:04', '2024-10-12 07:42:04');

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE `blogs` (
  `id` bigint UNSIGNED NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `serial_number` int NOT NULL,
  `status` tinyint NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `blog_contents`
--

CREATE TABLE `blog_contents` (
  `id` bigint UNSIGNED NOT NULL,
  `category_id` bigint NOT NULL,
  `language_id` bigint NOT NULL,
  `blog_id` bigint NOT NULL,
  `author` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_keyword` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `meta_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `text` blob NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blog_contents`
--

INSERT INTO `blog_contents` (`id`, `category_id`, `language_id`, `blog_id`, `author`, `title`, `slug`, `meta_keyword`, `meta_description`, `text`, `created_at`, `updated_at`) VALUES
(7, 26, 1, 11, 'sadfasdfasdf', 'sdfasdfasdf', 'sdfasdfasdf', '\"null\"', NULL, 0x3c703e7361646661736466617364663c2f703e, '2024-12-29 12:39:52', '2024-12-29 12:40:01'),
(8, 30, 1, 12, 'masud', 'this is blog title', 'this-is-blog-title', '\"&quot;null&quot;\"', NULL, 0x3c703e6d617375643c2f703e, '2025-01-04 10:06:09', '2025-01-04 10:07:42'),
(9, 31, 2, 12, 'asdfasdf', 'this is blog title', 'this-is-blog-title', 'null', NULL, 0x3c703e6173646661736466617364663c2f703e, '2025-01-04 10:07:42', '2025-01-04 10:07:42');

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` bigint UNSIGNED NOT NULL,
  `session_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `variant_id` bigint UNSIGNED DEFAULT NULL,
  `variant_label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`id`, `session_id`, `user_id`, `product_id`, `variant_id`, `variant_label`, `quantity`, `price`, `created_at`, `updated_at`) VALUES
(8, 'cart_69b6c0bed3e710.02060244', NULL, 54, 129, '500gm', 3, '900.00', '2026-03-15 09:44:43', '2026-03-15 09:44:50'),
(9, 'cart_69b6d83e8fc6d0.26091137', NULL, 54, 129, '500gm', 1, '900.00', '2026-03-15 10:03:15', '2026-03-15 10:03:15'),
(10, 'cart_69b6d85a406256.83261885', NULL, 54, 129, '500gm', 1, '900.00', '2026-03-15 10:03:44', '2026-03-15 10:03:44'),
(11, 'cart_69b6d8b62d5b02.19588697', NULL, 54, 129, '500gm', 1, '900.00', '2026-03-15 10:06:55', '2026-03-15 10:06:55'),
(12, 'cart_69b6d97f2744c8.69571657', NULL, 53, 120, '23.5 cm, Black', 1, '199.99', '2026-03-15 10:13:11', '2026-03-15 10:13:11'),
(13, 'cart_69b6daaa7dbce0.56648714', NULL, 54, 129, '500gm', 1, '900.00', '2026-03-15 10:14:42', '2026-03-15 10:14:42'),
(14, 'cart_69b6dbcc68cd60.15915978', NULL, 53, 120, '23.5 cm, Black', 1, '199.99', '2026-03-15 10:18:29', '2026-03-15 10:18:29'),
(16, 'cart_69c7f61ce67821.53970039', NULL, 54, 129, '500gm', 1, '630.00', '2026-03-28 11:31:08', '2026-03-28 11:31:08'),
(17, 'cart_69c7f61ce67821.53970039', NULL, 54, 130, '1Kg', 2, '1120.00', '2026-03-28 11:41:10', '2026-03-28 11:41:12');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL,
  `serial_number` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `language_id`, `name`, `slug`, `status`, `serial_number`, `created_at`, `updated_at`) VALUES
(30, 1, 'Sports', 'sports', 0, 1, '2025-01-04 10:05:36', '2025-02-14 00:09:18'),
(31, 2, 'Sports-b', 'sports-b', 1, 2, '2025-01-04 10:07:19', '2025-01-04 10:07:19');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `failed_jobs`
--

INSERT INTO `failed_jobs` (`id`, `uuid`, `connection`, `queue`, `payload`, `exception`, `failed_at`) VALUES
(1, '07d26260-cf97-47ea-b333-5bc5625160bd', 'database', 'default', '{\"uuid\":\"07d26260-cf97-47ea-b333-5bc5625160bd\",\"displayName\":\"App\\\\Jobs\\\\SendVerificationEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendVerificationEmail\",\"command\":\"O:30:\\\"App\\\\Jobs\\\\SendVerificationEmail\\\":2:{s:4:\\\"user\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:32;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:16:\\\"verificationLink\\\";s:81:\\\"https:\\/\\/sass-dashboard.test\\/register\\/mode\\/verify\\/a7130a23afbadee5cfee76e7257c40cf\\\";}\"}}', 'Error: Object of class Illuminate\\Mail\\Message could not be converted to string in D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Log\\Logger.php:268\nStack trace:\n#0 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Log\\Logger.php(185): Illuminate\\Log\\Logger->formatMessage(Object(Illuminate\\Mail\\Message))\n#1 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Log\\Logger.php(133): Illuminate\\Log\\Logger->writeLog(\'info\', Object(Illuminate\\Mail\\Message), Array)\n#2 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Log\\LogManager.php(722): Illuminate\\Log\\Logger->info(Object(Illuminate\\Mail\\Message), Array)\n#3 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Facades\\Facade.php(355): Illuminate\\Log\\LogManager->info(Object(Illuminate\\Mail\\Message))\n#4 D:\\laragon\\www\\sass-dashboard\\app\\Http\\Helpers\\MailConfig.php(103): Illuminate\\Support\\Facades\\Facade::__callStatic(\'info\', Array)\n#5 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(317): App\\Http\\Helpers\\MailConfig::App\\Http\\Helpers\\{closure}(Object(Illuminate\\Mail\\Message))\n#6 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\MailManager.php(592): Illuminate\\Mail\\Mailer->send(NULL, Array, Object(Closure))\n#7 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Facades\\Facade.php(355): Illuminate\\Mail\\MailManager->__call(\'send\', Array)\n#8 D:\\laragon\\www\\sass-dashboard\\app\\Http\\Helpers\\MailConfig.php(111): Illuminate\\Support\\Facades\\Facade::__callStatic(\'send\', Array)\n#9 D:\\laragon\\www\\sass-dashboard\\app\\Jobs\\SendVerificationEmail.php(49): App\\Http\\Helpers\\MailConfig::send(Array)\n#10 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): App\\Jobs\\SendVerificationEmail->handle()\n#11 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(41): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#12 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#13 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#14 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(662): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#15 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(128): Illuminate\\Container\\Container->call(Array)\n#16 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(144): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\SendVerificationEmail))\n#17 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(119): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\SendVerificationEmail))\n#18 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(132): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#19 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(124): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\SendVerificationEmail), false)\n#20 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(144): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(App\\Jobs\\SendVerificationEmail))\n#21 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(119): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\SendVerificationEmail))\n#22 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(126): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#23 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(70): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(App\\Jobs\\SendVerificationEmail))\n#24 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#25 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(439): Illuminate\\Queue\\Jobs\\Job->fire()\n#26 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(389): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#27 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(176): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#28 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(138): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#29 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(121): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#30 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#31 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(41): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#32 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#33 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#34 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(662): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#35 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(211): Illuminate\\Container\\Container->call(Array)\n#36 D:\\laragon\\www\\sass-dashboard\\vendor\\symfony\\console\\Command\\Command.php(326): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#37 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(181): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#38 D:\\laragon\\www\\sass-dashboard\\vendor\\symfony\\console\\Application.php(1096): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#39 D:\\laragon\\www\\sass-dashboard\\vendor\\symfony\\console\\Application.php(324): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#40 D:\\laragon\\www\\sass-dashboard\\vendor\\symfony\\console\\Application.php(175): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#41 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(201): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#42 D:\\laragon\\www\\sass-dashboard\\artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#43 {main}', '2025-11-18 13:09:58'),
(2, '5e86f397-0286-4e6b-a217-0beac2d26dfc', 'database', 'default', '{\"uuid\":\"5e86f397-0286-4e6b-a217-0beac2d26dfc\",\"displayName\":\"App\\\\Jobs\\\\SendVerificationEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendVerificationEmail\",\"command\":\"O:30:\\\"App\\\\Jobs\\\\SendVerificationEmail\\\":2:{s:4:\\\"user\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:33;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:16:\\\"verificationLink\\\";s:81:\\\"https:\\/\\/sass-dashboard.test\\/register\\/mode\\/verify\\/8e054b8d3abd4681341f1325726063ae\\\";}\"}}', 'Error: Object of class Illuminate\\Mail\\Message could not be converted to string in D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Log\\Logger.php:268\nStack trace:\n#0 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Log\\Logger.php(185): Illuminate\\Log\\Logger->formatMessage(Object(Illuminate\\Mail\\Message))\n#1 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Log\\Logger.php(133): Illuminate\\Log\\Logger->writeLog(\'info\', Object(Illuminate\\Mail\\Message), Array)\n#2 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Log\\LogManager.php(722): Illuminate\\Log\\Logger->info(Object(Illuminate\\Mail\\Message), Array)\n#3 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Facades\\Facade.php(355): Illuminate\\Log\\LogManager->info(Object(Illuminate\\Mail\\Message))\n#4 D:\\laragon\\www\\sass-dashboard\\app\\Http\\Helpers\\MailConfig.php(103): Illuminate\\Support\\Facades\\Facade::__callStatic(\'info\', Array)\n#5 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(317): App\\Http\\Helpers\\MailConfig::App\\Http\\Helpers\\{closure}(Object(Illuminate\\Mail\\Message))\n#6 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\MailManager.php(592): Illuminate\\Mail\\Mailer->send(NULL, Array, Object(Closure))\n#7 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Facades\\Facade.php(355): Illuminate\\Mail\\MailManager->__call(\'send\', Array)\n#8 D:\\laragon\\www\\sass-dashboard\\app\\Http\\Helpers\\MailConfig.php(111): Illuminate\\Support\\Facades\\Facade::__callStatic(\'send\', Array)\n#9 D:\\laragon\\www\\sass-dashboard\\app\\Jobs\\SendVerificationEmail.php(49): App\\Http\\Helpers\\MailConfig::send(Array)\n#10 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): App\\Jobs\\SendVerificationEmail->handle()\n#11 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(41): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#12 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#13 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#14 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(662): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#15 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(128): Illuminate\\Container\\Container->call(Array)\n#16 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(144): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\SendVerificationEmail))\n#17 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(119): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\SendVerificationEmail))\n#18 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(132): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#19 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(124): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\SendVerificationEmail), false)\n#20 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(144): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(App\\Jobs\\SendVerificationEmail))\n#21 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(119): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\SendVerificationEmail))\n#22 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(126): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#23 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(70): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(App\\Jobs\\SendVerificationEmail))\n#24 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#25 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(439): Illuminate\\Queue\\Jobs\\Job->fire()\n#26 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(389): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#27 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(176): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#28 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(138): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#29 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(121): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#30 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#31 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(41): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#32 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#33 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#34 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(662): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#35 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(211): Illuminate\\Container\\Container->call(Array)\n#36 D:\\laragon\\www\\sass-dashboard\\vendor\\symfony\\console\\Command\\Command.php(326): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#37 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(181): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#38 D:\\laragon\\www\\sass-dashboard\\vendor\\symfony\\console\\Application.php(1096): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#39 D:\\laragon\\www\\sass-dashboard\\vendor\\symfony\\console\\Application.php(324): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#40 D:\\laragon\\www\\sass-dashboard\\vendor\\symfony\\console\\Application.php(175): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#41 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(201): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#42 D:\\laragon\\www\\sass-dashboard\\artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#43 {main}', '2025-11-18 13:10:25'),
(3, '308b64cb-a247-4ae0-af71-00cdd37b17a9', 'database', 'default', '{\"uuid\":\"308b64cb-a247-4ae0-af71-00cdd37b17a9\",\"displayName\":\"App\\\\Jobs\\\\SendVerificationEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendVerificationEmail\",\"command\":\"O:30:\\\"App\\\\Jobs\\\\SendVerificationEmail\\\":2:{s:4:\\\"user\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:34;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:16:\\\"verificationLink\\\";s:81:\\\"https:\\/\\/sass-dashboard.test\\/register\\/mode\\/verify\\/31abe0cf8ee38c229ed1ca2442756416\\\";}\"}}', 'Error: Object of class Illuminate\\Mail\\Message could not be converted to string in D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Log\\Logger.php:268\nStack trace:\n#0 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Log\\Logger.php(185): Illuminate\\Log\\Logger->formatMessage(Object(Illuminate\\Mail\\Message))\n#1 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Log\\Logger.php(133): Illuminate\\Log\\Logger->writeLog(\'info\', Object(Illuminate\\Mail\\Message), Array)\n#2 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Log\\LogManager.php(722): Illuminate\\Log\\Logger->info(Object(Illuminate\\Mail\\Message), Array)\n#3 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Facades\\Facade.php(355): Illuminate\\Log\\LogManager->info(Object(Illuminate\\Mail\\Message))\n#4 D:\\laragon\\www\\sass-dashboard\\app\\Http\\Helpers\\MailConfig.php(103): Illuminate\\Support\\Facades\\Facade::__callStatic(\'info\', Array)\n#5 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(317): App\\Http\\Helpers\\MailConfig::App\\Http\\Helpers\\{closure}(Object(Illuminate\\Mail\\Message))\n#6 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\MailManager.php(592): Illuminate\\Mail\\Mailer->send(NULL, Array, Object(Closure))\n#7 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Facades\\Facade.php(355): Illuminate\\Mail\\MailManager->__call(\'send\', Array)\n#8 D:\\laragon\\www\\sass-dashboard\\app\\Http\\Helpers\\MailConfig.php(111): Illuminate\\Support\\Facades\\Facade::__callStatic(\'send\', Array)\n#9 D:\\laragon\\www\\sass-dashboard\\app\\Jobs\\SendVerificationEmail.php(49): App\\Http\\Helpers\\MailConfig::send(Array)\n#10 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): App\\Jobs\\SendVerificationEmail->handle()\n#11 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(41): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#12 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#13 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#14 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(662): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#15 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(128): Illuminate\\Container\\Container->call(Array)\n#16 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(144): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\SendVerificationEmail))\n#17 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(119): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\SendVerificationEmail))\n#18 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(132): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#19 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(124): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\SendVerificationEmail), false)\n#20 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(144): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(App\\Jobs\\SendVerificationEmail))\n#21 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(119): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\SendVerificationEmail))\n#22 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(126): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#23 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(70): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(App\\Jobs\\SendVerificationEmail))\n#24 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#25 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(439): Illuminate\\Queue\\Jobs\\Job->fire()\n#26 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(389): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#27 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(176): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#28 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(138): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#29 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(121): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#30 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#31 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(41): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#32 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#33 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#34 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(662): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#35 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(211): Illuminate\\Container\\Container->call(Array)\n#36 D:\\laragon\\www\\sass-dashboard\\vendor\\symfony\\console\\Command\\Command.php(326): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#37 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(181): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#38 D:\\laragon\\www\\sass-dashboard\\vendor\\symfony\\console\\Application.php(1096): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#39 D:\\laragon\\www\\sass-dashboard\\vendor\\symfony\\console\\Application.php(324): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#40 D:\\laragon\\www\\sass-dashboard\\vendor\\symfony\\console\\Application.php(175): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#41 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(201): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#42 D:\\laragon\\www\\sass-dashboard\\artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#43 {main}', '2025-11-18 13:12:05'),
(4, '341c10c3-0724-4423-a847-b7b587a9ce23', 'database', 'default', '{\"uuid\":\"341c10c3-0724-4423-a847-b7b587a9ce23\",\"displayName\":\"App\\\\Jobs\\\\SendVerificationEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendVerificationEmail\",\"command\":\"O:30:\\\"App\\\\Jobs\\\\SendVerificationEmail\\\":2:{s:4:\\\"user\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:35;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:16:\\\"verificationLink\\\";s:81:\\\"https:\\/\\/sass-dashboard.test\\/register\\/mode\\/verify\\/d04bbb0c223f06d0dcc7ad19e46d2fb9\\\";}\"}}', 'Error: Object of class Illuminate\\Mail\\Message could not be converted to string in D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Log\\Logger.php:268\nStack trace:\n#0 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Log\\Logger.php(185): Illuminate\\Log\\Logger->formatMessage(Object(Illuminate\\Mail\\Message))\n#1 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Log\\Logger.php(133): Illuminate\\Log\\Logger->writeLog(\'info\', Object(Illuminate\\Mail\\Message), Array)\n#2 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Log\\LogManager.php(722): Illuminate\\Log\\Logger->info(Object(Illuminate\\Mail\\Message), Array)\n#3 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Facades\\Facade.php(355): Illuminate\\Log\\LogManager->info(Object(Illuminate\\Mail\\Message))\n#4 D:\\laragon\\www\\sass-dashboard\\app\\Http\\Helpers\\MailConfig.php(103): Illuminate\\Support\\Facades\\Facade::__callStatic(\'info\', Array)\n#5 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(317): App\\Http\\Helpers\\MailConfig::App\\Http\\Helpers\\{closure}(Object(Illuminate\\Mail\\Message))\n#6 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\MailManager.php(592): Illuminate\\Mail\\Mailer->send(NULL, Array, Object(Closure))\n#7 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Facades\\Facade.php(355): Illuminate\\Mail\\MailManager->__call(\'send\', Array)\n#8 D:\\laragon\\www\\sass-dashboard\\app\\Http\\Helpers\\MailConfig.php(111): Illuminate\\Support\\Facades\\Facade::__callStatic(\'send\', Array)\n#9 D:\\laragon\\www\\sass-dashboard\\app\\Jobs\\SendVerificationEmail.php(49): App\\Http\\Helpers\\MailConfig::send(Array)\n#10 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): App\\Jobs\\SendVerificationEmail->handle()\n#11 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(41): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#12 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#13 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#14 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(662): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#15 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(128): Illuminate\\Container\\Container->call(Array)\n#16 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(144): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\SendVerificationEmail))\n#17 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(119): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\SendVerificationEmail))\n#18 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(132): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#19 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(124): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\SendVerificationEmail), false)\n#20 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(144): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(App\\Jobs\\SendVerificationEmail))\n#21 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(119): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\SendVerificationEmail))\n#22 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(126): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#23 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(70): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(App\\Jobs\\SendVerificationEmail))\n#24 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#25 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(439): Illuminate\\Queue\\Jobs\\Job->fire()\n#26 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(389): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#27 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(176): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#28 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(138): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#29 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(121): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#30 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#31 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(41): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#32 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#33 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#34 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(662): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#35 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(211): Illuminate\\Container\\Container->call(Array)\n#36 D:\\laragon\\www\\sass-dashboard\\vendor\\symfony\\console\\Command\\Command.php(326): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#37 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(181): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#38 D:\\laragon\\www\\sass-dashboard\\vendor\\symfony\\console\\Application.php(1096): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#39 D:\\laragon\\www\\sass-dashboard\\vendor\\symfony\\console\\Application.php(324): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#40 D:\\laragon\\www\\sass-dashboard\\vendor\\symfony\\console\\Application.php(175): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#41 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(201): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#42 D:\\laragon\\www\\sass-dashboard\\artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#43 {main}', '2025-11-18 13:13:23');
INSERT INTO `failed_jobs` (`id`, `uuid`, `connection`, `queue`, `payload`, `exception`, `failed_at`) VALUES
(5, '8d77200c-3464-47d7-8e9d-01783335f18d', 'database', 'default', '{\"uuid\":\"8d77200c-3464-47d7-8e9d-01783335f18d\",\"displayName\":\"App\\\\Jobs\\\\SendVerificationEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"60\",\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendVerificationEmail\",\"command\":\"O:30:\\\"App\\\\Jobs\\\\SendVerificationEmail\\\":2:{s:4:\\\"user\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:39;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:16:\\\"verificationLink\\\";s:81:\\\"https:\\/\\/sass-dashboard.test\\/register\\/mode\\/verify\\/e674c74bc84fdefc348433da56f17d19\\\";}\"}}', 'Symfony\\Component\\Mailer\\Exception\\TransportException: Failed to authenticate on SMTP server with username \"airdrop446646@gmail.com\" using the following authenticators: \"LOGIN\", \"PLAIN\", \"XOAUTH2\". Authenticator \"LOGIN\" returned \"Expected response code \"235\" but got code \"535\", with message \"535-5.7.8 Username and Password not accepted. For more information, go to\r\n535 5.7.8  https://support.google.com/mail/?p=BadCredentials 41be03b00d2f7-bc37526d91fsm18248934a12.22 - gsmtp\".\". Authenticator \"PLAIN\" returned \"Expected response code \"235\" but got code \"535\", with message \"535-5.7.8 Username and Password not accepted. For more information, go to\r\n535 5.7.8  https://support.google.com/mail/?p=BadCredentials 41be03b00d2f7-bc37526d91fsm18248934a12.22 - gsmtp\".\". Authenticator \"XOAUTH2\" returned \"Expected response code \"235\" but got code \"334\", with message \"334 eyJzdGF0dXMiOiI0MDAiLCJzY2hlbWVzIjoiQmVhcmVyIiwic2NvcGUiOiJodHRwczovL21haWwuZ29vZ2xlLmNvbS8ifQ==\".\". in D:\\laragon\\www\\sass-dashboard\\vendor\\symfony\\mailer\\Transport\\Smtp\\EsmtpTransport.php:226\nStack trace:\n#0 D:\\laragon\\www\\sass-dashboard\\vendor\\symfony\\mailer\\Transport\\Smtp\\EsmtpTransport.php(161): Symfony\\Component\\Mailer\\Transport\\Smtp\\EsmtpTransport->handleAuth(Array)\n#1 D:\\laragon\\www\\sass-dashboard\\vendor\\symfony\\mailer\\Transport\\Smtp\\EsmtpTransport.php(118): Symfony\\Component\\Mailer\\Transport\\Smtp\\EsmtpTransport->doEhloCommand()\n#2 D:\\laragon\\www\\sass-dashboard\\vendor\\symfony\\mailer\\Transport\\Smtp\\SmtpTransport.php(254): Symfony\\Component\\Mailer\\Transport\\Smtp\\EsmtpTransport->executeCommand(\'HELO [127.0.0.1...\', Array)\n#3 D:\\laragon\\www\\sass-dashboard\\vendor\\symfony\\mailer\\Transport\\Smtp\\SmtpTransport.php(277): Symfony\\Component\\Mailer\\Transport\\Smtp\\SmtpTransport->doHeloCommand()\n#4 D:\\laragon\\www\\sass-dashboard\\vendor\\symfony\\mailer\\Transport\\Smtp\\SmtpTransport.php(209): Symfony\\Component\\Mailer\\Transport\\Smtp\\SmtpTransport->start()\n#5 D:\\laragon\\www\\sass-dashboard\\vendor\\symfony\\mailer\\Transport\\AbstractTransport.php(69): Symfony\\Component\\Mailer\\Transport\\Smtp\\SmtpTransport->doSend(Object(Symfony\\Component\\Mailer\\SentMessage))\n#6 D:\\laragon\\www\\sass-dashboard\\vendor\\symfony\\mailer\\Transport\\Smtp\\SmtpTransport.php(137): Symfony\\Component\\Mailer\\Transport\\AbstractTransport->send(Object(Symfony\\Component\\Mime\\Email), Object(Symfony\\Component\\Mailer\\DelayedEnvelope))\n#7 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(573): Symfony\\Component\\Mailer\\Transport\\Smtp\\SmtpTransport->send(Object(Symfony\\Component\\Mime\\Email), Object(Symfony\\Component\\Mailer\\DelayedEnvelope))\n#8 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(335): Illuminate\\Mail\\Mailer->sendSymfonyMessage(Object(Symfony\\Component\\Mime\\Email))\n#9 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(224): Illuminate\\Mail\\Mailer->send(NULL, Array, Object(Closure))\n#10 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\MailManager.php(592): Illuminate\\Mail\\Mailer->raw(\'<p>Dear <strong...\', Object(Closure))\n#11 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Facades\\Facade.php(355): Illuminate\\Mail\\MailManager->__call(\'raw\', Array)\n#12 D:\\laragon\\www\\sass-dashboard\\app\\Jobs\\SendVerificationEmail.php(82): Illuminate\\Support\\Facades\\Facade::__callStatic(\'raw\', Array)\n#13 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): App\\Jobs\\SendVerificationEmail->handle()\n#14 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(41): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#15 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#16 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#17 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(662): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#18 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(128): Illuminate\\Container\\Container->call(Array)\n#19 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(144): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\SendVerificationEmail))\n#20 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(119): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\SendVerificationEmail))\n#21 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(132): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#22 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(124): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\SendVerificationEmail), false)\n#23 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(144): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(App\\Jobs\\SendVerificationEmail))\n#24 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(119): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\SendVerificationEmail))\n#25 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(126): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#26 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(70): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(App\\Jobs\\SendVerificationEmail))\n#27 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#28 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(439): Illuminate\\Queue\\Jobs\\Job->fire()\n#29 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(389): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#30 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(176): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#31 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(138): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#32 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(121): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#33 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#34 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(41): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#35 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#36 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#37 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(662): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#38 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(211): Illuminate\\Container\\Container->call(Array)\n#39 D:\\laragon\\www\\sass-dashboard\\vendor\\symfony\\console\\Command\\Command.php(326): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#40 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(181): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#41 D:\\laragon\\www\\sass-dashboard\\vendor\\symfony\\console\\Application.php(1096): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#42 D:\\laragon\\www\\sass-dashboard\\vendor\\symfony\\console\\Application.php(324): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#43 D:\\laragon\\www\\sass-dashboard\\vendor\\symfony\\console\\Application.php(175): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#44 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(201): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#45 D:\\laragon\\www\\sass-dashboard\\artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#46 {main}', '2025-11-19 09:34:33'),
(6, '6c621510-3b97-4d43-9cf8-7891d9923f9f', 'database', 'default', '{\"uuid\":\"6c621510-3b97-4d43-9cf8-7891d9923f9f\",\"displayName\":\"App\\\\Jobs\\\\SendVerificationEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"60\",\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendVerificationEmail\",\"command\":\"O:30:\\\"App\\\\Jobs\\\\SendVerificationEmail\\\":2:{s:4:\\\"user\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:40;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:16:\\\"verificationLink\\\";s:81:\\\"https:\\/\\/sass-dashboard.test\\/register\\/mode\\/verify\\/2d9e712ed8857c3859c06d5d335d0c7c\\\";}\"}}', 'Symfony\\Component\\Mailer\\Exception\\TransportException: Failed to authenticate on SMTP server with username \"airdrop446646@gmail.com\" using the following authenticators: \"LOGIN\", \"PLAIN\", \"XOAUTH2\". Authenticator \"LOGIN\" returned \"Expected response code \"235\" but got code \"535\", with message \"535-5.7.8 Username and Password not accepted. For more information, go to\r\n535 5.7.8  https://support.google.com/mail/?p=BadCredentials d2e1a72fcca58-7bf4d30e1a3sm10396635b3a.36 - gsmtp\".\". Authenticator \"PLAIN\" returned \"Expected response code \"235\" but got code \"535\", with message \"535-5.7.8 Username and Password not accepted. For more information, go to\r\n535 5.7.8  https://support.google.com/mail/?p=BadCredentials d2e1a72fcca58-7bf4d30e1a3sm10396635b3a.36 - gsmtp\".\". Authenticator \"XOAUTH2\" returned \"Expected response code \"235\" but got code \"334\", with message \"334 eyJzdGF0dXMiOiI0MDAiLCJzY2hlbWVzIjoiQmVhcmVyIiwic2NvcGUiOiJodHRwczovL21haWwuZ29vZ2xlLmNvbS8ifQ==\".\". in D:\\laragon\\www\\sass-dashboard\\vendor\\symfony\\mailer\\Transport\\Smtp\\EsmtpTransport.php:226\nStack trace:\n#0 D:\\laragon\\www\\sass-dashboard\\vendor\\symfony\\mailer\\Transport\\Smtp\\EsmtpTransport.php(161): Symfony\\Component\\Mailer\\Transport\\Smtp\\EsmtpTransport->handleAuth(Array)\n#1 D:\\laragon\\www\\sass-dashboard\\vendor\\symfony\\mailer\\Transport\\Smtp\\EsmtpTransport.php(118): Symfony\\Component\\Mailer\\Transport\\Smtp\\EsmtpTransport->doEhloCommand()\n#2 D:\\laragon\\www\\sass-dashboard\\vendor\\symfony\\mailer\\Transport\\Smtp\\SmtpTransport.php(254): Symfony\\Component\\Mailer\\Transport\\Smtp\\EsmtpTransport->executeCommand(\'HELO [127.0.0.1...\', Array)\n#3 D:\\laragon\\www\\sass-dashboard\\vendor\\symfony\\mailer\\Transport\\Smtp\\SmtpTransport.php(277): Symfony\\Component\\Mailer\\Transport\\Smtp\\SmtpTransport->doHeloCommand()\n#4 D:\\laragon\\www\\sass-dashboard\\vendor\\symfony\\mailer\\Transport\\Smtp\\SmtpTransport.php(209): Symfony\\Component\\Mailer\\Transport\\Smtp\\SmtpTransport->start()\n#5 D:\\laragon\\www\\sass-dashboard\\vendor\\symfony\\mailer\\Transport\\AbstractTransport.php(69): Symfony\\Component\\Mailer\\Transport\\Smtp\\SmtpTransport->doSend(Object(Symfony\\Component\\Mailer\\SentMessage))\n#6 D:\\laragon\\www\\sass-dashboard\\vendor\\symfony\\mailer\\Transport\\Smtp\\SmtpTransport.php(137): Symfony\\Component\\Mailer\\Transport\\AbstractTransport->send(Object(Symfony\\Component\\Mime\\Email), Object(Symfony\\Component\\Mailer\\DelayedEnvelope))\n#7 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(573): Symfony\\Component\\Mailer\\Transport\\Smtp\\SmtpTransport->send(Object(Symfony\\Component\\Mime\\Email), Object(Symfony\\Component\\Mailer\\DelayedEnvelope))\n#8 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(335): Illuminate\\Mail\\Mailer->sendSymfonyMessage(Object(Symfony\\Component\\Mime\\Email))\n#9 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(224): Illuminate\\Mail\\Mailer->send(NULL, Array, Object(Closure))\n#10 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\MailManager.php(592): Illuminate\\Mail\\Mailer->raw(\'<p>Dear <strong...\', Object(Closure))\n#11 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Facades\\Facade.php(355): Illuminate\\Mail\\MailManager->__call(\'raw\', Array)\n#12 D:\\laragon\\www\\sass-dashboard\\app\\Jobs\\SendVerificationEmail.php(82): Illuminate\\Support\\Facades\\Facade::__callStatic(\'raw\', Array)\n#13 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): App\\Jobs\\SendVerificationEmail->handle()\n#14 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(41): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#15 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#16 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#17 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(662): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#18 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(128): Illuminate\\Container\\Container->call(Array)\n#19 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(144): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\SendVerificationEmail))\n#20 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(119): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\SendVerificationEmail))\n#21 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(132): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#22 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(124): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\SendVerificationEmail), false)\n#23 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(144): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(App\\Jobs\\SendVerificationEmail))\n#24 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(119): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\SendVerificationEmail))\n#25 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(126): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#26 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(70): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(App\\Jobs\\SendVerificationEmail))\n#27 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#28 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(439): Illuminate\\Queue\\Jobs\\Job->fire()\n#29 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(389): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#30 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(176): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#31 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(138): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#32 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(121): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#33 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#34 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(41): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#35 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#36 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#37 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(662): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#38 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(211): Illuminate\\Container\\Container->call(Array)\n#39 D:\\laragon\\www\\sass-dashboard\\vendor\\symfony\\console\\Command\\Command.php(326): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#40 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(181): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#41 D:\\laragon\\www\\sass-dashboard\\vendor\\symfony\\console\\Application.php(1096): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#42 D:\\laragon\\www\\sass-dashboard\\vendor\\symfony\\console\\Application.php(324): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#43 D:\\laragon\\www\\sass-dashboard\\vendor\\symfony\\console\\Application.php(175): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#44 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(201): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#45 D:\\laragon\\www\\sass-dashboard\\artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#46 {main}', '2025-11-19 09:34:54'),
(7, '09bee7ab-41a3-45c7-8799-e2e2a80ef14c', 'database', 'default', '{\"uuid\":\"09bee7ab-41a3-45c7-8799-e2e2a80ef14c\",\"displayName\":\"App\\\\Jobs\\\\SendVerificationEmail\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":3,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"60\",\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\SendVerificationEmail\",\"command\":\"O:30:\\\"App\\\\Jobs\\\\SendVerificationEmail\\\":2:{s:4:\\\"user\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:15:\\\"App\\\\Models\\\\User\\\";s:2:\\\"id\\\";i:42;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:16:\\\"verificationLink\\\";s:81:\\\"https:\\/\\/sass-dashboard.test\\/register\\/mode\\/verify\\/e15b4740f62e15c2482ec419bc85d12f\\\";}\"}}', 'Symfony\\Component\\Mailer\\Exception\\TransportException: Failed to authenticate on SMTP server with username \"airdrop446646@gmail.com\" using the following authenticators: \"LOGIN\", \"PLAIN\", \"XOAUTH2\". Authenticator \"LOGIN\" returned \"Expected response code \"235\" but got code \"535\", with message \"535-5.7.8 Username and Password not accepted. For more information, go to\r\n535 5.7.8  https://support.google.com/mail/?p=BadCredentials d2e1a72fcca58-7b924aeedb3sm19810815b3a.10 - gsmtp\".\". Authenticator \"PLAIN\" returned \"Expected response code \"235\" but got code \"535\", with message \"535-5.7.8 Username and Password not accepted. For more information, go to\r\n535 5.7.8  https://support.google.com/mail/?p=BadCredentials d2e1a72fcca58-7b924aeedb3sm19810815b3a.10 - gsmtp\".\". Authenticator \"XOAUTH2\" returned \"Expected response code \"235\" but got code \"334\", with message \"334 eyJzdGF0dXMiOiI0MDAiLCJzY2hlbWVzIjoiQmVhcmVyIiwic2NvcGUiOiJodHRwczovL21haWwuZ29vZ2xlLmNvbS8ifQ==\".\". in D:\\laragon\\www\\sass-dashboard\\vendor\\symfony\\mailer\\Transport\\Smtp\\EsmtpTransport.php:226\nStack trace:\n#0 D:\\laragon\\www\\sass-dashboard\\vendor\\symfony\\mailer\\Transport\\Smtp\\EsmtpTransport.php(161): Symfony\\Component\\Mailer\\Transport\\Smtp\\EsmtpTransport->handleAuth(Array)\n#1 D:\\laragon\\www\\sass-dashboard\\vendor\\symfony\\mailer\\Transport\\Smtp\\EsmtpTransport.php(118): Symfony\\Component\\Mailer\\Transport\\Smtp\\EsmtpTransport->doEhloCommand()\n#2 D:\\laragon\\www\\sass-dashboard\\vendor\\symfony\\mailer\\Transport\\Smtp\\SmtpTransport.php(254): Symfony\\Component\\Mailer\\Transport\\Smtp\\EsmtpTransport->executeCommand(\'HELO [127.0.0.1...\', Array)\n#3 D:\\laragon\\www\\sass-dashboard\\vendor\\symfony\\mailer\\Transport\\Smtp\\SmtpTransport.php(277): Symfony\\Component\\Mailer\\Transport\\Smtp\\SmtpTransport->doHeloCommand()\n#4 D:\\laragon\\www\\sass-dashboard\\vendor\\symfony\\mailer\\Transport\\Smtp\\SmtpTransport.php(209): Symfony\\Component\\Mailer\\Transport\\Smtp\\SmtpTransport->start()\n#5 D:\\laragon\\www\\sass-dashboard\\vendor\\symfony\\mailer\\Transport\\AbstractTransport.php(69): Symfony\\Component\\Mailer\\Transport\\Smtp\\SmtpTransport->doSend(Object(Symfony\\Component\\Mailer\\SentMessage))\n#6 D:\\laragon\\www\\sass-dashboard\\vendor\\symfony\\mailer\\Transport\\Smtp\\SmtpTransport.php(137): Symfony\\Component\\Mailer\\Transport\\AbstractTransport->send(Object(Symfony\\Component\\Mime\\Email), Object(Symfony\\Component\\Mailer\\DelayedEnvelope))\n#7 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(573): Symfony\\Component\\Mailer\\Transport\\Smtp\\SmtpTransport->send(Object(Symfony\\Component\\Mime\\Email), Object(Symfony\\Component\\Mailer\\DelayedEnvelope))\n#8 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(335): Illuminate\\Mail\\Mailer->sendSymfonyMessage(Object(Symfony\\Component\\Mime\\Email))\n#9 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\Mailer.php(224): Illuminate\\Mail\\Mailer->send(NULL, Array, Object(Closure))\n#10 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Mail\\MailManager.php(592): Illuminate\\Mail\\Mailer->raw(\'<p>Dear <strong...\', Object(Closure))\n#11 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Support\\Facades\\Facade.php(355): Illuminate\\Mail\\MailManager->__call(\'raw\', Array)\n#12 D:\\laragon\\www\\sass-dashboard\\app\\Jobs\\SendVerificationEmail.php(82): Illuminate\\Support\\Facades\\Facade::__callStatic(\'raw\', Array)\n#13 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): App\\Jobs\\SendVerificationEmail->handle()\n#14 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(41): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#15 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#16 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#17 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(662): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#18 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(128): Illuminate\\Container\\Container->call(Array)\n#19 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(144): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\SendVerificationEmail))\n#20 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(119): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\SendVerificationEmail))\n#21 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Bus\\Dispatcher.php(132): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#22 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(124): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\SendVerificationEmail), false)\n#23 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(144): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(App\\Jobs\\SendVerificationEmail))\n#24 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Pipeline\\Pipeline.php(119): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\SendVerificationEmail))\n#25 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(126): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#26 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\CallQueuedHandler.php(70): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(App\\Jobs\\SendVerificationEmail))\n#27 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Jobs\\Job.php(102): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#28 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(439): Illuminate\\Queue\\Jobs\\Job->fire()\n#29 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(389): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#30 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Worker.php(176): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#31 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(138): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#32 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Queue\\Console\\WorkCommand.php(121): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#33 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#34 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(41): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#35 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#36 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#37 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(662): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#38 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(211): Illuminate\\Container\\Container->call(Array)\n#39 D:\\laragon\\www\\sass-dashboard\\vendor\\symfony\\console\\Command\\Command.php(326): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#40 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(181): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#41 D:\\laragon\\www\\sass-dashboard\\vendor\\symfony\\console\\Application.php(1096): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#42 D:\\laragon\\www\\sass-dashboard\\vendor\\symfony\\console\\Application.php(324): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#43 D:\\laragon\\www\\sass-dashboard\\vendor\\symfony\\console\\Application.php(175): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#44 D:\\laragon\\www\\sass-dashboard\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(201): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#45 D:\\laragon\\www\\sass-dashboard\\artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#46 {main}', '2025-11-19 09:41:08');

-- --------------------------------------------------------

--
-- Table structure for table `footers`
--

CREATE TABLE `footers` (
  `id` bigint UNSIGNED NOT NULL,
  `copyright` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `footers`
--

INSERT INTO `footers` (`id`, `copyright`, `content`, `created_at`, `updated_at`) VALUES
(3, '<p>Copyright &copy;2023. All Rights Reserved</p>', '<p>professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source.</p>', '2024-04-22 08:42:12', '2024-04-22 11:32:04');

-- --------------------------------------------------------

--
-- Table structure for table `home_freshness_items`
--

CREATE TABLE `home_freshness_items` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint UNSIGNED DEFAULT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `position` enum('left','right') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'left',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `serial_number` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `home_freshness_items`
--

INSERT INTO `home_freshness_items` (`id`, `language_id`, `icon`, `title`, `text`, `position`, `status`, `serial_number`, `created_at`, `updated_at`) VALUES
(1, 6, 'fas fa-seedling', 'Handmade Products', 'We collect fresh natural fruits for your healthy life.', 'left', 1, 1, '2026-03-28 10:15:54', '2026-03-29 10:11:26'),
(2, 6, 'fas fa-seedling', 'Organic and Fresh', 'Our products are 100% natural and fresh.', 'left', 1, 2, '2026-03-28 10:16:12', '2026-03-29 10:11:27'),
(3, 6, 'fas fa-seedling', '150+ Organic Items', 'We stock 150+ organic food items for your pantry.', 'left', 1, 3, '2026-03-28 10:16:27', '2026-03-29 10:11:28'),
(4, 6, 'fas fa-seedling', '100% Secure Payment', 'We make sure your payment method stays secure.', 'right', 1, 1, '2026-03-28 10:16:47', '2026-03-29 10:11:27'),
(5, 6, 'fas fa-seedling', 'Temperature Control', 'We keep every item cool and fresh in transit.', 'right', 1, 2, '2026-03-28 10:17:04', '2026-03-29 10:11:28'),
(6, 6, 'fas fa-seedling', 'Super Fast Delivery', 'Fast delivery services, safe and secure from damage.', 'right', 1, 3, '2026-03-28 10:17:23', '2026-03-29 10:11:29');

-- --------------------------------------------------------

--
-- Table structure for table `home_section_settings`
--

CREATE TABLE `home_section_settings` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint NOT NULL,
  `category_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `featured_product_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `featured_product_subtitle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `popular_product_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `popular_product_subtitle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `flash_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `flash_subtitle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `features_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `features_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `features_subtitle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `features_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `home_section_settings`
--

INSERT INTO `home_section_settings` (`id`, `language_id`, `category_title`, `featured_product_title`, `featured_product_subtitle`, `popular_product_title`, `popular_product_subtitle`, `flash_title`, `flash_subtitle`, `features_image`, `features_title`, `features_subtitle`, `features_text`, `created_at`, `updated_at`) VALUES
(11, 6, 'Browse By Categories', 'Featured Products', 'Handpicked picks for today', 'Popular right now', 'Most loved picks across produce, pantry, and seafood.', 'Flash sale', 'Adidas Ultraboost 22 Performance Gym Shoes', '69c7fe0488086.png', 'Why FreshCart', 'Freshness you can feel.', 'Hand-picked produce, secure payment, and fast delivery—every time.', '2026-03-09 11:07:26', '2026-03-28 10:12:52');

-- --------------------------------------------------------

--
-- Table structure for table `home_sliders`
--

CREATE TABLE `home_sliders` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_left_badge_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_left_badge_sub_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_right_badge_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_right_badge_sub_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sub_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `button_text_1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_url_1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_text_2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_url_2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `serial_number` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `home_sliders`
--

INSERT INTO `home_sliders` (`id`, `language_id`, `image`, `image_left_badge_title`, `image_left_badge_sub_title`, `image_right_badge_title`, `image_right_badge_sub_title`, `title`, `sub_title`, `description`, `button_text_1`, `button_url_1`, `button_text_2`, `button_url_2`, `status`, `serial_number`, `created_at`, `updated_at`) VALUES
(1, 6, '69aef3839bf3c.avif', 'Curated Daily', 'Market fresh produce', 'Next Slot', '7:30 PM delivery', 'Exclusive launch week', 'Groceries That Feel <span>Premium</span> Every Day', 'Chef grade produce, artisanal pantry staples, and dairy delivered with white-glove care in under 90 minutes.', 'Start Shopping', '/shop', 'View Deals', '/shop', 1, 1, '2026-03-09 10:21:23', '2026-03-29 09:57:29'),
(2, 6, '69aef3f30f351.avif', 'Transport Quality', 'Temperature controlled', 'Delivery ETA', 'Expected in 42 minutes', 'Fast lane delivery', 'Farm to Fridge With <span>Live Tracking</span>', 'Keep your schedule smooth with precise ETA updates, temperature-safe transport, and dedicated rider support.', 'Shop Fresh', '/shop', 'Explore Weekly Deals', '/shop', 1, 2, '2026-03-09 10:23:15', '2026-03-29 09:57:28');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` bigint NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_default` tinyint NOT NULL DEFAULT '0',
  `dashboard_default` tinyint NOT NULL DEFAULT '0',
  `direction` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `name`, `code`, `is_default`, `dashboard_default`, `direction`, `created_at`, `updated_at`) VALUES
(6, 'English', 'en', 1, 1, 'ltr', '2026-01-26 17:23:11', '2026-03-30 02:53:06'),
(8, 'Bangla', 'bn', 0, 0, 'LTR', '2026-03-28 09:52:00', '2026-03-28 09:52:00');

-- --------------------------------------------------------

--
-- Table structure for table `mail_templates`
--

CREATE TABLE `mail_templates` (
  `id` int NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` blob,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mail_templates`
--

INSERT INTO `mail_templates` (`id`, `type`, `subject`, `body`, `updated_at`) VALUES
(1, 'verify_email', 'Verify Your Email Address', 0x3c703e44656172203c7374726f6e673e7b637573746f6d65725f6e616d657d3c2f7374726f6e673e2c3c2f703e0d0a3c703e5765206a757374206e65656420746f2076657269667920796f757220656d61696c2061646472657373206265666f726520796f752063616e2061636365737320746f20796f75722064617368626f6172642e3c2f703e0d0a3c703e56657269667920796f757220656d61696c20616464726573732c207b766572696669636174696f6e5f6c696e6b7d2e3c2f703e0d0a3c703e5468616e6b20796f752e3c6272202f3e7b776562736974655f7469746c657d3c2f703e, '2024-10-08 11:27:34'),
(2, 'reset_password', 'Reset Your Password', 0x3c703e44656172203c7374726f6e673e7b637573746f6d65725f6e616d657d3c2f7374726f6e673e2c3c2f703e0d0a3c703e57652072656365697665642061207265717565737420746f20726573657420796f75722070617373776f72642e20496620796f75206d616465207468697320726571756573742c20706c6561736520636c69636b20746865206c696e6b2062656c6f7720746f20726573657420796f75722070617373776f72643a203c7374726f6e673e7b70617373776f72645f72657365745f6c696e6b7d3c2f7374726f6e673e3c2f703e0d0a3c703e496620796f75206469646ee2809974207265717565737420746f20726573657420796f75722070617373776f72642c20796f752063616e20736166656c792069676e6f7265207468697320656d61696c2e3c2f703e0d0a3c703e5468616e6b20796f753c6272202f3e3c7374726f6e673e7b776562736974655f7469746c657d3c2f7374726f6e673e3c2f703e, '2024-12-31 09:41:46'),
(3, 'membership_buy', 'membership_buy', NULL, '2024-10-08 11:27:34'),
(4, 'place_order', 'Order Confirmation', 0x3c703e44656172203c7374726f6e673e7b637573746f6d65725f6e616d657d3c2f7374726f6e673e2c3c2f703e0d0a3c703e5468616e6b20796f7520666f7220796f757220726563656e7420707572636861736520776974682075732e2057652061726520706c656173656420746f20636f6e6669726d20796f7572206f726465722023203c7374726f6e673e7b6f726465725f6e756d6265727d3c2f7374726f6e673e2c20706c61636564206f6e203c7374726f6e673e7b646174657d2e3c2f7374726f6e673e3c2f703e0d0a3c703e3c7374726f6e673e4f726465722044657461696c733a3c6272202f3e3c2f7374726f6e673e4974656d2050726963653a207b70726963657d3c6272202f3e5175616e6974793a207b7175616e7469797dc2a0c2a0c2a0203c2f703e0d0a3c703e5468616e6b20796f752e3c6272202f3e7b776562736974655f7469746c657d3c2f703e, '2025-02-04 11:57:43');

-- --------------------------------------------------------

--
-- Table structure for table `memberships`
--

CREATE TABLE `memberships` (
  `id` bigint UNSIGNED NOT NULL,
  `discount` double DEFAULT NULL,
  `coupon_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` double DEFAULT NULL,
  `currency_text` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_symbol` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_text_position` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_symbol_position` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint DEFAULT '0',
  `is_trial` tinyint DEFAULT '0',
  `trial_days` int DEFAULT NULL,
  `receipt` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `transaction_details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `package_id` bigint DEFAULT NULL,
  `user_id` bigint DEFAULT NULL,
  `start_date` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expire_date` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `modified` tinyint DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `memberships`
--

INSERT INTO `memberships` (`id`, `discount`, `coupon_code`, `price`, `currency_text`, `currency_symbol`, `currency_text_position`, `currency_symbol_position`, `payment_method`, `transaction_id`, `status`, `is_trial`, `trial_days`, `receipt`, `transaction_details`, `settings`, `package_id`, `user_id`, `start_date`, `expire_date`, `modified`, `created_at`, `updated_at`) VALUES
(66, NULL, NULL, 299, 'USD', '$', 'left', 'left', 'instamojo', '0', 1, 0, NULL, NULL, NULL, '{\"website_logo\":\"6792620a5426d.png\",\"website_color\":\"#FF0000FF\",\"maintenance_image\":\"6706bc36b9811.jpg\",\"maintenance_message\":\"<p>Maintenance MessageMaintenance Message<\\/p>\",\"website_title\":\"Business Validator\",\"favicon\":\"6792620a5379d.png\",\"currency_text\":\"USD\",\"currency_symbol\":\"$\",\"currency_symbol_position\":\"left\",\"currency_rate\":\"1\",\"package_expire_day\":4,\"email_verification_approval\":1}', 19, 59, '2025-11-21 12:24:12', '275816-08-03 12:24:12', 0, '2025-11-21 06:24:12', '2025-11-21 06:24:12');

-- --------------------------------------------------------

--
-- Table structure for table `menu_builders`
--

CREATE TABLE `menu_builders` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint DEFAULT NULL,
  `menu` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menu_builders`
--

INSERT INTO `menu_builders` (`id`, `language_id`, `menu`, `created_at`, `updated_at`) VALUES
(3, 6, '[{\"title\":\"Home\",\"url\":\"\\/\",\"target\":\"_self\",\"type\":\"prebuilt\"},{\"title\":\"Shop\",\"url\":\"\\/shop\",\"target\":\"_self\",\"type\":\"prebuilt\"},{\"title\":\"Contact\",\"url\":\"\\/contact\",\"target\":\"_self\",\"type\":\"prebuilt\"}]', '2025-11-09 13:16:55', '2026-03-15 08:59:49');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(6, '2014_10_12_000000_create_users_table', 1),
(7, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(8, '2019_08_19_000000_create_failed_jobs_table', 1),
(9, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(10, '2024_04_19_060913_create_admins_table', 1),
(11, '2024_04_20_160125_create_footers_table', 2),
(12, '2024_04_20_160256_create_settings_table', 3),
(13, '2024_04_28_132028_create_roles_table', 4),
(14, '2024_06_10_154409_create_categories_table', 5),
(17, '2024_06_15_080933_create_blogs_table', 6),
(19, '2024_06_27_151909_create_languages_table', 7),
(20, '2024_07_21_142937_create_page_headings_table', 8),
(21, '2024_07_22_142833_create_packages_table', 9),
(22, '2024_10_17_174845_create_payment_gateways_table', 10),
(26, '2024_10_25_151114_create_languages_table', 11),
(27, '2024_10_25_151841_create_memberships_table', 11),
(28, '2024_12_05_143642_create_vendors_table', 11),
(29, '2024_12_05_152401_create_vendor_infos_table', 11),
(30, '2024_12_28_171240_create_blog_contents_table', 12),
(31, '2024_12_31_162506_create_product_categories_table', 13),
(32, '2024_12_31_162520_create_products_table', 13),
(33, '2024_12_31_162527_create_product_contents_table', 13),
(34, '2025_01_01_163801_create_slider_images_table', 14),
(35, '2025_01_05_140756_create_product_variations_table', 15),
(36, '2025_01_12_154001_create_product_settings_table', 16),
(37, '2025_01_12_154021_create_product_coupons_table', 16),
(38, '2025_01_16_140426_create_orders_table', 17),
(39, '2025_01_16_163155_create_order_items_table', 18),
(40, '2025_01_24_091740_create_transactions_table', 19),
(41, '2025_02_14_174030_create_jobs_table', 20),
(42, '2025_02_15_130448_create_shipping_charges_table', 21),
(43, '2025_10_26_160514_create_customers_table', 22),
(44, '2025_11_09_185804_create_menu_builders_table', 22),
(45, '2025_11_14_125417_add_some_column_for_trail_on_packages_table', 23),
(46, '2025_11_20_043655_create_user_langauges_table', 24),
(47, '2025_11_21_112622_create_user_settings_table', 25),
(48, '2025_11_21_132302_create_product_categories_table', 26),
(49, '2025_11_21_151955_add_order_columns_to_tables', 27),
(53, '2025_11_26_141408_create_tables_table', 28),
(54, '2026_01_26_175909_create_home_section_settings_table', 29),
(55, '2026_01_28_185002_create_product_attribute_contents_table', 30),
(56, '2026_01_28_185238_create_product_attribute_values_table', 31),
(57, '2026_01_28_185448_create_product_attribute_value_contents_table', 32),
(58, '2026_01_28_185632_create_product_variants_table', 33),
(59, '2026_01_28_190041_create_product_variant_values_table', 34),
(60, '2026_01_29_000001_create_product_variation_normalized_tables', 35),
(61, '2026_01_29_000002_migrate_product_variations_to_normalized', 35),
(62, '2026_01_30_055454_create_product_options_table', 36),
(63, '2026_01_30_055507_create_product_option_values_table', 36),
(64, '2026_01_30_055519_create_product_variants_table', 36),
(65, '2026_01_30_055531_create_product_variant_values_table', 36),
(66, '2026_02_04_000001_add_serial_fields_to_product_variants_table', 37),
(67, '2026_02_04_000002_create_variant_serials_table', 37),
(68, '2026_02_04_000003_create_variant_serial_batches_table', 38),
(69, '2026_02_04_000004_create_variant_sold_serials_table', 38),
(70, '2026_02_05_000001_create_orders_table', 39),
(71, '2026_02_05_000002_create_order_items_table', 39),
(72, '2026_02_05_000003_add_variant_id_to_order_items_table', 39),
(73, '2026_02_07_000001_add_image_to_product_variants_table', 40),
(74, '2026_02_07_000002_add_stedfast_fields_to_settings_table', 41),
(75, '2026_02_07_000003_add_stedfast_fields_to_orders_table', 42),
(76, '2026_03_02_000001_add_icon_to_product_categories_table', 43),
(77, '2026_03_02_000002_add_flash_sale_fields_to_products_table', 44),
(78, '2026_03_07_000001_add_sslcommerz_to_payment_gateways_table', 45),
(79, '2026_03_07_000002_add_featured_field_to_products_table', 45),
(80, '2026_03_08_072950_create_home_sliders_table', 46),
(81, '2026_03_09_182256_create_carts_table', 47),
(82, '2026_03_28_170000_create_home_freshness_items_table', 48),
(83, '2026_03_29_161316_create_product_reviews_table', 49),
(84, '2026_03_29_200000_add_phone_address_to_users_table', 50),
(85, '2026_03_30_000000_add_product_fields_to_order_items_table', 51),
(86, '2026_03_30_053331_create_wishlists_table', 52),
(87, '2026_03_30_090000_fix_wishlists_user_foreign_key', 53);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint DEFAULT NULL,
  `order_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gateway` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cart_total` decimal(8,2) DEFAULT NULL,
  `pay_amount` decimal(10,0) DEFAULT NULL,
  `discount_amount` decimal(8,2) DEFAULT NULL,
  `tax` decimal(8,2) DEFAULT NULL,
  `shipping_charge` decimal(10,0) DEFAULT NULL,
  `invoice_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_symbol` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_symbol_position` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_text` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_text_position` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `receipt` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delivery_date` date DEFAULT NULL,
  `stedfast_consignment_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stedfast_tracking_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stedfast_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stedfast_message` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stedfast_payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `stedfast_response` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_number`, `billing_name`, `billing_email`, `billing_phone`, `billing_address`, `billing_city`, `shipping_address`, `payment_method`, `gateway`, `cart_total`, `pay_amount`, `discount_amount`, `tax`, `shipping_charge`, `invoice_number`, `currency_symbol`, `currency_symbol_position`, `currency_text`, `currency_text_position`, `payment_status`, `order_status`, `receipt`, `delivery_date`, `stedfast_consignment_id`, `stedfast_tracking_code`, `stedfast_status`, `stedfast_message`, `stedfast_payload`, `stedfast_response`, `created_at`, `updated_at`) VALUES
(160, 59, 'D7BA07CF', 'rana ahmedc', 'ranaahmed269205@gmail.com', '+8801306084771', 'Dhaka\r\nDhaka', 'Pabna', 'Dhaka\r\nDhaka, Pabna, 6613', 'Online Payment', 'SSLCommerz', '900.00', '970', NULL, NULL, '70', NULL, NULL, NULL, NULL, NULL, 'completed', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-15 10:25:31', '2026-03-15 10:25:31'),
(161, 59, '5C4951B8', 'Masud Rana', 'ranaahmed269205@gmail.com', '+8801306084771', 'Pabna', 'pabna', 'Pabna, pabna, 6670', 'Cash Payment', 'Manual', '163.99', '234', NULL, NULL, '70', NULL, NULL, NULL, NULL, NULL, 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-29 11:47:48', '2026-03-29 11:47:48'),
(162, 59, '6B953D35', 'Masud Rana', 'ranaahmed269205@gmail.com', '+8801306084771', 'Pabna', 'pabna', 'Pabna, pabna, 6670', 'Cash Payment', 'Manual', '96.00', '166', NULL, NULL, '70', NULL, NULL, NULL, NULL, NULL, 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-29 11:51:53', '2026-03-29 11:51:53'),
(163, 59, '93C03812', 'Masud Rana', 'ranaahmed269205@gmail.com', '+8801306084771', 'Pabna', 'pabna', 'Pabna, pabna, 6670', 'Cash Payment', 'Manual', '630.00', '700', NULL, NULL, '70', NULL, NULL, NULL, NULL, NULL, 'pending', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-29 12:02:36', '2026-03-29 12:02:36');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint DEFAULT NULL,
  `product_id` bigint DEFAULT NULL,
  `product_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit_price` decimal(10,2) DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `product_option` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `variant_id` bigint UNSIGNED DEFAULT NULL,
  `product_price` decimal(10,0) DEFAULT NULL,
  `qty` int DEFAULT NULL,
  `variations` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `product_image`, `unit_price`, `quantity`, `product_option`, `variant_id`, `product_price`, `qty`, `variations`, `created_at`, `updated_at`) VALUES
(176, 156, 34, NULL, NULL, NULL, NULL, NULL, 62, '50', 1, '[{\"product_id\":34,\"variation_id\":69,\"variation_name\":\"Size\",\"option_name\":\"M\",\"price\":0,\"option_key\":0,\"qty\":1}]', '2026-02-05 11:35:41', '2026-02-05 11:35:41'),
(181, 160, 54, NULL, NULL, NULL, NULL, NULL, 129, '900', 1, '[{\"label\":\"500gm\"}]', '2026-03-15 10:25:31', '2026-03-15 10:25:31'),
(182, 161, 53, NULL, NULL, NULL, NULL, NULL, 120, '164', 1, '[{\"label\":\"23.5 cm, Black\"}]', '2026-03-29 11:47:48', '2026-03-29 11:47:48'),
(183, 162, 52, NULL, NULL, NULL, NULL, NULL, 117, '96', 1, '[{\"label\":\"500gm, Organic\"}]', '2026-03-29 11:51:53', '2026-03-29 11:51:53'),
(184, 163, 54, 'Product', '69ac500560dd7.jpg', '630.00', 1, '500gm', 129, '630', 1, '[{\"label\":\"500gm\"}]', '2026-03-29 12:02:36', '2026-03-29 12:02:36');

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `id` bigint UNSIGNED NOT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(8,2) NOT NULL,
  `term` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trial_days` int DEFAULT NULL,
  `is_trial` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint NOT NULL DEFAULT '0',
  `recomended` tinyint NOT NULL DEFAULT '0',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `features` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `custom_feature` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`id`, `icon`, `title`, `slug`, `price`, `term`, `trial_days`, `is_trial`, `status`, `recomended`, `is_featured`, `features`, `custom_feature`, `created_at`, `updated_at`) VALUES
(13, 'fas fa-star', 'Basic', 'basic', '9.00', 'monthly', NULL, 0, 1, 0, 0, '[\"Custom Domain\"]', NULL, '2025-11-11 10:30:38', '2025-11-11 10:30:38'),
(14, 'fas fa-star', 'Standard', 'standard', '90.00', 'monthly', NULL, 0, 1, 1, 0, '[\"Hostel Management\"]', NULL, '2025-11-11 10:31:02', '2025-11-11 10:48:50'),
(15, 'fas fa-star', 'Premium', 'premium', '199.00', 'monthly', NULL, 0, 1, 0, 0, '[\"Hostel Management\",\"Course Management\",\"Student Management\"]', NULL, '2025-11-11 10:31:20', '2025-11-11 10:49:07'),
(16, 'fas fa-star', 'Basic', 'basic', '99.00', 'yearly', NULL, 0, 1, 0, 0, 'null', NULL, '2025-11-11 10:54:00', '2025-11-11 10:54:00'),
(17, 'fas fa-star', 'Standard', 'standard', '199.00', 'yearly', NULL, 0, 1, 1, 0, '[\"Custom Domain\"]', NULL, '2025-11-11 10:54:33', '2025-11-11 10:54:33'),
(18, 'fas fa-star', 'Premium', 'premium', '299.00', 'yearly', NULL, 0, 1, 0, 0, '[\"Custom Domain\",\"Subdomain\",\"Custom Page\"]', NULL, '2025-11-11 10:54:51', '2025-11-11 10:54:51'),
(19, 'fas fa-star', 'Basic', 'basic', '299.00', 'lifetime', NULL, 0, 1, 0, 0, '[\"Custom Domain\"]', NULL, '2025-11-11 10:56:02', '2025-11-11 10:56:02'),
(20, 'fas fa-star', 'Standard', 'standard', '299.00', 'lifetime', NULL, 0, 1, 1, 0, '[\"Custom Domain\",\"Subdomain\"]', NULL, '2025-11-11 10:56:30', '2025-11-11 10:56:30'),
(21, 'fas fa-star', 'Premium', 'premium', '499.00', 'lifetime', NULL, 0, 1, 0, 0, '[\"Custom Domain\",\"Subdomain\",\"Custom Page\"]', NULL, '2025-11-11 10:56:50', '2025-11-14 06:58:08'),
(24, NULL, 'Free', 'free', '0.00', 'yearly', 10, 1, 1, 0, 0, 'null', NULL, '2025-11-14 07:47:58', '2025-11-14 07:53:32');

-- --------------------------------------------------------

--
-- Table structure for table `page_headings`
--

CREATE TABLE `page_headings` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint NOT NULL,
  `page_not_found` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `about_us_page` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blog_page` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `faq_page` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_page` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_login_page` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_gateways`
--

CREATE TABLE `payment_gateways` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `information` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `keyword` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_gateways`
--

INSERT INTO `payment_gateways` (`id`, `name`, `text`, `type`, `information`, `keyword`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Stripe', NULL, 'automatic', '{\"key\":\"pk_test_UnU1Coi1p5qFGwtpjZMRMgJM\",\"secret\":\"sk_test_QQcg3vGsKRPlW6T3dXcNJsor\",\"text\":\"Pay via your Credit account.\"}', 'stripe', 1, NULL, '2025-11-16 12:32:06'),
(2, 'Paypal', NULL, 'automatic', '{\"sandbox_status\":\"1\",\"client_id\":\"AVYKFEw63FtDt9aeYOe9biyifNI56s2Hc2F1Us11hWoY5GMuegipJRQBfWLiIKNbwQ5tmqKSrQTU3zB3\",\"client_secret\":\"EJY0qOKliVg7wKsR3uPN7lngr9rL1N7q4WV0FulT1h4Fw3_e5Itv1mxSdbtSUwAaQoXQFgq-RLlk_sQu\",\"text\":\"Pay via your Credit account.\"}', 'paypal', 1, NULL, '2025-02-12 13:01:46'),
(3, 'Paytm', NULL, 'automatic', '{\"environment\":\"production\",\"merchant_key\":\"sdasdfasdf\",\"merchant_mid\":\"asdfasdfasdf\",\"merchant_website\":\"asdfasdfdasf\",\"industry_type\":\"Retail\",\"text\":\"Pay via your Credit account.\"}', 'paytm', 1, NULL, '2025-02-12 13:09:48'),
(4, 'Instamojo', NULL, 'automatic', '{\"sandbox_status\":\"production\",\"key\":\"sdfsdf\",\"token\":\"sdfsdaf\",\"text\":\"Pay via your Credit account.\"}', 'instamojo', 1, NULL, '2025-02-12 13:18:21'),
(5, 'SSLCommerz', NULL, 'automatic', '{\"mode\":\"sandbox\",\"store_id\":\"kreat69abf86160419\",\"store_password\":\"kreat69abf86160419@ssl\",\"currency\":\"BDT\",\"text\":\"Pay securely via SSLCommerz.\"}', 'sslcommerz', 1, '2026-03-07 10:33:02', '2026-03-15 09:59:20');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `stock` int DEFAULT NULL,
  `last_restock_qty` bigint DEFAULT NULL,
  `sku` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `thumbnail` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_price` decimal(8,2) DEFAULT NULL,
  `previous_price` decimal(8,2) DEFAULT NULL,
  `flash_sale_status` tinyint NOT NULL DEFAULT '0',
  `flash_sale_price` decimal(8,2) DEFAULT NULL COMMENT 'This price will be calculated as a percentage.',
  `flash_sale_start_at` timestamp NULL DEFAULT NULL,
  `flash_sale_end_at` timestamp NULL DEFAULT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `download_link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `download_file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `featured` tinyint NOT NULL DEFAULT '0',
  `rating` smallint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `order` int NOT NULL DEFAULT '0',
  `has_variants` tinyint NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `stock`, `last_restock_qty`, `sku`, `thumbnail`, `current_price`, `previous_price`, `flash_sale_status`, `flash_sale_price`, `flash_sale_start_at`, `flash_sale_end_at`, `type`, `file_type`, `download_link`, `download_file`, `status`, `featured`, `rating`, `created_at`, `updated_at`, `order`, `has_variants`) VALUES
(50, 100, 100, '1111111', '69a1ced3db594.png', '99.99', '120.99', 0, NULL, NULL, NULL, 'Physical', NULL, NULL, NULL, 1, 1, NULL, '2026-02-27 11:05:23', '2026-03-07 10:33:18', 0, 0),
(51, 0, 0, NULL, '69a1cfd5bc6cc.png', '0.00', NULL, 0, NULL, NULL, NULL, 'Physical', NULL, NULL, NULL, 1, 1, NULL, '2026-02-27 11:09:41', '2026-03-07 10:33:17', 0, 1),
(52, 0, 0, NULL, '69a1d0e6b4237.png', '0.00', NULL, 1, '20.00', '2026-03-27 16:36:00', '2026-09-08 16:36:00', 'Physical', NULL, NULL, NULL, 1, 1, NULL, '2026-02-27 11:14:14', '2026-03-28 11:35:27', 0, 1),
(53, 0, 0, NULL, '69a1d1bc06f73.png', '0.00', NULL, 1, '18.00', '2026-03-27 04:00:00', '2026-09-09 04:00:00', 'Physical', NULL, NULL, NULL, 1, 1, NULL, '2026-02-27 11:17:48', '2026-03-28 11:35:34', 0, 1),
(54, 0, 0, NULL, '69ac500560dd7.jpg', '0.00', NULL, 1, '30.00', '2026-03-27 16:35:00', '2026-08-12 16:35:00', 'Physical', NULL, NULL, NULL, 1, 1, NULL, '2026-03-07 10:19:17', '2026-03-28 11:18:47', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

CREATE TABLE `product_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `serial_number` int NOT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_categories`
--

INSERT INTO `product_categories` (`id`, `language_id`, `name`, `slug`, `icon`, `serial_number`, `status`, `created_at`, `updated_at`) VALUES
(10, 5, 'Beverages', 'beverages', NULL, 1, 1, '2025-01-27 11:52:27', '2025-11-28 11:58:14'),
(11, 5, 'Desserts', 'desserts', NULL, 2, 1, '2025-01-27 11:52:42', '2025-11-28 11:58:22'),
(13, 5, 'Main Course', 'main-course', NULL, 3, 1, '2025-11-28 11:58:32', '2025-11-28 11:58:32'),
(20, 7, 'Electronics', 'electronics-bn', NULL, 0, 1, '2026-02-07 11:33:47', '2026-02-07 11:33:47'),
(21, 7, 'Fashion', 'fashion-bn', NULL, 0, 1, '2026-02-07 11:33:47', '2026-02-07 11:33:47'),
(22, 7, 'Grocery', 'grocery-bn', NULL, 0, 1, '2026-02-07 11:33:47', '2026-02-07 11:33:47'),
(23, 6, 'Tv & Audio', 'tv-&-audio', 'fas fa-tv', 1, 1, '2026-02-27 11:03:42', '2026-03-29 10:11:35'),
(24, 6, 'Media Production', 'media-production', 'fas fa-camera', 2, 1, '2026-02-27 11:03:51', '2026-03-29 10:11:35'),
(25, 6, 'Mobile Device', 'mobile-device', 'fas fa-mobile', 3, 1, '2026-02-27 11:04:08', '2026-03-29 10:11:36'),
(26, 6, 'Home Appliances', 'home-appliances', 'fas fa-utensils', 4, 1, '2026-02-27 11:04:19', '2026-03-29 10:11:36'),
(27, 6, 'Pure Spices', 'pure-spices', 'fas fa-h-square', 5, 1, '2026-02-27 11:11:59', '2026-03-29 10:11:36'),
(28, 6, 'Sportswear', 'sportswear', 'fas fa-bolt', 6, 1, '2026-02-27 11:15:08', '2026-03-29 10:11:37');

-- --------------------------------------------------------

--
-- Table structure for table `product_contents`
--

CREATE TABLE `product_contents` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint DEFAULT NULL,
  `product_id` bigint DEFAULT NULL,
  `category_id` bigint DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `summary` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `meta_keyword` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_contents`
--

INSERT INTO `product_contents` (`id`, `language_id`, `product_id`, `category_id`, `title`, `slug`, `summary`, `description`, `meta_keyword`, `meta_description`, `created_at`, `updated_at`) VALUES
(66, 6, 50, 23, 'UltraVision Pro 27-Inch Full HD LED Monitor with Adaptive Sync and Eye-Care Technology', 'ultravision-pro-27-inch-full-hd-led-monitor-with-adaptive-sync-and-eye-care-technology', 'Transform your viewing experience with the UltraVision Pro 27-Inch Full HD LED Monitor. Featuring stunning visuals, adaptive sync, and eye-care technology, it delivers smooth, flicker-free performance—perfect for work, gaming, and entertainment.', '<p>Immerse yourself in crystal-clear visuals with the UltraVision Pro 27-Inch Full HD LED Monitor. Designed for ultimate performance, it offers vibrant colors, sharp details, and a 75Hz refresh rate, ensuring smooth motion and reduced lag. Adaptive Sync technology eliminates screen tearing, providing a seamless experience for gamers and creatives alike.</p>\r\n<p>The monitor\'s advanced Eye-Care technology reduces blue light emissions and flickering, minimizing eye strain during extended use. Its sleek, frameless design maximizes screen space, making it a stylish addition to any workspace or gaming setup. Multiple connectivity options, including HDMI and VGA ports, ensure compatibility with various devices.</p>', NULL, NULL, '2026-02-27 11:05:23', '2026-02-27 11:05:41'),
(67, 6, 51, 23, '55\" 4K Smart LED TV with HDR and Voice Control', '55\"-4k-smart-led-tv-with-hdr-and-voice-control', 'A sleek 55\" 4K Smart LED TV featuring HDR technology and voice control. Enjoy stunning visuals, smart apps, and seamless streaming for an ultimate viewing experience.', '<div class=\"product-single-tab pt-70\">\r\n<div class=\"tab-content radius-lg\">\r\n<div id=\"desc\" class=\"tab-pane fade active show\">\r\n<div class=\"tab-description\">\r\n<div class=\"row align-items-center\">\r\n<div class=\"col-md-12\">\r\n<div class=\"content mb-30 tinymce-content\">\r\n<div class=\"tab-pane fade active show\">\r\n<div class=\"tab-description\">\r\n<div class=\"row align-items-center\">\r\n<div class=\"col-md-12\">\r\n<div class=\"content mb-30 summernote-content\">\r\n<div class=\"tab-pane fade active show\">\r\n<div class=\"tab-description\">\r\n<div class=\"row align-items-center\">\r\n<div class=\"col-md-12\">\r\n<div class=\"content mb-30 summernote-content\">\r\n<p>Experience true-to-life picture quality with the 55\" 4K Smart LED TV. Equipped with HDR technology, this TV delivers stunning contrast, vivid colors, and sharp detail, bringing your favorite movies and shows to life. The built-in smart platform gives you access to popular streaming apps like Netflix, YouTube, and Prime Video. Integrated voice control lets you navigate effortlessly, while multiple HDMI and USB ports ensure easy connectivity with other devices. Its slim, modern design enhances any living space, providing both elegance and entertainment. Perfect for families, gamers, and movie enthusiasts.</p>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n</div>', NULL, NULL, '2026-02-27 11:09:41', '2026-02-27 11:09:41'),
(68, 6, 52, 27, 'Spicy Chili Powder – Bold, Fiery Heat for Your Dishes', 'spicy-chili-powder-–-bold-fiery-heat-for-your-dishes', 'Add a fiery kick to your meals with our Chili Powder. This aromatic, hot spice is perfect for seasoning curries, soups, sauces, marinades, and more. With a rich, vibrant flavor, it\'s the ideal ingredient for anyone who loves heat and bold flavors in their cooking.', '<p>Our&nbsp;<strong>Chili Powder</strong>&nbsp;is crafted from the finest dried chili peppers, ground into a smooth powder that retains all the bold, spicy heat you crave. It&rsquo;s perfect for spicing up your curries, soups, stews, and sauces, and works wonderfully in marinades, dressings, and even for sprinkling on roasted vegetables.</p>\r\n<p>With its vibrant red color and rich, intense heat, this&nbsp;<strong>Chili Powder</strong>&nbsp;adds not only spice but depth and complexity to your dishes. Whether you\'re making a traditional chili, adding heat to a Tex-Mex dish, or giving your homemade sauces a fiery boost, this powder is a must-have for any kitchen.</p>\r\n<p><strong>Why Choose Our Chili Powder?</strong></p>\r\n<ul>\r\n<li><strong>Rich, Fiery Heat:</strong>&nbsp;Made from carefully selected chili peppers, it brings bold and intense heat to your meals.</li>\r\n<li><strong>Versatile Spice:</strong>&nbsp;Perfect for a variety of dishes, from curries to marinades, sauces, and more.</li>\r\n<li><strong>No Artificial Additives:</strong>&nbsp;Free from preservatives and artificial colors&mdash;just pure, natural chili flavor.</li>\r\n<li><strong>Easy to Use:</strong>&nbsp;The finely ground powder easily dissolves into your dishes, giving you the perfect balance of heat and flavor every time.</li>\r\n</ul>\r\n<p><strong>Key Features:</strong></p>\r\n<ul>\r\n<li><strong>Made from High-Quality Chili Peppers:</strong>&nbsp;Ground into a fine powder to preserve flavor and heat.</li>\r\n<li><strong>Versatile for Cooking:</strong>&nbsp;Ideal for curries, soups, stews, sauces, marinades, and dressings.</li>\r\n<li><strong>Natural and Pure:</strong>&nbsp;Contains no artificial preservatives or colorants.</li>\r\n<li><strong>Bold and Aromatic:</strong>&nbsp;Adds intense heat and depth to your dishes.</li>\r\n</ul>', NULL, NULL, '2026-02-27 11:14:14', '2026-02-27 11:14:14'),
(69, 6, 53, 28, 'Adidas Ultraboost 22 Performance Gym Shoes', 'adidas-ultraboost-22-performance-gym-shoes', 'The Adidas Ultraboost 22 Performance Gym Shoes are engineered for maximum comfort and performance, featuring a responsive Boost midsole and breathable mesh upper. Perfect for both gym workouts and outdoor activities, these shoes provide the support and flexibility you need to stay at the top of your game.', '<p>The&nbsp;<strong>Adidas Ultraboost 22 Performance Gym Shoes</strong>&nbsp;are designed to help you perform at your best, no matter the workout. The signature Boost midsole technology offers superior cushioning and energy return with every step, providing a responsive feel that helps you power through intense sessions. The breathable mesh upper enhances airflow, keeping your feet cool and dry during even the toughest workouts.</p>\r\n<p>These shoes feature a durable rubber outsole with a grippy pattern, providing excellent traction on a variety of surfaces. Whether you\'re lifting, running, or doing agility drills, the&nbsp;<strong>Ultraboost 22</strong>&nbsp;ensures stability and support, giving you the confidence to push yourself further. The sleek, modern design with reflective details also adds a stylish touch, making these shoes perfect for both the gym and casual wear.</p>\r\n<p>Available in a range of colors, the&nbsp;<strong>Adidas Ultraboost 22 Gym Shoes</strong>&nbsp;combine performance with comfort and style, making them an essential addition to any fitness enthusiast\'s collection.</p>', NULL, NULL, '2026-02-27 11:17:48', '2026-02-27 11:17:48'),
(70, 6, 54, 28, 'প্রিমিয়াম ঘি (Ghee)', 'প্রিমিয়াম-ঘি-(ghee)', 'খাঁটি গরুর দুধ থেকে তৈরি আমাদের ঘি, যা আপনার প্রতিদিনের রান্না ও খাবারে যোগ করবে বিশেষ স্বাদ ও পুষ্টিগুণ। আপনার নিত্যদিনের খাবারের স্বাদ বাড়ানোর জন্য হতে পারে সেরা পছন্দ।', '<p>খাঁটি গরুর দুধ থেকে তৈরি ঘি, যা আপনার প্রতিদিনের রান্না ও খাবারে যোগ করবে বিশেষ স্বাদ ও পুষ্টিগুণ। আমাদের ঘি সম্পূর্ণ প্রাকৃতিক এবং কোনো প্রকার কৃত্রিম উপাদান ছাড়া তৈরি। এটি আপনার নিত্যদিনের খাবারের স্বাদ বাড়ানোর জন্য হতে পারে সেরা পছন্দ।</p>\r\n<p>&nbsp;</p>\r\n<h2>ঘি এর উপকারিতা:</h2>\r\n<p>১.&nbsp;ঘি হজম প্রক্রিয়া উন্নত করে এবং অন্ত্রের স্বাস্থ্য ভালো রাখতে সহায়তা করে।</p>\r\n<p>২.&nbsp;ঘি ত্বকের আর্দ্রতা ধরে রাখে এবং উজ্জ্বলতা বাড়ায়।</p>\r\n<p>৩.&nbsp;ঘি শরীরকে তাৎক্ষণিক শক্তি প্রদান করে এবং দীর্ঘস্থায়ী এনার্জি ধরে রাখতে সহায়তা করে।</p>\r\n<p>৪.&nbsp;ঘি-তে থাকা ভিটামিন এ, ডি, ই, এবং কে রোগ প্রতিরোধ ক্ষমতা বাড়ায়।</p>\r\n<p>৫.&nbsp;পরিমিত পরিমাণে ঘি সেবন হার্টের জন্য উপকারী।</p>\r\n<p>৬.&nbsp;ঘি-তে থাকা স্বাস্থ্যকর ফ্যাট মস্তিষ্কের কার্যক্ষমতা বাড়াতে সাহায্য করে ।</p>\r\n<p>৭.&nbsp;ঘি শরীরের জয়েন্টের প্রদাহ কমায় এবং ব্যথা উপশমে সহায়তা করে।</p>\r\n<p>৮.&nbsp;ঘি-তে থাকা স্বাস্থ্যকর ফ্যাট মেটাবলিজম বাড়িয়ে ওজন নিয়ন্ত্রণে রাখে।</p>\r\n<p>৯.&nbsp;ঘি-তে থাকা অ্যান্টিঅক্সিডেন্ট শরীর থেকে বিষাক্ত পদার্থ বের করতে সহায়তা করে।</p>\r\n<p>১০.&nbsp;ঘি যে কোনো খাবারের স্বাদ ও গন্ধ বাড়িয়ে তোলে।</p>\r\n<p>&nbsp;</p>\r\n<h2>কেন ফালাক ফুডের ঘি অনন্য?</h2>\r\n<p>১. খাটি গরুর দুধ থেকে তৈরি।<br>২.আসল স্বাদ ও পুষ্টি ধরে রাখে ।<br>৩. খাবারে যোগ করে বাড়তি স্বাদ।<br>৪. ভিটামিন এ, ডি, ই, এবং কে সমৃদ্ধ।<br>৫.মান ও বিশুদ্ধতা নিশ্চিত করে ।<br>৬. কোনো রাসায়নিক উপাদান নেই।<br>৭. রান্না, ভাজা ও মিষ্টিতে ব্যবহার উপযোগী।</p>\r\n<p>&nbsp;</p>\r\n<p>Pure ghee from fresh cow&rsquo;s milk, is ideal for cooking or adding a rich, traditional flavor to your meals. It&rsquo;s natural, free from additives, and perfect for everyday use.</p>\r\n<p>&nbsp;</p>\r\n<h2>Benefits of Ghee:</h2>\r\n<ol>\r\n<li aria-level=\"1\">Loaded with essential vitamins A, D, E, and K for overall health.</li>\r\n<li aria-level=\"1\">It contains beneficial fats that provide energy and support brain function.</li>\r\n<li aria-level=\"1\">Packed with antioxidants to strengthen the immune system.</li>\r\n<li aria-level=\"1\">Vitamin D helps calcium absorption for stronger bones.</li>\r\n<li aria-level=\"1\">Contains butyric acid, which promotes a healthy gut.</li>\r\n<li aria-level=\"1\">Keeps skin hydrated and nourished from within.</li>\r\n<li aria-level=\"1\">Helps reduce joint pain and inflammation.</li>\r\n<li aria-level=\"1\">Stimulates metabolism and aids in fat breakdown.</li>\r\n<li aria-level=\"1\">Provides sustained energy without unhealthy additives.</li>\r\n<li aria-level=\"1\">In moderation, it may support healthy cholesterol levels.</li>\r\n</ol>\r\n<p>&nbsp;</p>\r\n<h2>Choose Pure Ghee at the Best Prices</h2>\r\n<ol>\r\n<li aria-level=\"1\">&nbsp;Made from high-quality cow&rsquo;s milk without additives or preservatives.</li>\r\n<li aria-level=\"1\">Retains authentic taste and nutritional value.</li>\r\n<li aria-level=\"1\">Enhances the taste of any dish effortlessly.</li>\r\n<li aria-level=\"1\">Packed with essential vitamins like A, D, E, and K.</li>\r\n<li aria-level=\"1\">Ensures purity and premium quality in every jar.</li>\r\n<li aria-level=\"1\">No hydrogenated oils or harmful chemicals are used.</li>\r\n<li aria-level=\"1\">Perfect for cooking, frying, baking, or adding flavor.</li>\r\n</ol>', NULL, NULL, '2026-03-07 10:19:17', '2026-03-07 10:19:17'),
(71, 7, 54, 22, 'প্রিমিয়াম ঘি (Ghee)', 'প্রিমিয়াম-ঘি-(ghee)', 'খাঁটি গরুর দুধ থেকে তৈরি আমাদের ঘি, যা আপনার প্রতিদিনের রান্না ও খাবারে যোগ করবে বিশেষ স্বাদ ও পুষ্টিগুণ। আপনার নিত্যদিনের খাবারের স্বাদ বাড়ানোর জন্য হতে পারে সেরা পছন্দ।', '<p>খাঁটি গরুর দুধ থেকে তৈরি ঘি, যা আপনার প্রতিদিনের রান্না ও খাবারে যোগ করবে বিশেষ স্বাদ ও পুষ্টিগুণ। আমাদের ঘি সম্পূর্ণ প্রাকৃতিক এবং কোনো প্রকার কৃত্রিম উপাদান ছাড়া তৈরি। এটি আপনার নিত্যদিনের খাবারের স্বাদ বাড়ানোর জন্য হতে পারে সেরা পছন্দ।</p>\r\n<p>&nbsp;</p>\r\n<h2>ঘি এর উপকারিতা:</h2>\r\n<p>১.&nbsp;ঘি হজম প্রক্রিয়া উন্নত করে এবং অন্ত্রের স্বাস্থ্য ভালো রাখতে সহায়তা করে।</p>\r\n<p>২.&nbsp;ঘি ত্বকের আর্দ্রতা ধরে রাখে এবং উজ্জ্বলতা বাড়ায়।</p>\r\n<p>৩.&nbsp;ঘি শরীরকে তাৎক্ষণিক শক্তি প্রদান করে এবং দীর্ঘস্থায়ী এনার্জি ধরে রাখতে সহায়তা করে।</p>\r\n<p>৪.&nbsp;ঘি-তে থাকা ভিটামিন এ, ডি, ই, এবং কে রোগ প্রতিরোধ ক্ষমতা বাড়ায়।</p>\r\n<p>৫.&nbsp;পরিমিত পরিমাণে ঘি সেবন হার্টের জন্য উপকারী।</p>\r\n<p>৬.&nbsp;ঘি-তে থাকা স্বাস্থ্যকর ফ্যাট মস্তিষ্কের কার্যক্ষমতা বাড়াতে সাহায্য করে ।</p>\r\n<p>৭.&nbsp;ঘি শরীরের জয়েন্টের প্রদাহ কমায় এবং ব্যথা উপশমে সহায়তা করে।</p>\r\n<p>৮.&nbsp;ঘি-তে থাকা স্বাস্থ্যকর ফ্যাট মেটাবলিজম বাড়িয়ে ওজন নিয়ন্ত্রণে রাখে।</p>\r\n<p>৯.&nbsp;ঘি-তে থাকা অ্যান্টিঅক্সিডেন্ট শরীর থেকে বিষাক্ত পদার্থ বের করতে সহায়তা করে।</p>\r\n<p>১০.&nbsp;ঘি যে কোনো খাবারের স্বাদ ও গন্ধ বাড়িয়ে তোলে।</p>\r\n<p>&nbsp;</p>\r\n<h2>কেন ফালাক ফুডের ঘি অনন্য?</h2>\r\n<p>১. খাটি গরুর দুধ থেকে তৈরি।<br>২.আসল স্বাদ ও পুষ্টি ধরে রাখে ।<br>৩. খাবারে যোগ করে বাড়তি স্বাদ।<br>৪. ভিটামিন এ, ডি, ই, এবং কে সমৃদ্ধ।<br>৫.মান ও বিশুদ্ধতা নিশ্চিত করে ।<br>৬. কোনো রাসায়নিক উপাদান নেই।<br>৭. রান্না, ভাজা ও মিষ্টিতে ব্যবহার উপযোগী।</p>\r\n<p>&nbsp;</p>\r\n<p>Pure ghee from fresh cow&rsquo;s milk, is ideal for cooking or adding a rich, traditional flavor to your meals. It&rsquo;s natural, free from additives, and perfect for everyday use.</p>\r\n<p>&nbsp;</p>\r\n<h2>Benefits of Ghee:</h2>\r\n<ol>\r\n<li aria-level=\"1\">Loaded with essential vitamins A, D, E, and K for overall health.</li>\r\n<li aria-level=\"1\">It contains beneficial fats that provide energy and support brain function.</li>\r\n<li aria-level=\"1\">Packed with antioxidants to strengthen the immune system.</li>\r\n<li aria-level=\"1\">Vitamin D helps calcium absorption for stronger bones.</li>\r\n<li aria-level=\"1\">Contains butyric acid, which promotes a healthy gut.</li>\r\n<li aria-level=\"1\">Keeps skin hydrated and nourished from within.</li>\r\n<li aria-level=\"1\">Helps reduce joint pain and inflammation.</li>\r\n<li aria-level=\"1\">Stimulates metabolism and aids in fat breakdown.</li>\r\n<li aria-level=\"1\">Provides sustained energy without unhealthy additives.</li>\r\n<li aria-level=\"1\">In moderation, it may support healthy cholesterol levels.</li>\r\n</ol>\r\n<p>&nbsp;</p>\r\n<h2>Choose Pure Ghee at the Best Prices</h2>\r\n<ol>\r\n<li aria-level=\"1\">&nbsp;Made from high-quality cow&rsquo;s milk without additives or preservatives.</li>\r\n<li aria-level=\"1\">Retains authentic taste and nutritional value.</li>\r\n<li aria-level=\"1\">Enhances the taste of any dish effortlessly.</li>\r\n<li aria-level=\"1\">Packed with essential vitamins like A, D, E, and K.</li>\r\n<li aria-level=\"1\">Ensures purity and premium quality in every jar.</li>\r\n<li aria-level=\"1\">No hydrogenated oils or harmful chemicals are used.</li>\r\n<li aria-level=\"1\">Perfect for cooking, frying, baking, or adding flavor.</li>\r\n</ol>', NULL, NULL, '2026-03-07 10:19:17', '2026-03-07 10:19:17');

-- --------------------------------------------------------

--
-- Table structure for table `product_coupons`
--

CREATE TABLE `product_coupons` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('fixed','percentage') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `value` decimal(8,2) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `amount_spend` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_coupons`
--

INSERT INTO `product_coupons` (`id`, `name`, `code`, `type`, `value`, `start_date`, `end_date`, `amount_spend`, `created_at`, `updated_at`) VALUES
(4, 'SUPER99', 'SUPER99', 'fixed', '100.00', '2026-02-27', '2026-04-24', '399.00', '2026-02-27 11:18:41', '2026-02-27 11:18:41');

-- --------------------------------------------------------

--
-- Table structure for table `product_options`
--

CREATE TABLE `product_options` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` int UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_options`
--

INSERT INTO `product_options` (`id`, `product_id`, `name`, `position`, `created_at`, `updated_at`) VALUES
(60, 51, 'Color', 0, '2026-02-27 11:09:41', '2026-02-27 11:09:41'),
(61, 51, 'Audio Technology', 1, '2026-02-27 11:09:41', '2026-02-27 11:09:41'),
(62, 52, 'Size', 0, '2026-02-27 11:14:14', '2026-02-27 11:14:14'),
(63, 52, 'Quality', 1, '2026-02-27 11:14:14', '2026-02-27 11:14:14'),
(64, 53, 'Size', 0, '2026-02-27 11:17:48', '2026-02-27 11:17:48'),
(65, 53, 'Color', 1, '2026-02-27 11:17:48', '2026-02-27 11:17:48'),
(66, 54, 'Size', 0, '2026-03-07 10:19:17', '2026-03-07 10:19:17');

-- --------------------------------------------------------

--
-- Table structure for table `product_option_values`
--

CREATE TABLE `product_option_values` (
  `id` bigint UNSIGNED NOT NULL,
  `product_option_id` bigint UNSIGNED NOT NULL,
  `value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` int UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_option_values`
--

INSERT INTO `product_option_values` (`id`, `product_option_id`, `value`, `position`, `created_at`, `updated_at`) VALUES
(124, 60, 'Black', 0, '2026-02-27 11:09:41', '2026-02-27 11:09:41'),
(125, 60, 'Metallic Silver', 1, '2026-02-27 11:09:41', '2026-02-27 11:09:41'),
(126, 61, 'Dolby Audio', 0, '2026-02-27 11:09:41', '2026-02-27 11:09:41'),
(127, 61, 'Dolby Atmos', 1, '2026-02-27 11:09:41', '2026-02-27 11:09:41'),
(128, 62, '500gm', 0, '2026-02-27 11:14:14', '2026-02-27 11:14:14'),
(129, 62, '1kg', 1, '2026-02-27 11:14:14', '2026-02-27 11:14:14'),
(130, 63, 'Fine Grind', 0, '2026-02-27 11:14:14', '2026-02-27 11:14:14'),
(131, 63, 'Organic', 1, '2026-02-27 11:14:14', '2026-02-27 11:14:14'),
(132, 64, '23.5 cm', 0, '2026-02-27 11:17:48', '2026-02-27 11:17:48'),
(133, 64, '24.4 cm', 1, '2026-02-27 11:17:48', '2026-02-27 11:17:48'),
(134, 64, '25.5 cm', 2, '2026-02-27 11:17:48', '2026-02-27 11:17:48'),
(135, 65, 'Black', 0, '2026-02-27 11:17:48', '2026-02-27 11:17:48'),
(136, 65, 'White', 1, '2026-02-27 11:17:48', '2026-02-27 11:17:48'),
(137, 65, 'Gray', 2, '2026-02-27 11:17:48', '2026-02-27 11:17:48'),
(138, 66, '500gm', 0, '2026-03-07 10:19:17', '2026-03-07 10:19:17'),
(139, 66, '1Kg', 1, '2026-03-07 10:19:17', '2026-03-07 10:19:17');

-- --------------------------------------------------------

--
-- Table structure for table `product_reviews`
--

CREATE TABLE `product_reviews` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `rating` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_reviews`
--

INSERT INTO `product_reviews` (`id`, `product_id`, `user_id`, `rating`, `comment`, `created_at`, `updated_at`) VALUES
(1, 54, 59, 4, 'dsfsadfsadf', '2026-03-29 11:07:25', '2026-03-29 11:36:03'),
(2, 50, 59, 5, 'dddddddd', '2026-03-30 02:56:46', '2026-03-30 02:56:46');

-- --------------------------------------------------------

--
-- Table structure for table `product_settings`
--

CREATE TABLE `product_settings` (
  `id` bigint UNSIGNED NOT NULL,
  `digital_product` tinyint NOT NULL DEFAULT '0',
  `physical_product` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_settings`
--

INSERT INTO `product_settings` (`id`, `digital_product`, `physical_product`, `created_at`, `updated_at`) VALUES
(1, 0, 1, '2026-02-02 06:18:53', '2026-02-05 12:20:04');

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

CREATE TABLE `product_variants` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `sku` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(8,2) DEFAULT NULL,
  `stock` int NOT NULL DEFAULT '0',
  `status` tinyint NOT NULL DEFAULT '1',
  `track_serial` tinyint(1) NOT NULL DEFAULT '0',
  `serial_start` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `serial_end` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_variants`
--

INSERT INTO `product_variants` (`id`, `product_id`, `sku`, `image`, `price`, `stock`, `status`, `track_serial`, `serial_start`, `serial_end`, `created_at`, `updated_at`) VALUES
(112, 51, 'LED-TV-1', NULL, '399.99', 10, 1, 1, '1', '10', '2026-02-27 11:09:41', '2026-02-27 11:09:41'),
(113, 51, 'LED-TV-2', NULL, '299.99', 10, 1, 1, '1', '10', '2026-02-27 11:09:41', '2026-02-27 11:09:41'),
(114, 51, 'LED-TV-3', NULL, '199.99', 10, 1, 1, '1', '10', '2026-02-27 11:09:41', '2026-02-27 11:09:41'),
(115, 51, 'LED-TV-4', NULL, '499.99', 10, 1, 1, '1', '10', '2026-02-27 11:09:41', '2026-02-27 11:09:41'),
(116, 52, 'CHILI-4', NULL, '100.00', 10, 1, 1, '1', '10', '2026-02-27 11:14:14', '2026-02-27 11:14:14'),
(117, 52, 'CHILI-1', NULL, '120.00', 10, 1, 1, '1', '10', '2026-02-27 11:14:14', '2026-02-27 11:14:14'),
(118, 52, 'CHILI-3', NULL, '110.00', 10, 1, 1, '1', '10', '2026-02-27 11:14:14', '2026-02-27 11:14:14'),
(119, 52, 'CHILI-2', NULL, '90.00', 10, 1, 1, '1', '10', '2026-02-27 11:14:14', '2026-02-27 11:14:14'),
(120, 53, 'SHOE-1', NULL, '199.99', 10, 1, 1, '1', '10', '2026-02-27 11:17:48', '2026-02-27 11:17:48'),
(121, 53, 'SHOE-2', NULL, '199.99', 10, 1, 1, '1', '10', '2026-02-27 11:17:48', '2026-02-27 11:17:48'),
(122, 53, 'SHOE-3', NULL, '199.99', 10, 1, 1, '1', '10', '2026-02-27 11:17:48', '2026-02-27 11:17:48'),
(123, 53, 'SHOE-4', NULL, '199.99', 10, 1, 1, '1', '10', '2026-02-27 11:17:48', '2026-02-27 11:17:48'),
(124, 53, 'SHOE-5', NULL, '199.99', 10, 1, 1, '1', '10', '2026-02-27 11:17:48', '2026-02-27 11:17:48'),
(125, 53, 'SHOE-6', NULL, '199.99', 10, 1, 1, '1', '10', '2026-02-27 11:17:48', '2026-02-27 11:17:48'),
(126, 53, 'SHOE-7', NULL, '199.99', 10, 1, 1, '1', '10', '2026-02-27 11:17:48', '2026-02-27 11:17:48'),
(127, 53, 'SHOE-8', NULL, '199.99', 10, 1, 1, '1', '10', '2026-02-27 11:17:48', '2026-02-27 11:17:48'),
(128, 53, 'SHOE-9', NULL, '199.99', 10, 1, 1, '1', '10', '2026-02-27 11:17:48', '2026-02-27 11:17:48'),
(129, 54, NULL, NULL, '900.00', 5, 1, 0, NULL, NULL, '2026-03-07 10:19:17', '2026-03-07 10:19:17'),
(130, 54, NULL, NULL, '1600.00', 5, 1, 0, NULL, NULL, '2026-03-07 10:19:17', '2026-03-07 10:19:17');

-- --------------------------------------------------------

--
-- Table structure for table `product_variant_values`
--

CREATE TABLE `product_variant_values` (
  `id` bigint UNSIGNED NOT NULL,
  `variant_id` bigint UNSIGNED NOT NULL,
  `option_value_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_variant_values`
--

INSERT INTO `product_variant_values` (`id`, `variant_id`, `option_value_id`, `created_at`, `updated_at`) VALUES
(166, 112, 124, '2026-02-27 11:09:41', '2026-02-27 11:09:41'),
(167, 112, 126, '2026-02-27 11:09:41', '2026-02-27 11:09:41'),
(168, 113, 124, '2026-02-27 11:09:41', '2026-02-27 11:09:41'),
(169, 113, 127, '2026-02-27 11:09:41', '2026-02-27 11:09:41'),
(170, 114, 125, '2026-02-27 11:09:41', '2026-02-27 11:09:41'),
(171, 114, 126, '2026-02-27 11:09:41', '2026-02-27 11:09:41'),
(172, 115, 125, '2026-02-27 11:09:41', '2026-02-27 11:09:41'),
(173, 115, 127, '2026-02-27 11:09:41', '2026-02-27 11:09:41'),
(174, 116, 128, '2026-02-27 11:14:14', '2026-02-27 11:14:14'),
(175, 116, 130, '2026-02-27 11:14:14', '2026-02-27 11:14:14'),
(176, 117, 128, '2026-02-27 11:14:14', '2026-02-27 11:14:14'),
(177, 117, 131, '2026-02-27 11:14:14', '2026-02-27 11:14:14'),
(178, 118, 129, '2026-02-27 11:14:14', '2026-02-27 11:14:14'),
(179, 118, 130, '2026-02-27 11:14:14', '2026-02-27 11:14:14'),
(180, 119, 129, '2026-02-27 11:14:14', '2026-02-27 11:14:14'),
(181, 119, 131, '2026-02-27 11:14:14', '2026-02-27 11:14:14'),
(182, 120, 132, '2026-02-27 11:17:48', '2026-02-27 11:17:48'),
(183, 120, 135, '2026-02-27 11:17:48', '2026-02-27 11:17:48'),
(184, 121, 132, '2026-02-27 11:17:48', '2026-02-27 11:17:48'),
(185, 121, 136, '2026-02-27 11:17:48', '2026-02-27 11:17:48'),
(186, 122, 132, '2026-02-27 11:17:48', '2026-02-27 11:17:48'),
(187, 122, 137, '2026-02-27 11:17:48', '2026-02-27 11:17:48'),
(188, 123, 133, '2026-02-27 11:17:48', '2026-02-27 11:17:48'),
(189, 123, 135, '2026-02-27 11:17:48', '2026-02-27 11:17:48'),
(190, 124, 133, '2026-02-27 11:17:48', '2026-02-27 11:17:48'),
(191, 124, 136, '2026-02-27 11:17:48', '2026-02-27 11:17:48'),
(192, 125, 133, '2026-02-27 11:17:48', '2026-02-27 11:17:48'),
(193, 125, 137, '2026-02-27 11:17:48', '2026-02-27 11:17:48'),
(194, 126, 134, '2026-02-27 11:17:48', '2026-02-27 11:17:48'),
(195, 126, 135, '2026-02-27 11:17:48', '2026-02-27 11:17:48'),
(196, 127, 134, '2026-02-27 11:17:48', '2026-02-27 11:17:48'),
(197, 127, 136, '2026-02-27 11:17:48', '2026-02-27 11:17:48'),
(198, 128, 134, '2026-02-27 11:17:48', '2026-02-27 11:17:48'),
(199, 128, 137, '2026-02-27 11:17:48', '2026-02-27 11:17:48'),
(200, 129, 138, '2026-03-07 10:19:17', '2026-03-07 10:19:17'),
(201, 130, 139, '2026-03-07 10:19:17', '2026-03-07 10:19:17');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `permission` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `permission`, `created_at`, `updated_at`) VALUES
(6, 'test role', NULL, '2024-06-10 08:47:41', '2024-06-10 08:47:41'),
(7, 'ROL', NULL, '2024-06-26 11:07:33', '2024-06-26 11:07:33');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint UNSIGNED NOT NULL,
  `uniqid` int NOT NULL,
  `website_logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo_two` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `footer_logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `favicon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `smtp_host` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `smtp_port` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `smtp_username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `smtp_password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `encryption` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sender_mail` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sender_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `smtp_status` tinyint NOT NULL DEFAULT '0',
  `currency_symbol` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_symbol_position` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_text` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_text_position` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_rate` decimal(10,0) DEFAULT NULL,
  `timezone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website_color` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `maintenance_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `maintenance_status` tinyint DEFAULT '0',
  `maintenance_message` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `bypass_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `package_expire_day` int DEFAULT NULL,
  `admin_approval` tinyint DEFAULT '0',
  `email_verification_approval` tinyint DEFAULT '0',
  `admin_approval_notice` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `pusher_app_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pusher_status` tinyint NOT NULL DEFAULT '0',
  `pusher_app_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pusher_app_secret` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pusher_app_cluster` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stedfast_api_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stedfast_secret_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stedfast_status` tinyint NOT NULL DEFAULT '0',
  `gemini_status` tinyint NOT NULL DEFAULT '0',
  `gemini_api_key` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gemini_image_model` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gemini_text_model` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `uniqid`, `website_logo`, `logo_two`, `footer_logo`, `favicon`, `website_title`, `email_address`, `contact_number`, `address`, `smtp_host`, `smtp_port`, `smtp_username`, `smtp_password`, `encryption`, `sender_mail`, `sender_name`, `smtp_status`, `currency_symbol`, `currency_symbol_position`, `currency_text`, `currency_text_position`, `currency_rate`, `timezone`, `website_color`, `maintenance_image`, `maintenance_status`, `maintenance_message`, `bypass_token`, `package_expire_day`, `admin_approval`, `email_verification_approval`, `admin_approval_notice`, `pusher_app_id`, `pusher_status`, `pusher_app_key`, `pusher_app_secret`, `pusher_app_cluster`, `stedfast_api_key`, `stedfast_secret_key`, `stedfast_status`, `gemini_status`, `gemini_api_key`, `gemini_image_model`, `gemini_text_model`, `created_at`, `updated_at`) VALUES
(1, 1234, '6792620a5426d.png', NULL, '6623f11a26d49.png', '6792620a5379d.png', 'Business Validator', NULL, NULL, NULL, 'smtp.gmail.com', '587', 'airdrop446646@gmail.com', 'lwee cjer feik pdof', 'TLS', 'airdrop446646@gmail.com', 'Myapp', 1, '৳', 'left', 'TK', 'right', '1', 'Europe/Andorra', '#FF0000FF', '6706bc36b9811.jpg', 0, '<p>Maintenance MessageMaintenance Message</p>', '-1', 4, 1, 1, 'You need to permission from admin to access this panel', '1942636', 1, 'e58380d6ebb048e6feb4', '24a208922bc018ef9b37', 'ap2', 'xnwbyhhhyuycs6ckslp9v0qlylzwflps', 'dw8wwhnqcaiajhpk93lsfrms', 1, 1, 'AIzaSyBiPZPJu6xLOd5lE8H_tjCI7Ufa7Pas0YM', 'imagen-4.0-generate', 'Gemini 2.5 Pro', NULL, '2024-12-09 11:41:33');

-- --------------------------------------------------------

--
-- Table structure for table `shipping_charges`
--

CREATE TABLE `shipping_charges` (
  `id` bigint UNSIGNED NOT NULL,
  `language_id` bigint DEFAULT NULL,
  `unique_id` varbinary(255) DEFAULT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `charge` decimal(10,2) NOT NULL DEFAULT '0.00',
  `serial_number` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shipping_charges`
--

INSERT INTO `shipping_charges` (`id`, `language_id`, `unique_id`, `title`, `text`, `charge`, `serial_number`, `created_at`, `updated_at`) VALUES
(29, 6, 0x36396236633562653935386639, 'Inside Dhaka City', 'Delivery charge for locations inside Dhaka city.', '70.00', 1, '2026-03-15 08:44:14', '2026-03-15 08:44:14'),
(30, 7, 0x36396236633562653935386639, 'ঢাকার ভিতরে ডেলিভারি', 'ঢাকা শহরের ভিতরে ডেলিভারির জন্য প্রযোজ্য চার্জ।', '70.00', 1, '2026-03-15 08:44:14', '2026-03-15 08:57:06'),
(31, 6, 0x36396236633865643062393863, 'Outside Dhaka City', 'Delivery charge for locations outside Dhaka city.', '120.00', 2, '2026-03-15 08:57:49', '2026-03-15 08:57:49'),
(32, 7, 0x36396236633865643062393863, 'ঢাকার বাইরে ডেলিভারি', 'ঢাকা শহরের বাইরে ডেলিভারির জন্য প্রযোজ্য চার্জ।', '120.00', 2, '2026-03-15 08:57:49', '2026-03-15 08:57:49');

-- --------------------------------------------------------

--
-- Table structure for table `slider_images`
--

CREATE TABLE `slider_images` (
  `id` bigint UNSIGNED NOT NULL,
  `item_id` bigint DEFAULT NULL,
  `item_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `slider_images`
--

INSERT INTO `slider_images` (`id`, `item_id`, `item_type`, `image`, `created_at`, `updated_at`) VALUES
(180, 50, 'product', '69a1cea659684.png', '2026-02-27 11:04:38', '2026-02-27 11:05:23'),
(181, 50, 'product', '69a1cea661c30.png', '2026-02-27 11:04:38', '2026-02-27 11:05:23'),
(182, 50, 'product', '69a1cea695fdb.png', '2026-02-27 11:04:38', '2026-02-27 11:05:23'),
(183, 50, 'product', '69a1cea6a7299.png', '2026-02-27 11:04:38', '2026-02-27 11:05:23'),
(184, 51, 'product', '69a1cfd1d2444.png', '2026-02-27 11:09:37', '2026-02-27 11:09:41'),
(185, 51, 'product', '69a1cfd1d7c98.png', '2026-02-27 11:09:37', '2026-02-27 11:09:41'),
(186, 51, 'product', '69a1cfd227690.png', '2026-02-27 11:09:38', '2026-02-27 11:09:41'),
(187, 51, 'product', '69a1cfd22f214.png', '2026-02-27 11:09:38', '2026-02-27 11:09:41'),
(188, 52, 'product', '69a1d075d5973.png', '2026-02-27 11:12:21', '2026-02-27 11:14:14'),
(189, 52, 'product', '69a1d075d67bc.png', '2026-02-27 11:12:21', '2026-02-27 11:14:14'),
(190, 52, 'product', '69a1d0761d3e3.png', '2026-02-27 11:12:22', '2026-02-27 11:14:14'),
(191, 52, 'product', '69a1d07620833.png', '2026-02-27 11:12:22', '2026-02-27 11:14:14'),
(192, 53, 'product', '69a1d1b76ceb3.png', '2026-02-27 11:17:43', '2026-02-27 11:17:48'),
(193, 53, 'product', '69a1d1b770ee0.png', '2026-02-27 11:17:43', '2026-02-27 11:17:48'),
(194, 53, 'product', '69a1d1b7b18a3.png', '2026-02-27 11:17:43', '2026-02-27 11:17:48'),
(195, 54, 'product', '69ac4d45dd512.png', '2026-03-07 10:07:33', '2026-03-07 10:19:17'),
(196, 54, 'product', '69ac4d462577e.png', '2026-03-07 10:07:34', '2026-03-07 10:19:17'),
(197, 54, 'product', '69ac4d46ab3f1.png', '2026-03-07 10:07:34', '2026-03-07 10:19:17');

-- --------------------------------------------------------

--
-- Table structure for table `tables`
--

CREATE TABLE `tables` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint NOT NULL,
  `language_id` bigint NOT NULL,
  `table_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `capacity` int NOT NULL DEFAULT '1',
  `status` enum('available','occupied','reserved','cleaning','unavailable') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `qr_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `serial_number` int NOT NULL DEFAULT '1',
  `color` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#000000',
  `size` int NOT NULL DEFAULT '300',
  `margin` int NOT NULL DEFAULT '1',
  `style` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'square',
  `eye_style` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'square',
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default',
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_size` int NOT NULL DEFAULT '10',
  `image_x` int NOT NULL DEFAULT '50',
  `image_y` int NOT NULL DEFAULT '50',
  `text` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text_color` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#000000',
  `text_size` int NOT NULL DEFAULT '5',
  `text_x` int NOT NULL DEFAULT '50',
  `text_y` int NOT NULL DEFAULT '50',
  `qr_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tables`
--

INSERT INTO `tables` (`id`, `user_id`, `language_id`, `table_number`, `capacity`, `status`, `qr_code`, `serial_number`, `color`, `size`, `margin`, `style`, `eye_style`, `type`, `image`, `image_size`, `image_x`, `image_y`, `text`, `text_color`, `text_size`, `text_x`, `text_y`, `qr_image`, `created_at`, `updated_at`) VALUES
(1, 59, 5, 'Table 1', 5, 'available', NULL, 1, '#000000', 289, 1, 'square', 'circle', 'default', 'table_1_1764177819.png', 10, 18, 50, 'Apple', '#000000', 9, 35, 50, 'qr_1_1764348704.png', '2025-11-26 10:32:43', '2025-11-28 10:51:44'),
(2, 59, 5, 'Table 2', 2, 'available', NULL, 2, '#000000', 282, 1, 'square', 'square', 'default', NULL, 10, 50, 50, NULL, '#000000', 5, 50, 50, 'qr_2_1764350499.png', '2025-11-28 10:53:17', '2025-11-28 11:21:39'),
(3, 59, 5, 'Table 3', 5, 'occupied', NULL, 3, '#000000', 277, 1, 'square', 'square', 'default', NULL, 10, 50, 50, NULL, '#000000', 5, 50, 50, 'qr_3_1764350491.png', '2025-11-28 10:53:38', '2025-11-28 11:21:31'),
(4, 59, 5, 'Table 4', 10, 'unavailable', NULL, 4, '#000000', 279, 1, 'square', 'square', 'default', NULL, 10, 50, 50, NULL, '#000000', 5, 50, 50, 'qr_4_1764350487.png', '2025-11-28 10:53:50', '2025-11-28 11:21:27'),
(5, 59, 5, 'Table 5', 4, 'cleaning', NULL, 5, '#000000', 273, 1, 'square', 'square', 'default', NULL, 10, 50, 50, NULL, '#000000', 5, 50, 50, 'qr_5_1764350482.png', '2025-11-28 10:54:05', '2025-11-28 11:21:22'),
(6, 59, 5, 'Table 6', 3, 'reserved', NULL, 6, '#000000', 275, 1, 'square', 'square', 'default', NULL, 10, 50, 50, NULL, '#000000', 5, 50, 50, 'qr_6_1764350244.png', '2025-11-28 10:54:18', '2025-11-28 11:17:24');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `transaction_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pre_balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `actual_total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `after_balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `currency_symbol` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_symbol_position` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_method` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `transaction_id`, `pre_balance`, `actual_total`, `after_balance`, `currency_symbol`, `currency_symbol_position`, `payment_status`, `payment_method`, `transaction_type`, `created_at`, `updated_at`) VALUES
(1, 'eiZegNVj7z', '0.00', '12.00', '12.00', '$', 'left', 'completed', 'Cash', 'product_purchase', '2025-01-24 03:41:49', '2025-01-24 03:41:49'),
(2, 'vkHJu3OZEW', '12.00', '141.00', '153.00', '$', 'left', 'completed', 'Cash', 'product_purchase', '2025-01-24 03:44:07', '2025-01-24 03:44:07'),
(3, 'c1uKwQWHxB', '153.00', '12.00', '165.00', '$', 'left', 'completed', 'Cash', 'product_purchase', '2025-01-24 03:45:08', '2025-01-24 03:45:08'),
(4, 'iBnbmQKprE', '165.00', '152.00', '317.00', '$', 'left', 'completed', 'Cash', 'product_purchase', '2025-01-24 07:22:36', '2025-01-24 07:22:36'),
(5, 'HR1wy13DzT', '317.00', '4549.00', '4866.00', '$', 'left', 'completed', 'Cash', 'product_purchase', '2025-01-24 07:22:50', '2025-01-24 07:22:50'),
(6, 'ipq4Xw6fIz', '4866.00', '152.00', '5018.00', '$', 'left', 'completed', 'Cash', 'product_purchase', '2025-01-24 07:23:05', '2025-01-24 07:23:05'),
(7, 'f5Hj32QQG4', '5018.00', '12.00', '5030.00', '$', 'left', 'completed', 'Cash', 'product_purchase', '2025-01-24 07:23:15', '2025-01-24 07:23:15'),
(8, 'o2RLvQwJSt', '5030.00', '12.00', '5042.00', '$', 'left', 'completed', 'Cash', 'product_purchase', '2025-01-24 07:25:01', '2025-01-24 07:25:01'),
(9, 't14gle3FgS', '5042.00', '399.98', '5441.98', '$', 'left', 'completed', 'Cash', 'product_purchase', '2025-01-27 12:02:51', '2025-01-27 12:02:51'),
(10, 'ubh7kRpSC8', '5441.98', '299.97', '5741.95', '$', 'left', 'completed', 'Cash', 'product_purchase', '2025-01-27 12:15:39', '2025-01-27 12:15:39'),
(11, 'AMD3D5Z0', '5741.95', '99.99', '5841.94', '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-01-27 12:56:07', '2025-01-27 12:56:07'),
(12, 'QYZ8AAOW', '5841.94', '399.98', '6241.92', '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-01-27 13:03:38', '2025-01-27 13:03:38'),
(13, '0PIHS2R2', '6241.92', '299.99', '6541.91', '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-01-27 13:05:11', '2025-01-27 13:05:11'),
(14, '9PJ5N8ZQ', '6541.91', '99.99', '6641.90', '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-01-27 13:06:13', '2025-01-27 13:06:13'),
(15, '45X32O2B', '6641.90', '79.00', '6720.90', '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-01-31 01:01:07', '2025-01-31 01:01:07'),
(16, 'TDPPCEB7', '6720.90', '319.20', '7040.10', '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-02 11:05:48', '2025-02-02 11:05:48'),
(17, 'OXPSJN1G', '7040.10', '358.99', '7399.09', '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-02 12:16:11', '2025-02-02 12:16:11'),
(18, 'A4X2IHZO', '7399.09', '259.00', '7658.09', '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 02:48:48', '2025-02-14 02:48:48'),
(19, 'N6N8XA4V', '7658.09', '650.98', '8309.07', '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 07:56:29', '2025-02-14 07:56:29'),
(20, 'V77CNFTM', '8309.07', '673.08', '8982.15', '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 07:58:50', '2025-02-14 07:58:50'),
(21, 'ISU9M8Q9', '8982.15', '658.98', '9641.13', '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 08:34:37', '2025-02-14 08:34:37'),
(22, 'KNASWBSR', '9641.13', '299.99', '9941.12', '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 09:06:59', '2025-02-14 09:06:59'),
(23, 'XTNSU6DO', '9941.12', '558.99', '10500.11', '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 09:20:24', '2025-02-14 09:20:24'),
(24, '4260IO95', '10500.11', '299.99', '10800.10', '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 09:22:26', '2025-02-14 09:22:26'),
(25, 'PDFLJO3D', '10800.10', '299.99', '11100.09', '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 09:26:52', '2025-02-14 09:26:52'),
(26, '7KSMGOU1', '11100.09', '251.00', '11351.09', '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 09:30:24', '2025-02-14 09:30:24'),
(27, 'TOBFESWA', '11351.09', '299.99', '11651.08', '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 09:33:28', '2025-02-14 09:33:28'),
(28, 'LMVQS0TO', '11651.08', '299.99', '11951.07', '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 09:36:34', '2025-02-14 09:36:34'),
(29, '0Q9G2QMA', '11951.07', '299.99', '12251.06', '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 09:44:03', '2025-02-14 09:44:03'),
(30, 'M6N9SXL8', '12251.06', '299.99', '12551.05', '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 09:46:02', '2025-02-14 09:46:02'),
(31, 'E60O3XHO', '12551.05', '299.99', '12851.04', '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 09:50:34', '2025-02-14 09:50:34'),
(32, 'C1288N59', '12851.04', '299.99', '13151.03', '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 09:56:12', '2025-02-14 09:56:12'),
(33, '8X4FD2J2', '13151.03', '299.99', '13451.02', '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 09:58:46', '2025-02-14 09:58:46'),
(34, '6VYZKETT', '13451.02', '299.99', '13751.01', '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 10:00:07', '2025-02-14 10:00:07'),
(35, 'AFG34V7O', '13751.01', '299.99', '14051.00', '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 10:04:08', '2025-02-14 10:04:08'),
(36, 'Y0WIKIFC', '14051.00', '299.99', '14350.99', '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 10:06:04', '2025-02-14 10:06:04'),
(37, '4EUQ9CVL', '14350.99', '299.99', '14650.98', '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 11:19:07', '2025-02-14 11:19:07'),
(38, '2K4M5CCZ', '14650.98', '299.99', '14950.97', '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 11:30:10', '2025-02-14 11:30:10'),
(39, '1AYCOS9H', '14950.97', '299.99', '15250.96', '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 11:37:11', '2025-02-14 11:37:11'),
(40, '9AUPZOK1', '15250.96', '299.99', '15550.95', '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 11:47:05', '2025-02-14 11:47:05'),
(41, 'O0KX6I0D', '15550.95', '300.00', '15850.95', '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 11:55:54', '2025-02-14 11:55:54'),
(42, 'JNAGIML7', '15850.95', '559.00', '16409.95', '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 11:57:25', '2025-02-14 11:57:25'),
(43, 'G7QN0BGL', '16409.95', '400.00', '16809.95', '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 12:01:59', '2025-02-14 12:01:59'),
(44, 'B6BISCZL', '16809.95', '300.00', '17109.95', '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 12:02:00', '2025-02-14 12:02:00'),
(45, 'U9Q2PAMQ', '17109.95', '300.00', '17409.95', '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 12:07:38', '2025-02-14 12:07:38'),
(46, 'PHJ116YG', '17409.95', '300.00', '17709.95', '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 12:16:29', '2025-02-14 12:16:29'),
(47, '2EKVK9TQ', '17709.95', '300.00', '18009.95', '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 12:22:42', '2025-02-14 12:22:42'),
(48, 'IZLTFVA2', '18009.95', '559.00', '18568.95', '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 12:30:01', '2025-02-14 12:30:01'),
(49, 'Y8FACDZL', '18568.95', '300.00', '18868.95', '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 12:33:04', '2025-02-14 12:33:04'),
(50, 'TQOPGCAV', '18868.95', '400.00', '19268.95', '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 12:34:17', '2025-02-14 12:34:17'),
(51, '2CMNMEC3', '19268.95', '300.00', '19568.95', '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-15 01:02:56', '2025-02-15 01:02:56'),
(52, '2S8LHS63', '19568.95', '300.00', '19868.95', '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-15 01:03:00', '2025-02-15 01:03:00'),
(53, 'A41V83DY', '19868.95', '300.00', '20168.95', '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-15 01:05:04', '2025-02-15 01:05:04'),
(54, 'N8TAU16P', '20168.95', '100.00', '20268.95', '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-15 01:06:10', '2025-02-15 01:06:10'),
(55, 'TDNEMYYS', '20268.95', '300.00', '20568.95', '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-15 01:06:51', '2025-02-15 01:06:51'),
(56, 'AHLJNGV2', '20568.95', '300.00', '20868.95', '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-15 01:07:00', '2025-02-15 01:07:00'),
(57, '2TON346J', '20868.95', '300.00', '21168.95', '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-15 03:48:54', '2025-02-15 03:48:54'),
(58, '6AKW8LR6', '21168.95', '300.00', '21468.95', '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-15 03:50:50', '2025-02-15 03:50:50'),
(59, '5OVCYHMM', '21468.95', '300.00', '21768.95', '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-15 03:53:45', '2025-02-15 03:53:45'),
(60, 'A9UEWQAG', '21768.95', '300.00', '22068.95', '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-15 03:54:52', '2025-02-15 03:54:52'),
(61, 'ESLBDTWW', '22068.95', '100.00', '22168.95', '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-15 03:55:17', '2025-02-15 03:55:17'),
(62, 'A76RQHZK', '22168.95', '300.00', '22468.95', '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-15 03:55:29', '2025-02-15 03:55:29'),
(63, 'NHTL472M', '22468.95', '120.00', '22588.95', '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2026-02-05 11:35:44', '2026-02-05 11:35:44');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `qr_menu_enabled` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `image`, `status`, `phone`, `country`, `city`, `state`, `zip_code`, `address`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `qr_menu_enabled`) VALUES
(59, 'Masud Rana', 'vysidexud', 'dehat@mailinator.com', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 06:24:12', '$2y$12$nMQkOTNbb3EwF3qVgd6pY.XrvtjMu.UPPhlnwkM6idUzOC7seDixK', 'ahVDR4CCllzUT9QMpI2srGHDzdaYl1KVzVnSzLqn26YXlgPxJU1nPvw2xzRI', '2025-11-21 06:24:12', '2026-03-29 11:06:50', 1);

-- --------------------------------------------------------

--
-- Table structure for table `variant_serials`
--

CREATE TABLE `variant_serials` (
  `id` bigint UNSIGNED NOT NULL,
  `variant_id` bigint UNSIGNED NOT NULL,
  `serial` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'in_stock',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `variant_serial_batches`
--

CREATE TABLE `variant_serial_batches` (
  `id` bigint UNSIGNED NOT NULL,
  `variant_id` bigint UNSIGNED NOT NULL,
  `batch_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `serial_start` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `serial_end` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` int NOT NULL,
  `sold_qty` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `variant_serial_batches`
--

INSERT INTO `variant_serial_batches` (`id`, `variant_id`, `batch_no`, `serial_start`, `serial_end`, `qty`, `sold_qty`, `created_at`, `updated_at`) VALUES
(7, 112, 'INIT-112-20260227170941', '1', '10', 10, 0, '2026-02-27 11:09:41', '2026-02-27 11:09:41'),
(8, 113, 'INIT-113-20260227170941', '1', '10', 10, 0, '2026-02-27 11:09:41', '2026-02-27 11:09:41'),
(9, 114, 'INIT-114-20260227170941', '1', '10', 10, 0, '2026-02-27 11:09:41', '2026-02-27 11:09:41'),
(10, 115, 'INIT-115-20260227170941', '1', '10', 10, 0, '2026-02-27 11:09:41', '2026-02-27 11:09:41'),
(11, 116, 'INIT-116-20260227171414', '1', '10', 10, 0, '2026-02-27 11:14:14', '2026-02-27 11:14:14'),
(12, 117, 'INIT-117-20260227171414', '1', '10', 10, 0, '2026-02-27 11:14:14', '2026-02-27 11:14:14'),
(13, 118, 'INIT-118-20260227171414', '1', '10', 10, 0, '2026-02-27 11:14:14', '2026-02-27 11:14:14'),
(14, 119, 'INIT-119-20260227171414', '1', '10', 10, 0, '2026-02-27 11:14:14', '2026-02-27 11:14:14'),
(15, 120, 'INIT-120-20260227171748', '1', '10', 10, 0, '2026-02-27 11:17:48', '2026-02-27 11:17:48'),
(16, 121, 'INIT-121-20260227171748', '1', '10', 10, 0, '2026-02-27 11:17:48', '2026-02-27 11:17:48'),
(17, 122, 'INIT-122-20260227171748', '1', '10', 10, 0, '2026-02-27 11:17:48', '2026-02-27 11:17:48'),
(18, 123, 'INIT-123-20260227171748', '1', '10', 10, 0, '2026-02-27 11:17:48', '2026-02-27 11:17:48'),
(19, 124, 'INIT-124-20260227171748', '1', '10', 10, 0, '2026-02-27 11:17:48', '2026-02-27 11:17:48'),
(20, 125, 'INIT-125-20260227171748', '1', '10', 10, 0, '2026-02-27 11:17:48', '2026-02-27 11:17:48'),
(21, 126, 'INIT-126-20260227171748', '1', '10', 10, 0, '2026-02-27 11:17:48', '2026-02-27 11:17:48'),
(22, 127, 'INIT-127-20260227171748', '1', '10', 10, 0, '2026-02-27 11:17:48', '2026-02-27 11:17:48'),
(23, 128, 'INIT-128-20260227171748', '1', '10', 10, 0, '2026-02-27 11:17:48', '2026-02-27 11:17:48');

-- --------------------------------------------------------

--
-- Table structure for table `variant_sold_serials`
--

CREATE TABLE `variant_sold_serials` (
  `id` bigint UNSIGNED NOT NULL,
  `order_item_id` bigint UNSIGNED NOT NULL,
  `variant_id` bigint UNSIGNED NOT NULL,
  `serial` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sold',
  `returned_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `id` bigint UNSIGNED NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint NOT NULL DEFAULT '0',
  `is_verified` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`id`, `username`, `email`, `image`, `phone`, `zip_code`, `password`, `email_verified_at`, `is_active`, `is_verified`, `created_at`, `updated_at`) VALUES
(30, 'dyzeloruvo', 'xuzigun@mailinator.com', NULL, NULL, NULL, '$2y$12$cxlJC7l5ASO2nt5zCOmY2O7q/azp7SJggcCgWmRLhnFpebkeY2jxW', '2025-11-09 09:27:07', 1, 1, '2024-12-11 12:13:08', '2025-11-09 09:27:07'),
(32, 'miwatahibe', 'pykoquh@mailinator.com', NULL, NULL, NULL, '$2y$12$8Aak9a77GQF6otyBcAqCxuy8Q8TkUPsFyXn4chejM5It6pPwZLJFa', '2025-02-14 00:04:38', 1, 1, '2024-12-11 12:13:35', '2025-02-14 00:04:39');

-- --------------------------------------------------------

--
-- Table structure for table `vendor_infos`
--

CREATE TABLE `vendor_infos` (
  `id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint NOT NULL,
  `language_id` bigint NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendor_infos`
--

INSERT INTO `vendor_infos` (`id`, `vendor_id`, `language_id`, `name`, `address`, `country`, `city`, `state`, `created_at`, `updated_at`) VALUES
(58, 30, 2, 'dyzeloruvo', NULL, NULL, NULL, NULL, '2024-12-11 12:13:08', '2024-12-11 12:13:08'),
(61, 32, 1, 'miwatahibe', NULL, NULL, NULL, NULL, '2024-12-11 12:13:35', '2024-12-11 12:13:35'),
(62, 32, 2, 'miwatahibe', NULL, NULL, NULL, NULL, '2024-12-11 12:13:35', '2024-12-11 12:13:35');

-- --------------------------------------------------------

--
-- Table structure for table `wishlists`
--

CREATE TABLE `wishlists` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wishlists`
--

INSERT INTO `wishlists` (`id`, `user_id`, `product_id`, `created_at`, `updated_at`) VALUES
(8, 59, 52, '2026-03-30 02:55:52', '2026-03-30 02:55:52'),
(9, 59, 51, '2026-03-30 02:55:53', '2026-03-30 02:55:53');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blog_contents`
--
ALTER TABLE `blog_contents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carts_product_id_foreign` (`product_id`),
  ADD KEY `carts_session_id_index` (`session_id`),
  ADD KEY `carts_user_id_index` (`user_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `footers`
--
ALTER TABLE `footers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `home_freshness_items`
--
ALTER TABLE `home_freshness_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `home_freshness_items_language_id_index` (`language_id`),
  ADD KEY `home_freshness_items_position_index` (`position`);

--
-- Indexes for table `home_section_settings`
--
ALTER TABLE `home_section_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `home_section_settings_key_unique` (`featured_product_title`),
  ADD KEY `home_section_settings_section_index` (`category_title`);

--
-- Indexes for table `home_sliders`
--
ALTER TABLE `home_sliders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mail_templates`
--
ALTER TABLE `mail_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `memberships`
--
ALTER TABLE `memberships`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu_builders`
--
ALTER TABLE `menu_builders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_variant_id_index` (`variant_id`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `page_headings`
--
ALTER TABLE `page_headings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payment_gateways`
--
ALTER TABLE `payment_gateways`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_contents`
--
ALTER TABLE `product_contents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_coupons`
--
ALTER TABLE `product_coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_coupons_code_unique` (`code`);

--
-- Indexes for table `product_options`
--
ALTER TABLE `product_options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_option_values`
--
ALTER TABLE `product_option_values`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_option_values_product_option_id_value_index` (`product_option_id`,`value`);

--
-- Indexes for table `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_settings`
--
ALTER TABLE `product_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_variants_product_id_sku_unique` (`product_id`,`sku`),
  ADD KEY `product_variants_product_id_status_index` (`product_id`,`status`);

--
-- Indexes for table `product_variant_values`
--
ALTER TABLE `product_variant_values`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_variant_values_variant_id_option_value_id_unique` (`variant_id`,`option_value_id`),
  ADD KEY `product_variant_values_option_value_id_index` (`option_value_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shipping_charges`
--
ALTER TABLE `shipping_charges`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `slider_images`
--
ALTER TABLE `slider_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tables`
--
ALTER TABLE `tables`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tables_table_number_unique` (`table_number`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `variant_serials`
--
ALTER TABLE `variant_serials`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `variant_serials_variant_id_serial_unique` (`variant_id`,`serial`),
  ADD KEY `variant_serials_variant_id_status_index` (`variant_id`,`status`);

--
-- Indexes for table `variant_serial_batches`
--
ALTER TABLE `variant_serial_batches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `variant_serial_batches_variant_id_batch_no_unique` (`variant_id`,`batch_no`),
  ADD KEY `variant_serial_batches_variant_id_created_at_index` (`variant_id`,`created_at`);

--
-- Indexes for table `variant_sold_serials`
--
ALTER TABLE `variant_sold_serials`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `variant_sold_serials_variant_id_serial_unique` (`variant_id`,`serial`),
  ADD KEY `variant_sold_serials_variant_id_order_item_id_index` (`variant_id`,`order_item_id`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendor_infos`
--
ALTER TABLE `vendor_infos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wishlists_product_id_foreign` (`product_id`),
  ADD KEY `wishlists_user_id_foreign` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `blogs`
--
ALTER TABLE `blogs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `blog_contents`
--
ALTER TABLE `blog_contents`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `footers`
--
ALTER TABLE `footers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `home_freshness_items`
--
ALTER TABLE `home_freshness_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `home_section_settings`
--
ALTER TABLE `home_section_settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `home_sliders`
--
ALTER TABLE `home_sliders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `mail_templates`
--
ALTER TABLE `mail_templates`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `memberships`
--
ALTER TABLE `memberships`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `menu_builders`
--
ALTER TABLE `menu_builders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=164;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=185;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `page_headings`
--
ALTER TABLE `page_headings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_gateways`
--
ALTER TABLE `payment_gateways`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `product_contents`
--
ALTER TABLE `product_contents`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `product_coupons`
--
ALTER TABLE `product_coupons`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `product_options`
--
ALTER TABLE `product_options`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `product_option_values`
--
ALTER TABLE `product_option_values`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=140;

--
-- AUTO_INCREMENT for table `product_reviews`
--
ALTER TABLE `product_reviews`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `product_settings`
--
ALTER TABLE `product_settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=131;

--
-- AUTO_INCREMENT for table `product_variant_values`
--
ALTER TABLE `product_variant_values`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=202;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `shipping_charges`
--
ALTER TABLE `shipping_charges`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `slider_images`
--
ALTER TABLE `slider_images`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=198;

--
-- AUTO_INCREMENT for table `tables`
--
ALTER TABLE `tables`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `variant_serials`
--
ALTER TABLE `variant_serials`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `variant_serial_batches`
--
ALTER TABLE `variant_serial_batches`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `variant_sold_serials`
--
ALTER TABLE `variant_sold_serials`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `vendor_infos`
--
ALTER TABLE `vendor_infos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD CONSTRAINT `wishlists_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlists_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
