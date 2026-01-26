-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 28, 2025 at 06:19 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sass-dashboard`
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

--
-- Dumping data for table `blogs`
--

INSERT INTO `blogs` (`id`, `image`, `serial_number`, `status`, `created_at`, `updated_at`) VALUES
(12, '67795c71c33f5.png', 1, 1, '2025-01-04 10:06:09', '2025-10-30 12:18:18');

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
  `customer_keywords` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `name`, `code`, `is_default`, `dashboard_default`, `direction`, `customer_keywords`, `created_at`, `updated_at`) VALUES
(1, 'English', 'en', 1, 1, 'LTR', '{\"Inactive\":\"Inactive\",\"Active\":\"Active\",\"completed\":\"completed\",\"Order Code\":\"Order Code\",\"Customer\":\"Customer\",\"Actions\":\"Actions\",\"Order Status\":\"Order Status\",\"Payment Status\":\"Payment Status\",\"Payment method\":\"Payment method\",\"Monthly Orders\":\"Monthly Orders\",\"Monthly Sale\":\"Monthly Sale\",\"Filter\":\"Filter\",\"Total Earning on\":\"Total Earning on\",\"Total Sales on\":\"Total Sales on\",\"Total Earning\":\"Total Earning\",\"Today\'s Sales\":\"Today\'s Sales\",\"Today\'s Pending\":\"Today\'s Pending\",\"Add or select a customer to include billing details\":\"Add or select a customer to include billing details\",\"Inventory Management\":\"Inventory Management\",\"Select a customer\":\"Select a customer\",\"Add Customer\":\"Add Customer\",\"ADD PRODUCT\":\"ADD PRODUCT\",\"Tax Amount\":\"Tax Amount\",\"Discount Amount\":\"Discount Amount\",\"Shipping Amount\":\"Shipping Amount\",\"Deactive\":\"Deactive\",\"Status\":\"Status\",\"Config Email Setting\":\"Config Email Setting\",\"Common\":\"Common\",\"Edit Admin Keyword\":\"Edit Admin Keyword\",\"Keywords of\":\"Keywords of\",\"Back\":\"Back\",\"Favicon\":\"Favicon\",\"Logo\":\"Logo\",\"Select Currency Position\":\"Select Currency Position\",\"Left\":\"Left\",\"Right\":\"Right\",\"Save Changes\":\"Save Changes\",\"Currency Symbol Position\":\"Currency Symbol Position\",\"Currency Text Position\":\"Currency Text Position\",\"Currency Symbol\":\"Currency Symbol\",\"Currency Text\":\"Currency Text\",\"Currency Rate\":\"Currency Rate\",\"Websit Color\":\"Websit Color\",\"Currency Information\":\"Currency Information\",\"Website Appearance\":\"Website Appearance\",\"Timezone\":\"Timezone\",\"Website Title\":\"Website Title\",\"Website Information\":\"Website Information\",\"Close\":\"Close\",\"Save\":\"Save\",\"NO PRODUCT FOUND\":\"NO PRODUCT FOUND\",\"Enable or disable maintenance mode and configure settings for site updates\":\"Enable or disable maintenance mode and configure settings for site updates\",\"View and update plugin setting\":\"View and update plugin setting\",\"Manage and configure payment gateway settings\":\"Manage and configure payment gateway settings\",\"Email templates using HTML & system variables\":\"Email templates using HTML & system variables\",\"View and update your email settings and email templates\":\"View and update your email settings and email templates\",\"View and update your general settings and activate license\":\"View and update your general settings and activate license\",\"Page Heading\":\"Page Heading\",\"Payment Gateway\":\"Payment Gateway\",\"Plugins\":\"Plugins\",\"Maintenance Mode\":\"Maintenance Mode\",\"Email Templates\":\"Email Templates\",\"Registered Users\":\"Registered Users\",\"Language\":\"Language\",\"User Management\":\"User Management\",\"Language Management\":\"Language Management\",\"Packages\":\"Packages\",\"Package Management\":\"Package Management\",\"All Admins\":\"All Admins\",\"Role & Permission\":\"Role & Permission\",\"Role Management\":\"Role Management\",\"Vendors\":\"Vendors\",\"Vendor Management\":\"Vendor Management\",\"Posts\":\"Posts\",\"Blog Management\":\"Blog Management\",\"Products\":\"Products\",\"Coupons\":\"Coupons\",\"Categories\":\"Categories\",\"Product Management\":\"Product Management\",\"Reports\":\"Reports\",\"All Sales\":\"All Sales\",\"Sales Management\":\"Sales Management\",\"POS\":\"POS\",\"Email Settings\":\"Email Settings\",\"General Settings\":\"General Settings\",\"Dashboard\":\"Dashboard\",\"Settings\":\"Settings\",\"Rejected Orders\":\"Rejected Orders\",\"Pending Orders\":\"Pending Orders\",\"Completed Orders\":\"Completed Orders\",\"Total Sales\":\"Total Sales\",\"Today\'s Earning\":\"Today\'s Earning\"}\n', '2024-12-05 09:48:56', '2025-11-25 10:44:06'),
(3, 'عربي', 'ar', 0, 0, 'RTL', '{\"Inactive\":\"Inactive\",\"Active\":\"Active\",\"completed\":\"completed\",\"Order Code\":\"Order Code\",\"Customer\":\"Customer\",\"Actions\":\"Actions\",\"Order Status\":\"Order Status\",\"Payment Status\":\"Payment Status\",\"Payment method\":\"Payment method\",\"Monthly Orders\":\"Monthly Orders\",\"Monthly Sale\":\"Monthly Sale\",\"Filter\":\"Filter\",\"Total Earning on\":\"Total Earning on\",\"Total Sales on\":\"Total Sales on\",\"Total Earning\":\"Total Earning\",\"Today\'s Sales\":\"Today\'s Sales\",\"Today\'s Pending\":\"Today\'s Pending\",\"Add or select a customer to include billing details\":\"Add or select a customer to include billing details\",\"Inventory Management\":\"Inventory Management\",\"Select a customer\":\"Select a customer\",\"Add Customer\":\"Add Customer\",\"ADD PRODUCT\":\"ADD PRODUCT\",\"Tax Amount\":\"Tax Amount\",\"Discount Amount\":\"Discount Amount\",\"Shipping Amount\":\"Shipping Amount\",\"Deactive\":\"Deactive\",\"Status\":\"Status\",\"Config Email Setting\":\"Config Email Setting\",\"Common\":\"Common\",\"Edit Admin Keyword\":\"Edit Admin Keyword\",\"Keywords of\":\"Keywords of\",\"Back\":\"Back\",\"Favicon\":\"Favicon\",\"Logo\":\"Logo\",\"Select Currency Position\":\"Select Currency Position\",\"Left\":\"Left\",\"Right\":\"Right\",\"Save Changes\":\"Save Changes\",\"Currency Symbol Position\":\"Currency Symbol Position\",\"Currency Text Position\":\"Currency Text Position\",\"Currency Symbol\":\"Currency Symbol\",\"Currency Text\":\"Currency Text\",\"Currency Rate\":\"Currency Rate\",\"Websit Color\":\"Websit Color\",\"Currency Information\":\"Currency Information\",\"Website Appearance\":\"Website Appearance\",\"Timezone\":\"Timezone\",\"Website Title\":\"Website Title\",\"Website Information\":\"Website Information\",\"Close\":\"Close\",\"Save\":\"Save\",\"NO PRODUCT FOUND\":\"NO PRODUCT FOUND\",\"Enable or disable maintenance mode and configure settings for site updates\":\"Enable or disable maintenance mode and configure settings for site updates\",\"View and update plugin setting\":\"View and update plugin setting\",\"Manage and configure payment gateway settings\":\"Manage and configure payment gateway settings\",\"Email templates using HTML & system variables\":\"Email templates using HTML & system variables\",\"View and update your email settings and email templates\":\"View and update your email settings and email templates\",\"View and update your general settings and activate license\":\"View and update your general settings and activate license\",\"Page Heading\":\"Page Heading\",\"Payment Gateway\":\"Payment Gateway\",\"Plugins\":\"Plugins\",\"Maintenance Mode\":\"Maintenance Mode\",\"Email Templates\":\"Email Templates\",\"Registered Users\":\"Registered Users\",\"Language\":\"Language\",\"User Management\":\"User Management\",\"Language Management\":\"Language Management\",\"Packages\":\"Packages\",\"Package Management\":\"Package Management\",\"All Admins\":\"All Admins\",\"Role & Permission\":\"Role & Permission\",\"Role Management\":\"Role Management\",\"Vendors\":\"Vendors\",\"Vendor Management\":\"Vendor Management\",\"Posts\":\"Posts\",\"Blog Management\":\"Blog Management\",\"Products\":\"Products\",\"Coupons\":\"Coupons\",\"Categories\":\"Categories\",\"Product Management\":\"Product Management\",\"Reports\":\"Reports\",\"All Sales\":\"All Sales\",\"Sales Management\":\"Sales Management\",\"POS\":\"POS\",\"Email Settings\":\"Email Settings\",\"General Settings\":\"General Settings\",\"Dashboard\":\"Dashboard\",\"Settings\":\"Settings\",\"Rejected Orders\":\"Rejected Orders\",\"Pending Orders\":\"Pending Orders\",\"Completed Orders\":\"Completed Orders\",\"Total Sales\":\"Total Sales\",\"Today\'s Earning\":\"Today\'s Earning\"}\n', '2025-01-23 08:09:21', '2025-11-21 07:03:41');

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
  `currency_text_position` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_symbol_position` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `start_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expire_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `menu` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menu_builders`
--

INSERT INTO `menu_builders` (`id`, `language_id`, `menu`, `created_at`, `updated_at`) VALUES
(3, 1, '[{\"title\":\"Home\",\"url\":\"\\/\",\"target\":\"_self\",\"type\":null},{\"title\":\"Pricing\",\"url\":\"\\/pricing\",\"target\":\"_self\",\"type\":null},{\"title\":\"Contact\",\"url\":\"\\/contact\",\"target\":\"_self\",\"type\":null},{\"title\":\"Pages\",\"url\":\"\\/\",\"target\":\"_self\",\"type\":null,\"children\":[{\"title\":\"Blog\",\"url\":\"\\/blog\",\"target\":\"_self\",\"type\":null},{\"title\":\"About\",\"url\":\"\\/about\",\"target\":\"_self\",\"type\":null}]}]', '2025-11-09 13:16:55', '2025-11-10 12:46:54');

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
(53, '2025_11_26_141408_create_tables_table', 28);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint UNSIGNED NOT NULL,
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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_number`, `billing_name`, `billing_email`, `billing_phone`, `billing_address`, `billing_city`, `shipping_address`, `payment_method`, `gateway`, `cart_total`, `pay_amount`, `discount_amount`, `tax`, `shipping_charge`, `invoice_number`, `currency_symbol`, `currency_symbol_position`, `currency_text`, `currency_text_position`, `payment_status`, `order_status`, `receipt`, `delivery_date`, `created_at`, `updated_at`) VALUES
(131, 'JNAGIML7', 'Vielka Gomez', 'masud.cst0@gmail.com', '+1 (501) 993-6871', 'Hic quia et enim eos', NULL, 'unknown', 'Cash Payment', 'Offline', 558.99, 559, 0.00, 0.00, NULL, 'BOs11739555845.pdf', '$', 'left', 'USD', 'left', 'completed', 'completed', NULL, NULL, '2025-02-14 11:57:25', '2025-02-14 11:57:25'),
(132, 'G7QN0BGL', 'Pamela Kaufman', 'masud.cst0@gmail.com', '+1 (226) 491-1486', 'Officia modi sint re', NULL, 'unknown', 'Cash Payment', 'Offline', 399.98, 400, 0.00, 0.00, NULL, 'BWY61739556119.pdf', '$', 'left', 'USD', 'left', 'completed', 'completed', NULL, NULL, '2025-02-14 12:00:01', '2025-02-14 12:01:59'),
(133, 'B6BISCZL', 'Ezra Tanner', 'masud.cst0@gmail.com', '+1 (254) 644-7323', 'Voluptatem blanditi', NULL, 'unknown', 'Cash Payment', 'Offline', 299.99, 300, 0.00, 0.00, NULL, 'aQ311739556120.pdf', '$', 'left', 'USD', 'left', 'completed', 'completed', NULL, NULL, '2025-02-14 12:00:56', '2025-02-14 12:02:00'),
(134, 'U9Q2PAMQ', 'Debra Becker', 'masud.cst0@gmail.com', '+1 (232) 318-6752', 'Non sed nemo ut eius', NULL, 'unknown', 'Cash Payment', 'Offline', 299.99, 300, 0.00, 0.00, NULL, 'mEPm1739556458.pdf', '$', 'left', 'USD', 'left', 'completed', 'completed', NULL, NULL, '2025-02-14 12:07:35', '2025-02-14 12:07:38'),
(135, 'PHJ116YG', 'Clayton Ingram', 'masud.cst0@gmail.com', '+1 (318) 537-2701', 'Magnam in voluptatem', NULL, 'unknown', 'Cash Payment', 'Offline', 299.99, 300, 0.00, 0.00, NULL, 'AVxD1739556989.pdf', '$', 'left', 'USD', 'left', 'completed', 'completed', NULL, NULL, '2025-02-14 12:10:00', '2025-02-14 12:16:29'),
(136, '2EKVK9TQ', 'Shelby Cross', 'masud.cst0@gmail.com', '+1 (521) 164-4707', 'Debitis sint officia', NULL, 'unknown', 'Cash Payment', 'Offline', 299.99, 300, 0.00, 0.00, NULL, 'BTvT1739557362.pdf', '$', 'left', 'USD', 'left', 'completed', 'completed', NULL, NULL, '2025-02-14 12:22:42', '2025-02-14 12:22:43'),
(137, 'IZLTFVA2', 'Nissim Blevins', 'masud.cst0@gmail.com', '+1 (412) 186-8622', 'Dolore esse ex labo', NULL, 'unknown', 'Cash Payment', 'Offline', 558.99, 559, 0.00, 0.00, NULL, 'UIUs1739557801.pdf', '$', 'left', 'USD', 'left', 'completed', 'completed', NULL, NULL, '2025-02-14 12:28:54', '2025-02-14 12:30:01'),
(138, 'Y8FACDZL', 'Zahir Porter', 'masud.cst0@gmail.com', '+1 (909) 988-4108', 'Voluptas recusandae', NULL, 'unknown', 'Cash Payment', 'Offline', 299.99, 300, 0.00, 0.00, NULL, 'cluV1739557909.pdf', '$', 'left', 'USD', 'left', 'completed', 'completed', NULL, NULL, '2025-02-14 12:31:49', '2025-02-14 12:33:03'),
(139, 'TQOPGCAV', 'Buckminster Wise', 'masud.cst0@gmail.com', '+1 (156) 511-5113', 'Anim eius harum dist', NULL, 'unknown', 'Cash Payment', 'Offline', 399.98, 400, 0.00, 0.00, NULL, 'fue41739558057.pdf', '$', 'left', 'USD', 'left', 'completed', 'completed', NULL, NULL, '2025-02-14 12:34:17', '2025-02-14 12:35:26'),
(140, '2CMNMEC3', 'unknown', 'unknown', 'unknown', 'unknown', NULL, 'unknown', 'Cash Payment', 'Offline', 299.99, 300, 0.00, 0.00, NULL, 'bAeB1739602976.pdf', '$', 'left', 'USD', 'left', 'completed', 'completed', NULL, NULL, '2025-02-15 01:00:17', '2025-02-15 01:03:00'),
(141, '2S8LHS63', 'Hanae Chaney', 'syxegunap@mailinator.com', '+1 (341) 367-8332', 'Sapiente velit numqu', NULL, 'unknown', 'Cash Payment', 'Offline', 299.99, 300, 0.00, 0.00, NULL, 'WmSz1739602980.pdf', '$', 'left', 'USD', 'left', 'completed', 'completed', NULL, NULL, '2025-02-15 01:01:40', '2025-02-15 01:03:00'),
(142, 'A41V83DY', 'Hanae Chaney', 'syxegunap@mailinator.com', '+1 (341) 367-8332', 'Sapiente velit numqu', NULL, 'unknown', 'Cash Payment', 'Offline', 299.99, 300, 0.00, 0.00, NULL, 'Osc01739603104.pdf', '$', 'left', 'USD', 'left', 'completed', 'completed', NULL, NULL, '2025-02-15 01:05:03', '2025-02-15 01:05:04'),
(143, 'N8TAU16P', 'Hanae Chaney', 'syxegunap@mailinator.com', '+1 (341) 367-8332', 'Sapiente velit numqu', NULL, 'unknown', 'Cash Payment', 'Offline', 99.99, 100, 0.00, 0.00, NULL, 'gSoI1739603170.pdf', '$', 'left', 'USD', 'left', 'completed', 'completed', NULL, NULL, '2025-02-15 01:06:07', '2025-02-15 01:06:10'),
(144, 'TDNEMYYS', 'Hanae Chaney', 'syxegunap@mailinator.com', '+1 (341) 367-8332', 'Sapiente velit numqu', NULL, 'unknown', 'Cash Payment', 'Offline', 299.99, 300, 0.00, 0.00, NULL, 'oC3v1739603211.pdf', '$', 'left', 'USD', 'left', 'completed', 'completed', NULL, NULL, '2025-02-15 01:06:50', '2025-02-15 01:06:52'),
(145, 'AHLJNGV2', 'unknown', 'unknown', 'unknown', 'unknown', NULL, 'unknown', 'Cash Payment', 'Offline', 299.99, 300, 0.00, 0.00, NULL, 'wTx91739603220.pdf', '$', 'left', 'USD', 'left', 'completed', 'completed', NULL, NULL, '2025-02-15 01:06:59', '2025-02-15 01:07:00'),
(146, '2TON346J', 'unknown', 'unknown', 'unknown', 'unknown', NULL, 'unknown', 'Cash Payment', 'Offline', 299.99, 300, 0.00, 0.00, NULL, '0gp61739612934.pdf', '$', 'left', 'USD', 'left', 'completed', 'completed', NULL, NULL, '2025-02-15 03:48:51', '2025-02-15 03:48:55'),
(147, '6AKW8LR6', 'unknown', 'unknown', 'unknown', 'unknown', NULL, 'unknown', 'Cash Payment', 'Offline', 299.99, 300, 0.00, 0.00, NULL, 'KI1P1739613050.pdf', '$', 'left', 'USD', 'left', 'completed', 'completed', NULL, NULL, '2025-02-15 03:50:50', '2025-02-15 03:50:50'),
(148, '5OVCYHMM', 'unknown', 'unknown', 'unknown', 'unknown', NULL, 'unknown', 'Cash Payment', 'Offline', 299.99, 300, 0.00, 0.00, NULL, 'iGOu1739613225.pdf', '$', 'left', 'USD', 'left', 'completed', 'completed', NULL, NULL, '2025-02-15 03:53:44', '2025-02-15 03:53:46'),
(149, 'A9UEWQAG', 'unknown', 'unknown', 'unknown', 'unknown', NULL, 'unknown', 'Cash Payment', 'Offline', 299.99, 300, 0.00, 0.00, NULL, 'wevC1739613292.pdf', '$', 'left', 'USD', 'left', 'completed', 'completed', NULL, NULL, '2025-02-15 03:54:50', '2025-02-15 03:54:52'),
(150, 'ESLBDTWW', 'unknown', 'unknown', 'unknown', 'unknown', NULL, 'unknown', 'Cash Payment', 'Offline', 99.99, 100, 0.00, 0.00, NULL, '9Frw1739613317.pdf', '$', 'left', 'USD', 'left', 'completed', 'completed', NULL, NULL, '2025-02-15 03:55:16', '2025-02-15 03:55:17'),
(151, 'A76RQHZK', 'unknown', 'unknown', 'unknown', 'unknown', NULL, 'unknown', 'Cash Payment', 'Offline', 299.99, 300, 0.00, 0.00, NULL, 'Oieg1739613329.pdf', '$', 'left', 'USD', 'left', 'completed', 'completed', NULL, NULL, '2025-02-15 03:55:28', '2025-02-15 03:55:29'),
(152, '6KDDMTF2', 'unknown', 'unknown', 'unknown', 'unknown', NULL, 'unknown', 'Cash Payment', 'Offline', 99.99, 100, 0.00, 0.00, NULL, NULL, '$', 'left', 'USD', 'left', 'completed', 'completed', NULL, NULL, '2025-10-30 12:39:06', '2025-10-30 12:39:06');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint DEFAULT NULL,
  `customer_id` bigint DEFAULT NULL,
  `product_id` bigint DEFAULT NULL,
  `product_price` decimal(10,0) DEFAULT NULL,
  `qty` int DEFAULT NULL,
  `variations` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `customer_id`, `product_id`, `product_price`, `qty`, `variations`, `created_at`, `updated_at`) VALUES
(150, 131, NULL, 7, 234, 1, '[{\"product_id\":7,\"variation_id\":85,\"variation_name\":\"Color\",\"option_name\":\"Black\",\"price\":\"20\",\"option_key\":\"1\",\"qty\":1},{\"product_id\":7,\"variation_id\":87,\"variation_name\":\"Size\",\"option_name\":\"M\",\"price\":\"5\",\"option_key\":\"0\",\"qty\":1}]', '2025-02-14 11:57:25', '2025-02-14 11:57:25'),
(151, 131, NULL, 5, 300, 1, '[]', '2025-02-14 11:57:25', '2025-02-14 11:57:25'),
(152, 132, NULL, 5, 300, 1, '[]', '2025-02-14 12:00:01', '2025-02-14 12:00:01'),
(153, 132, NULL, 6, 100, 1, '[]', '2025-02-14 12:00:01', '2025-02-14 12:00:01'),
(154, 133, NULL, 5, 300, 1, '[]', '2025-02-14 12:00:56', '2025-02-14 12:00:56'),
(155, 134, NULL, 5, 300, 1, '[]', '2025-02-14 12:07:35', '2025-02-14 12:07:35'),
(156, 135, NULL, 5, 300, 1, '[]', '2025-02-14 12:10:00', '2025-02-14 12:10:00'),
(157, 136, NULL, 5, 300, 1, '[]', '2025-02-14 12:22:42', '2025-02-14 12:22:42'),
(158, 137, NULL, 7, 234, 1, '[{\"product_id\":7,\"variation_id\":85,\"variation_name\":\"Color\",\"option_name\":\"Black\",\"price\":\"20\",\"option_key\":\"1\",\"qty\":1},{\"product_id\":7,\"variation_id\":87,\"variation_name\":\"Size\",\"option_name\":\"M\",\"price\":\"5\",\"option_key\":\"0\",\"qty\":1}]', '2025-02-14 12:28:54', '2025-02-14 12:28:54'),
(159, 137, NULL, 5, 300, 1, '[]', '2025-02-14 12:28:54', '2025-02-14 12:28:54'),
(160, 138, NULL, 5, 300, 1, '[]', '2025-02-14 12:31:49', '2025-02-14 12:31:49'),
(161, 139, NULL, 5, 300, 1, '[]', '2025-02-14 12:34:17', '2025-02-14 12:34:17'),
(162, 139, NULL, 6, 100, 1, '[]', '2025-02-14 12:34:17', '2025-02-14 12:34:17'),
(163, 140, NULL, 5, 300, 1, '[]', '2025-02-15 01:00:17', '2025-02-15 01:00:17'),
(164, 141, NULL, 5, 300, 1, '[]', '2025-02-15 01:01:40', '2025-02-15 01:01:40'),
(165, 142, NULL, 5, 300, 1, '[]', '2025-02-15 01:05:03', '2025-02-15 01:05:03'),
(166, 143, NULL, 6, 100, 1, '[]', '2025-02-15 01:06:07', '2025-02-15 01:06:07'),
(167, 144, NULL, 5, 300, 1, '[]', '2025-02-15 01:06:50', '2025-02-15 01:06:50'),
(168, 145, NULL, 5, 300, 1, '[]', '2025-02-15 01:06:59', '2025-02-15 01:06:59'),
(169, 146, NULL, 5, 300, 1, '[]', '2025-02-15 03:48:52', '2025-02-15 03:48:52'),
(170, 147, NULL, 5, 300, 1, '[]', '2025-02-15 03:50:50', '2025-02-15 03:50:50'),
(171, 148, NULL, 5, 300, 1, '[]', '2025-02-15 03:53:44', '2025-02-15 03:53:44'),
(172, 149, NULL, 5, 300, 1, '[]', '2025-02-15 03:54:50', '2025-02-15 03:54:50'),
(173, 150, NULL, 6, 100, 1, '[]', '2025-02-15 03:55:16', '2025-02-15 03:55:16'),
(174, 151, NULL, 5, 300, 1, '[]', '2025-02-15 03:55:28', '2025-02-15 03:55:28'),
(175, 152, NULL, 6, 100, 1, '[]', '2025-10-30 12:39:06', '2025-10-30 12:39:06');

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
(13, 'fas fa-star', 'Basic', 'basic', 9.00, 'monthly', NULL, 0, 1, 0, 0, '[\"Custom Domain\"]', NULL, '2025-11-11 10:30:38', '2025-11-11 10:30:38'),
(14, 'fas fa-star', 'Standard', 'standard', 90.00, 'monthly', NULL, 0, 1, 1, 0, '[\"Hostel Management\"]', NULL, '2025-11-11 10:31:02', '2025-11-11 10:48:50'),
(15, 'fas fa-star', 'Premium', 'premium', 199.00, 'monthly', NULL, 0, 1, 0, 0, '[\"Hostel Management\",\"Course Management\",\"Student Management\"]', NULL, '2025-11-11 10:31:20', '2025-11-11 10:49:07'),
(16, 'fas fa-star', 'Basic', 'basic', 99.00, 'yearly', NULL, 0, 1, 0, 0, 'null', NULL, '2025-11-11 10:54:00', '2025-11-11 10:54:00'),
(17, 'fas fa-star', 'Standard', 'standard', 199.00, 'yearly', NULL, 0, 1, 1, 0, '[\"Custom Domain\"]', NULL, '2025-11-11 10:54:33', '2025-11-11 10:54:33'),
(18, 'fas fa-star', 'Premium', 'premium', 299.00, 'yearly', NULL, 0, 1, 0, 0, '[\"Custom Domain\",\"Subdomain\",\"Custom Page\"]', NULL, '2025-11-11 10:54:51', '2025-11-11 10:54:51'),
(19, 'fas fa-star', 'Basic', 'basic', 299.00, 'lifetime', NULL, 0, 1, 0, 0, '[\"Custom Domain\"]', NULL, '2025-11-11 10:56:02', '2025-11-11 10:56:02'),
(20, 'fas fa-star', 'Standard', 'standard', 299.00, 'lifetime', NULL, 0, 1, 1, 0, '[\"Custom Domain\",\"Subdomain\"]', NULL, '2025-11-11 10:56:30', '2025-11-11 10:56:30'),
(21, 'fas fa-star', 'Premium', 'premium', 499.00, 'lifetime', NULL, 0, 1, 0, 0, '[\"Custom Domain\",\"Subdomain\",\"Custom Page\"]', NULL, '2025-11-11 10:56:50', '2025-11-14 06:58:08'),
(24, NULL, 'Free', 'free', 0.00, 'yearly', 10, 1, 1, 0, 0, 'null', NULL, '2025-11-14 07:47:58', '2025-11-14 07:53:32');

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
(4, 'Instamojo', NULL, 'automatic', '{\"sandbox_status\":\"production\",\"key\":\"sdfsdf\",\"token\":\"sdfsdaf\",\"text\":\"Pay via your Credit account.\"}', 'instamojo', 1, NULL, '2025-02-12 13:18:21');

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
  `user_id` bigint DEFAULT NULL,
  `stock` int DEFAULT NULL,
  `last_restock_qty` bigint DEFAULT NULL,
  `sku` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `thumbnail` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_price` decimal(8,2) DEFAULT NULL,
  `previous_price` decimal(8,2) DEFAULT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `download_link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `download_file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `featured` tinyint NOT NULL DEFAULT '0',
  `rating` smallint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `order` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `user_id`, `stock`, `last_restock_qty`, `sku`, `thumbnail`, `current_price`, `previous_price`, `type`, `file_type`, `download_link`, `download_file`, `status`, `featured`, `rating`, `created_at`, `updated_at`, `order`) VALUES
(5, 59, -29, 18, '1234567', '6797c96602c0b.jpg', 299.99, 399.99, 'Physical', NULL, NULL, NULL, 1, 0, NULL, '2025-01-27 11:59:02', '2025-02-15 03:55:28', 0),
(6, 59, 1991, 10, '34678954', '6797c9c9a16f4.jpg', 99.99, 120.99, 'Physical', NULL, NULL, NULL, 1, 0, NULL, '2025-01-27 12:00:41', '2025-10-30 12:39:06', 0),
(7, 59, 0, 0, 'ASDFASDFASD', '679912375ca9c.png', 234.00, 443.00, 'Physical', NULL, NULL, NULL, 1, 0, NULL, '2025-01-28 11:21:59', '2025-11-21 09:01:08', 0);

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

CREATE TABLE `product_categories` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint DEFAULT NULL,
  `language_id` bigint DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `serial_number` int NOT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_categories`
--

INSERT INTO `product_categories` (`id`, `user_id`, `language_id`, `name`, `slug`, `serial_number`, `status`, `created_at`, `updated_at`) VALUES
(10, 59, 5, 'Beverages', 'beverages', 1, 1, '2025-01-27 11:52:27', '2025-11-28 11:58:14'),
(11, 59, 5, 'Desserts', 'desserts', 2, 1, '2025-01-27 11:52:42', '2025-11-28 11:58:22'),
(13, 59, 5, 'Main Course', 'main-course', 3, 1, '2025-11-28 11:58:32', '2025-11-28 11:58:32');

-- --------------------------------------------------------

--
-- Table structure for table `product_contents`
--

CREATE TABLE `product_contents` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint DEFAULT NULL,
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

INSERT INTO `product_contents` (`id`, `user_id`, `language_id`, `product_id`, `category_id`, `title`, `slug`, `summary`, `description`, `meta_keyword`, `meta_description`, `created_at`, `updated_at`) VALUES
(6, 59, 5, 5, 10, 'product 1', 'product-1', NULL, '<p>product 1</p>', '\"null\"', NULL, '2025-01-27 11:59:02', '2025-01-27 12:15:23'),
(7, 59, 5, 6, 11, 'product 2', 'product-2', NULL, '<p>product 2</p>', '\"&quot;null&quot;\"', NULL, '2025-01-27 12:00:41', '2025-01-28 11:17:30'),
(8, 59, 5, 7, 10, 'product 3', 'product-3', 'product 3', '<p>product 3</p>', 'null', NULL, '2025-01-28 11:21:59', '2025-01-28 11:21:59');

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
(3, 'OFFER99', 'OFFER99', 'fixed', 100.00, '2025-01-16', '2025-01-31', 200.00, '2025-01-15 13:00:43', '2025-01-15 13:00:43');

-- --------------------------------------------------------

--
-- Table structure for table `product_settings`
--

CREATE TABLE `product_settings` (
  `id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_variations`
--

CREATE TABLE `product_variations` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint NOT NULL,
  `language_id` bigint NOT NULL,
  `variant_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `option_name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `option_price` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `option_stock` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `indx` bigint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_variations`
--

INSERT INTO `product_variations` (`id`, `product_id`, `language_id`, `variant_name`, `option_name`, `option_price`, `option_stock`, `indx`, `created_at`, `updated_at`) VALUES
(43, 2, 1, 'new', '[\"sdfsdf\"]', '[\"4\"]', '[3]', 0, '2025-01-08 09:45:25', '2025-01-24 07:22:50'),
(44, 2, 2, 'new', '[\"sdfsdf\"]', '[\"4\"]', '[3]', 0, '2025-01-08 09:45:25', '2025-01-24 07:22:50'),
(57, 3, 1, 'Color', '[\"white\",\"black\"]', '[\"10\",\"9\"]', '[\"0\",1]', 0, '2025-01-22 12:56:50', '2025-01-24 07:23:05'),
(58, 3, 2, 'Color', '[\"white\",\"kala\"]', '[\"10\",\"9\"]', '[\"0\",1]', 0, '2025-01-22 12:56:50', '2025-01-24 07:23:05'),
(59, 3, 1, 'Size', '[\"M\",\"L\"]', '[\"11\",\"12\"]', '[0,3]', 1, '2025-01-22 12:56:50', '2025-01-24 07:23:05'),
(60, 3, 2, 'Size', '[\"M\",\"L\"]', '[\"11\",\"12\"]', '[0,3]', 1, '2025-01-22 12:56:50', '2025-01-24 07:23:05'),
(89, 8, 1, 'RAM', '[\"8 GB\",\"16 GB\"]', '[\"0\",\"0\"]', '[\"0\",\"0\"]', 0, '2025-01-29 09:59:30', '2025-01-29 09:59:30'),
(90, 8, 3, 'RAM', '[\"8 GB\",\"16 GB\"]', '[\"0\",\"0\"]', '[\"0\",\"0\"]', 0, '2025-01-29 09:59:30', '2025-01-29 09:59:30'),
(91, 8, 1, 'ROM', '[\"32 GB\",\"64 GB\"]', '[\"0\",\"0\"]', '[\"0\",\"0\"]', 1, '2025-01-29 09:59:30', '2025-01-29 09:59:30'),
(92, 8, 3, 'ROM', '[\"32 GB\",\"64 GB\"]', '[\"0\",\"0\"]', '[\"0\",\"0\"]', 1, '2025-01-29 09:59:30', '2025-01-29 09:59:30'),
(97, 7, 5, 'Nostrum dolor volupt', '[\"Et mollit veniam qu\"]', '[\"87\"]', '[\"72\"]', 0, '2025-11-21 09:06:58', '2025-11-21 09:06:58'),
(98, 7, 6, 'Iusto porro in labor', '[\"Exercitationem Nam f\"]', '[\"87\"]', '[\"72\"]', 0, '2025-11-21 09:06:58', '2025-11-21 09:06:58');

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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `uniqid`, `website_logo`, `logo_two`, `footer_logo`, `favicon`, `website_title`, `email_address`, `contact_number`, `address`, `smtp_host`, `smtp_port`, `smtp_username`, `smtp_password`, `encryption`, `sender_mail`, `sender_name`, `smtp_status`, `currency_symbol`, `currency_symbol_position`, `currency_text`, `currency_text_position`, `currency_rate`, `timezone`, `website_color`, `maintenance_image`, `maintenance_status`, `maintenance_message`, `bypass_token`, `package_expire_day`, `admin_approval`, `email_verification_approval`, `admin_approval_notice`, `pusher_app_id`, `pusher_status`, `pusher_app_key`, `pusher_app_secret`, `pusher_app_cluster`, `created_at`, `updated_at`) VALUES
(1, 1234, '6792620a5426d.png', NULL, '6623f11a26d49.png', '6792620a5379d.png', 'Business Validator', NULL, NULL, NULL, 'smtp.gmail.com', '587', 'airdrop446646@gmail.com', 'lwee cjer feik pdof', 'TLS', 'airdrop446646@gmail.com', 'Myapp', 1, '$', 'left', 'USD', 'right', 1, 'Europe/Andorra', '#FF0000FF', '6706bc36b9811.jpg', 0, '<p>Maintenance MessageMaintenance Message</p>', '-1', 4, 1, 1, 'You need to permission from admin to access this panel', '1942636', 1, 'e58380d6ebb048e6feb4', '24a208922bc018ef9b37', 'ap2', NULL, '2024-12-09 11:41:33');

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
(64, 1, 'product', '6777b4f1bea82.jpg', '2025-01-03 03:59:13', '2025-01-03 04:00:03'),
(65, 1, 'product', '6777b4f1bf487.jpg', '2025-01-03 03:59:13', '2025-01-03 04:00:03'),
(66, 2, 'product', '6777bad4ef71e.jpg', '2025-01-03 04:24:21', '2025-01-03 04:24:50'),
(73, 1, 'product', '6777f941788d6.jpg', '2025-01-03 08:50:41', '2025-01-03 08:50:43'),
(74, NULL, 'product', '677808f2ae73d.jpg', '2025-01-03 09:57:38', '2025-01-03 09:57:38'),
(75, 3, 'product', '677809c323a63.jpg', '2025-01-03 10:01:07', '2025-01-03 10:02:26'),
(81, NULL, 'product', '67794d4381f5a.jpg', '2025-01-04 09:01:23', '2025-01-04 09:01:23'),
(98, 4, 'product', '6792613ea02e7.jpg', '2025-01-23 09:33:18', '2025-01-23 09:34:32'),
(101, 5, 'product', '6797c8b7061f3.jpg', '2025-01-27 11:56:07', '2025-01-27 11:59:02'),
(102, 6, 'product', '6797c9a7952d9.jpg', '2025-01-27 12:00:07', '2025-01-27 12:00:41'),
(103, 7, 'product', '679911b7176b9.jpg', '2025-01-28 11:19:51', '2025-01-28 11:21:59'),
(118, 9, 'product', '6920767363c2d.jpg', '2025-11-21 08:25:55', '2025-11-21 08:30:55');

-- --------------------------------------------------------

--
-- Table structure for table `tables`
--

CREATE TABLE `tables` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint NOT NULL,
  `language_id` bigint NOT NULL,
  `table_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `capacity` int NOT NULL DEFAULT '1',
  `status` enum('available','occupied','reserved','cleaning','unavailable') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `qr_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `serial_number` int NOT NULL DEFAULT '1',
  `color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#000000',
  `size` int NOT NULL DEFAULT '300',
  `margin` int NOT NULL DEFAULT '1',
  `style` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'square',
  `eye_style` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'square',
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default',
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_size` int NOT NULL DEFAULT '10',
  `image_x` int NOT NULL DEFAULT '50',
  `image_y` int NOT NULL DEFAULT '50',
  `text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text_color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#000000',
  `text_size` int NOT NULL DEFAULT '5',
  `text_x` int NOT NULL DEFAULT '50',
  `text_y` int NOT NULL DEFAULT '50',
  `qr_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
(1, 'eiZegNVj7z', 0.00, 12.00, 12.00, '$', 'left', 'completed', 'Cash', 'product_purchase', '2025-01-24 03:41:49', '2025-01-24 03:41:49'),
(2, 'vkHJu3OZEW', 12.00, 141.00, 153.00, '$', 'left', 'completed', 'Cash', 'product_purchase', '2025-01-24 03:44:07', '2025-01-24 03:44:07'),
(3, 'c1uKwQWHxB', 153.00, 12.00, 165.00, '$', 'left', 'completed', 'Cash', 'product_purchase', '2025-01-24 03:45:08', '2025-01-24 03:45:08'),
(4, 'iBnbmQKprE', 165.00, 152.00, 317.00, '$', 'left', 'completed', 'Cash', 'product_purchase', '2025-01-24 07:22:36', '2025-01-24 07:22:36'),
(5, 'HR1wy13DzT', 317.00, 4549.00, 4866.00, '$', 'left', 'completed', 'Cash', 'product_purchase', '2025-01-24 07:22:50', '2025-01-24 07:22:50'),
(6, 'ipq4Xw6fIz', 4866.00, 152.00, 5018.00, '$', 'left', 'completed', 'Cash', 'product_purchase', '2025-01-24 07:23:05', '2025-01-24 07:23:05'),
(7, 'f5Hj32QQG4', 5018.00, 12.00, 5030.00, '$', 'left', 'completed', 'Cash', 'product_purchase', '2025-01-24 07:23:15', '2025-01-24 07:23:15'),
(8, 'o2RLvQwJSt', 5030.00, 12.00, 5042.00, '$', 'left', 'completed', 'Cash', 'product_purchase', '2025-01-24 07:25:01', '2025-01-24 07:25:01'),
(9, 't14gle3FgS', 5042.00, 399.98, 5441.98, '$', 'left', 'completed', 'Cash', 'product_purchase', '2025-01-27 12:02:51', '2025-01-27 12:02:51'),
(10, 'ubh7kRpSC8', 5441.98, 299.97, 5741.95, '$', 'left', 'completed', 'Cash', 'product_purchase', '2025-01-27 12:15:39', '2025-01-27 12:15:39'),
(11, 'AMD3D5Z0', 5741.95, 99.99, 5841.94, '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-01-27 12:56:07', '2025-01-27 12:56:07'),
(12, 'QYZ8AAOW', 5841.94, 399.98, 6241.92, '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-01-27 13:03:38', '2025-01-27 13:03:38'),
(13, '0PIHS2R2', 6241.92, 299.99, 6541.91, '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-01-27 13:05:11', '2025-01-27 13:05:11'),
(14, '9PJ5N8ZQ', 6541.91, 99.99, 6641.90, '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-01-27 13:06:13', '2025-01-27 13:06:13'),
(15, '45X32O2B', 6641.90, 79.00, 6720.90, '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-01-31 01:01:07', '2025-01-31 01:01:07'),
(16, 'TDPPCEB7', 6720.90, 319.20, 7040.10, '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-02 11:05:48', '2025-02-02 11:05:48'),
(17, 'OXPSJN1G', 7040.10, 358.99, 7399.09, '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-02 12:16:11', '2025-02-02 12:16:11'),
(18, 'A4X2IHZO', 7399.09, 259.00, 7658.09, '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 02:48:48', '2025-02-14 02:48:48'),
(19, 'N6N8XA4V', 7658.09, 650.98, 8309.07, '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 07:56:29', '2025-02-14 07:56:29'),
(20, 'V77CNFTM', 8309.07, 673.08, 8982.15, '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 07:58:50', '2025-02-14 07:58:50'),
(21, 'ISU9M8Q9', 8982.15, 658.98, 9641.13, '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 08:34:37', '2025-02-14 08:34:37'),
(22, 'KNASWBSR', 9641.13, 299.99, 9941.12, '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 09:06:59', '2025-02-14 09:06:59'),
(23, 'XTNSU6DO', 9941.12, 558.99, 10500.11, '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 09:20:24', '2025-02-14 09:20:24'),
(24, '4260IO95', 10500.11, 299.99, 10800.10, '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 09:22:26', '2025-02-14 09:22:26'),
(25, 'PDFLJO3D', 10800.10, 299.99, 11100.09, '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 09:26:52', '2025-02-14 09:26:52'),
(26, '7KSMGOU1', 11100.09, 251.00, 11351.09, '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 09:30:24', '2025-02-14 09:30:24'),
(27, 'TOBFESWA', 11351.09, 299.99, 11651.08, '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 09:33:28', '2025-02-14 09:33:28'),
(28, 'LMVQS0TO', 11651.08, 299.99, 11951.07, '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 09:36:34', '2025-02-14 09:36:34'),
(29, '0Q9G2QMA', 11951.07, 299.99, 12251.06, '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 09:44:03', '2025-02-14 09:44:03'),
(30, 'M6N9SXL8', 12251.06, 299.99, 12551.05, '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 09:46:02', '2025-02-14 09:46:02'),
(31, 'E60O3XHO', 12551.05, 299.99, 12851.04, '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 09:50:34', '2025-02-14 09:50:34'),
(32, 'C1288N59', 12851.04, 299.99, 13151.03, '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 09:56:12', '2025-02-14 09:56:12'),
(33, '8X4FD2J2', 13151.03, 299.99, 13451.02, '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 09:58:46', '2025-02-14 09:58:46'),
(34, '6VYZKETT', 13451.02, 299.99, 13751.01, '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 10:00:07', '2025-02-14 10:00:07'),
(35, 'AFG34V7O', 13751.01, 299.99, 14051.00, '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 10:04:08', '2025-02-14 10:04:08'),
(36, 'Y0WIKIFC', 14051.00, 299.99, 14350.99, '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 10:06:04', '2025-02-14 10:06:04'),
(37, '4EUQ9CVL', 14350.99, 299.99, 14650.98, '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 11:19:07', '2025-02-14 11:19:07'),
(38, '2K4M5CCZ', 14650.98, 299.99, 14950.97, '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 11:30:10', '2025-02-14 11:30:10'),
(39, '1AYCOS9H', 14950.97, 299.99, 15250.96, '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 11:37:11', '2025-02-14 11:37:11'),
(40, '9AUPZOK1', 15250.96, 299.99, 15550.95, '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 11:47:05', '2025-02-14 11:47:05'),
(41, 'O0KX6I0D', 15550.95, 300.00, 15850.95, '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 11:55:54', '2025-02-14 11:55:54'),
(42, 'JNAGIML7', 15850.95, 559.00, 16409.95, '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 11:57:25', '2025-02-14 11:57:25'),
(43, 'G7QN0BGL', 16409.95, 400.00, 16809.95, '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 12:01:59', '2025-02-14 12:01:59'),
(44, 'B6BISCZL', 16809.95, 300.00, 17109.95, '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 12:02:00', '2025-02-14 12:02:00'),
(45, 'U9Q2PAMQ', 17109.95, 300.00, 17409.95, '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 12:07:38', '2025-02-14 12:07:38'),
(46, 'PHJ116YG', 17409.95, 300.00, 17709.95, '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 12:16:29', '2025-02-14 12:16:29'),
(47, '2EKVK9TQ', 17709.95, 300.00, 18009.95, '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 12:22:42', '2025-02-14 12:22:42'),
(48, 'IZLTFVA2', 18009.95, 559.00, 18568.95, '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 12:30:01', '2025-02-14 12:30:01'),
(49, 'Y8FACDZL', 18568.95, 300.00, 18868.95, '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 12:33:04', '2025-02-14 12:33:04'),
(50, 'TQOPGCAV', 18868.95, 400.00, 19268.95, '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-14 12:34:17', '2025-02-14 12:34:17'),
(51, '2CMNMEC3', 19268.95, 300.00, 19568.95, '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-15 01:02:56', '2025-02-15 01:02:56'),
(52, '2S8LHS63', 19568.95, 300.00, 19868.95, '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-15 01:03:00', '2025-02-15 01:03:00'),
(53, 'A41V83DY', 19868.95, 300.00, 20168.95, '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-15 01:05:04', '2025-02-15 01:05:04'),
(54, 'N8TAU16P', 20168.95, 100.00, 20268.95, '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-15 01:06:10', '2025-02-15 01:06:10'),
(55, 'TDNEMYYS', 20268.95, 300.00, 20568.95, '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-15 01:06:51', '2025-02-15 01:06:51'),
(56, 'AHLJNGV2', 20568.95, 300.00, 20868.95, '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-15 01:07:00', '2025-02-15 01:07:00'),
(57, '2TON346J', 20868.95, 300.00, 21168.95, '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-15 03:48:54', '2025-02-15 03:48:54'),
(58, '6AKW8LR6', 21168.95, 300.00, 21468.95, '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-15 03:50:50', '2025-02-15 03:50:50'),
(59, '5OVCYHMM', 21468.95, 300.00, 21768.95, '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-15 03:53:45', '2025-02-15 03:53:45'),
(60, 'A9UEWQAG', 21768.95, 300.00, 22068.95, '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-15 03:54:52', '2025-02-15 03:54:52'),
(61, 'ESLBDTWW', 22068.95, 100.00, 22168.95, '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-15 03:55:17', '2025-02-15 03:55:17'),
(62, 'A76RQHZK', 22168.95, 300.00, 22468.95, '$', 'left', 'completed', 'Cash Payment', 'product_purchase', '2025-02-15 03:55:29', '2025-02-15 03:55:29');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
  `database_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `database_username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `database_password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `qr_menu_enabled` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `company_name`, `username`, `email`, `image`, `status`, `phone`, `country`, `city`, `state`, `zip_code`, `address`, `email_verified_at`, `password`, `remember_token`, `database_name`, `database_username`, `database_password`, `created_at`, `updated_at`, `qr_menu_enabled`) VALUES
(59, NULL, 'Ballard and Sawyer Traders', 'vysidexud', 'dehat@mailinator.com', NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 06:24:12', '$2y$12$RpYAqen.fCBNSFbY34jomOEWl.SFvnQOlzwMQWK0sM0rK80zTUfT.', 'ahVDR4CCllzUT9QMpI2srGHDzdaYl1KVzVnSzLqn26YXlgPxJU1nPvw2xzRI', NULL, NULL, NULL, '2025-11-21 06:24:12', '2025-11-25 10:46:09', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_languages`
--

CREATE TABLE `user_languages` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `dashboard_default` tinyint(1) NOT NULL DEFAULT '0',
  `direction` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'admin',
  `keywords` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_languages`
--

INSERT INTO `user_languages` (`id`, `user_id`, `name`, `code`, `is_default`, `dashboard_default`, `direction`, `created_by`, `keywords`, `created_at`, `updated_at`) VALUES
(5, 59, 'English', 'en', 0, 1, 'LTR', 'admin', '{\"Inactive\":\"Inactive\",\"Active\":\"Active\",\"completed\":\"completed\",\"Order Code\":\"Order Code\",\"Customer\":\"Customer\",\"Actions\":\"Actions\",\"Order Status\":\"Order Status\",\"Payment Status\":\"Payment Status\",\"Payment method\":\"Payment method\",\"Monthly Orders\":\"Monthly Orders\",\"Monthly Sale\":\"Monthly Sale\",\"Filter\":\"Filter\",\"Total Earning on\":\"Total Earning on\",\"Total Sales on\":\"Total Sales on\",\"Total Earning\":\"Total Earning\",\"Today\'s Sales\":\"Today\'s Sales\",\"Today\'s Pending\":\"Today\'s Pending\",\"Add or select a customer to include billing details\":\"Add or select a customer to include billing details\",\"Inventory Management\":\"Inventory Management\",\"Select a customer\":\"Select a customer\",\"Add Customer\":\"Add Customer\",\"ADD PRODUCT\":\"ADD PRODUCT\",\"Tax Amount\":\"Tax Amount\",\"Discount Amount\":\"Discount Amount\",\"Shipping Amount\":\"Shipping Amount\",\"Deactive\":\"Deactive\",\"Status\":\"Status\",\"Config Email Setting\":\"Config Email Setting\",\"Common\":\"Common\",\"Edit Admin Keyword\":\"Edit Admin Keyword\",\"Keywords of\":\"Keywords of\",\"Back\":\"Back\",\"Favicon\":\"Favicon\",\"Logo\":\"Logo\",\"Select Currency Position\":\"Select Currency Position\",\"Left\":\"Left\",\"Right\":\"Right\",\"Save Changes\":\"Save Changes\",\"Currency Symbol Position\":\"Currency Symbol Position\",\"Currency Text Position\":\"Currency Text Position\",\"Currency Symbol\":\"Currency Symbol\",\"Currency Text\":\"Currency Text\",\"Currency Rate\":\"Currency Rate\",\"Websit Color\":\"Websit Color\",\"Currency Information\":\"Currency Information\",\"Website Appearance\":\"Website Appearance\",\"Timezone\":\"Timezone\",\"Website Title\":\"Website Title\",\"Website Information\":\"Website Information\",\"Close\":\"Close\",\"Save\":\"Save\",\"NO PRODUCT FOUND\":\"NO PRODUCT FOUND\",\"Enable or disable maintenance mode and configure settings for site updates\":\"Enable or disable maintenance mode and configure settings for site updates\",\"View and update plugin setting\":\"View and update plugin setting\",\"Manage and configure payment gateway settings\":\"Manage and configure payment gateway settings\",\"Email templates using HTML & system variables\":\"Email templates using HTML & system variables\",\"View and update your email settings and email templates\":\"View and update your email settings and email templates\",\"View and update your general settings and activate license\":\"View and update your general settings and activate license\",\"Page Heading\":\"Page Heading\",\"Payment Gateway\":\"Payment Gateway\",\"Plugins\":\"Plugins\",\"Maintenance Mode\":\"Maintenance Mode\",\"Email Templates\":\"Email Templates\",\"Registered Users\":\"Registered Users\",\"Language\":\"Language\",\"User Management\":\"User Management\",\"Language Management\":\"Language Management\",\"Packages\":\"Packages\",\"Package Management\":\"Package Management\",\"All Admins\":\"All Admins\",\"Role & Permission\":\"Role & Permission\",\"Role Management\":\"Role Management\",\"Vendors\":\"Vendors\",\"Vendor Management\":\"Vendor Management\",\"Posts\":\"Posts\",\"Blog Management\":\"Blog Management\",\"Products\":\"Products\",\"Coupons\":\"Coupons\",\"Categories\":\"Categories\",\"Product Management\":\"Product Management\",\"Reports\":\"Reports\",\"All Sales\":\"All Sales\",\"Sales Management\":\"Sales Management\",\"POS\":\"POS\",\"Email Settings\":\"Email Settings\",\"General Settings\":\"General Settings\",\"Dashboard\":\"Dashboard\",\"Settings\":\"Settings\",\"Rejected Orders\":\"Rejected Orders\",\"Pending Orders\":\"Pending Orders\",\"Completed Orders\":\"Completed Orders\",\"Total Sales\":\"Total Sales\",\"Today\'s Earning\":\"Today\'s Earning\"}\n', '2025-11-21 06:24:12', '2025-11-25 10:44:02'),
(6, 59, 'عربي', 'ar', 1, 0, 'RTL', 'admin', '{\"Inactive\":\"Inactive\",\"Active\":\"Active\",\"completed\":\"completed\",\"Order Code\":\"Order Code\",\"Customer\":\"Customer\",\"Actions\":\"Actions\",\"Order Status\":\"Order Status\",\"Payment Status\":\"Payment Status\",\"Payment method\":\"Payment method\",\"Monthly Orders\":\"Monthly Orders\",\"Monthly Sale\":\"Monthly Sale\",\"Filter\":\"Filter\",\"Total Earning on\":\"Total Earning on\",\"Total Sales on\":\"Total Sales on\",\"Total Earning\":\"Total Earning\",\"Today\'s Sales\":\"Today\'s Sales\",\"Today\'s Pending\":\"Today\'s Pending\",\"Add or select a customer to include billing details\":\"Add or select a customer to include billing details\",\"Inventory Management\":\"Inventory Management\",\"Select a customer\":\"Select a customer\",\"Add Customer\":\"Add Customer\",\"ADD PRODUCT\":\"ADD PRODUCT\",\"Tax Amount\":\"Tax Amount\",\"Discount Amount\":\"Discount Amount\",\"Shipping Amount\":\"Shipping Amount\",\"Deactive\":\"Deactive\",\"Status\":\"Status\",\"Config Email Setting\":\"Config Email Setting\",\"Common\":\"Common\",\"Edit Admin Keyword\":\"Edit Admin Keyword\",\"Keywords of\":\"Keywords of\",\"Back\":\"Back\",\"Favicon\":\"Favicon\",\"Logo\":\"Logo\",\"Select Currency Position\":\"Select Currency Position\",\"Left\":\"Left\",\"Right\":\"Right\",\"Save Changes\":\"Save Changes\",\"Currency Symbol Position\":\"Currency Symbol Position\",\"Currency Text Position\":\"Currency Text Position\",\"Currency Symbol\":\"Currency Symbol\",\"Currency Text\":\"Currency Text\",\"Currency Rate\":\"Currency Rate\",\"Websit Color\":\"Websit Color\",\"Currency Information\":\"Currency Information\",\"Website Appearance\":\"Website Appearance\",\"Timezone\":\"Timezone\",\"Website Title\":\"Website Title\",\"Website Information\":\"Website Information\",\"Close\":\"Close\",\"Save\":\"Save\",\"NO PRODUCT FOUND\":\"NO PRODUCT FOUND\",\"Enable or disable maintenance mode and configure settings for site updates\":\"Enable or disable maintenance mode and configure settings for site updates\",\"View and update plugin setting\":\"View and update plugin setting\",\"Manage and configure payment gateway settings\":\"Manage and configure payment gateway settings\",\"Email templates using HTML & system variables\":\"Email templates using HTML & system variables\",\"View and update your email settings and email templates\":\"View and update your email settings and email templates\",\"View and update your general settings and activate license\":\"View and update your general settings and activate license\",\"Page Heading\":\"Page Heading\",\"Payment Gateway\":\"Payment Gateway\",\"Plugins\":\"Plugins\",\"Maintenance Mode\":\"Maintenance Mode\",\"Email Templates\":\"Email Templates\",\"Registered Users\":\"Registered Users\",\"Language\":\"Language\",\"User Management\":\"User Management\",\"Language Management\":\"Language Management\",\"Packages\":\"Packages\",\"Package Management\":\"Package Management\",\"All Admins\":\"All Admins\",\"Role & Permission\":\"Role & Permission\",\"Role Management\":\"Role Management\",\"Vendors\":\"Vendors\",\"Vendor Management\":\"Vendor Management\",\"Posts\":\"Posts\",\"Blog Management\":\"Blog Management\",\"Products\":\"Products\",\"Coupons\":\"Coupons\",\"Categories\":\"Categories\",\"Product Management\":\"Product Management\",\"Reports\":\"Reports\",\"All Sales\":\"All Sales\",\"Sales Management\":\"Sales Management\",\"POS\":\"POS\",\"Email Settings\":\"Email Settings\",\"General Settings\":\"General Settings\",\"Dashboard\":\"Dashboard\",\"Settings\":\"Settings\",\"Rejected Orders\":\"Rejected Orders\",\"Pending Orders\":\"Pending Orders\",\"Completed Orders\":\"Completed Orders\",\"Total Sales\":\"Total Sales\",\"Today\'s Earning\":\"Today\'s Earning\"}\n', '2025-11-21 06:24:12', '2025-11-21 06:24:12');

-- --------------------------------------------------------

--
-- Table structure for table `user_settings`
--

CREATE TABLE `user_settings` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint NOT NULL,
  `website_logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `favicon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `footer_logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website_color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `timezone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_symbol` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_symbol_position` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_text_position` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency_rate` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_settings`
--

INSERT INTO `user_settings` (`id`, `user_id`, `website_logo`, `favicon`, `footer_logo`, `website_title`, `website_color`, `timezone`, `currency_symbol`, `currency_symbol_position`, `currency_text`, `currency_text_position`, `currency_rate`, `created_at`, `updated_at`) VALUES
(3, 59, NULL, NULL, NULL, 'Business Validator', '#FF0000FF', 'Europe/Andorra', '$', 'left', 'USD', 'left', '1', '2025-11-21 06:24:12', '2025-11-21 06:24:12');

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
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `product_settings`
--
ALTER TABLE `product_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_variations`
--
ALTER TABLE `product_variations`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `user_languages`
--
ALTER TABLE `user_languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_settings`
--
ALTER TABLE `user_settings`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=153;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=176;

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `product_contents`
--
ALTER TABLE `product_contents`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `product_coupons`
--
ALTER TABLE `product_coupons`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `product_settings`
--
ALTER TABLE `product_settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_variations`
--
ALTER TABLE `product_variations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `slider_images`
--
ALTER TABLE `slider_images`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT for table `tables`
--
ALTER TABLE `tables`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `user_languages`
--
ALTER TABLE `user_languages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user_settings`
--
ALTER TABLE `user_settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
