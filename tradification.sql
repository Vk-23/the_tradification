-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 17, 2025 at 08:18 AM
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
-- Database: `tradification`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `email`, `password`) VALUES
(1, 'Advocate@gmail.com', '$2a$12$vXPx2qEiMESvhTp0eyUN9uQZf.c1Zrk8oI.DUYK7kOVTKgEYOBqJC');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `name`, `email`, `phone`, `subject`, `message`, `created_at`) VALUES
(1, 'testing', 'testing@gmail.com', '8765432109', 'testing', 'testing', '2025-07-09 21:35:41'),
(2, 'testing', 'testing@gmail.com', '123456u90', 'testing', 'rer', '2025-07-13 14:20:25'),
(3, 'ravina Darwai', 'testing@gmail.com', '9098839256', 'testing', 'testing', '2025-07-13 18:31:31'),
(4, 'djfjef', 'jroejoe@gmail.com', 'eenrjenrie', 'kjkejrrke', 'nejenej', '2025-07-13 20:24:13'),
(5, 'dfkdn', 'hudh@gmail.com', '2345678907', 'sdfghjkl', 'srtyuiuygyg', '2025-07-13 20:24:39');

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `type` enum('percentage','amount') NOT NULL,
  `value` decimal(10,2) NOT NULL,
  `min_amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `type`, `value`, `min_amount`, `created_at`) VALUES
(1, '54326', 'amount', 500.00, 1000.00, '2025-07-13 21:38:59'),
(3, '12345', 'percentage', 2.00, 1000.00, '2025-07-13 22:46:13');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `long_description` text DEFAULT NULL,
  `lectures` int(11) DEFAULT NULL,
  `level` varchar(50) DEFAULT NULL,
  `banner_active` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `video_link` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `category_id`, `name`, `thumbnail`, `short_description`, `long_description`, `lectures`, `level`, `banner_active`, `created_at`, `updated_at`, `video_link`) VALUES
(1, 1, 'Grow your skills learn with us from anywhere', '1752094589_imgi_21_forex-trade-graph-chart-concept_53876-132317.jpg', 'Grow your skills learn with us from anywhere', 'Performance panel improvements\nThis version brings several improvements to the Performance panel.\n\nPreconnected origins in \'Network dependency tree\' insight\nThe Performance > Insights > Network dependency tree insight now shows you a list of used or unused preconnected origins and preconnect candidates, if any.\n\nPreconnect hints let your site establish early connections to important third-party origins and improve page load speed.Performance panel improvements\nThis version brings several improvements to the Performance panel.\n\nPreconnected origins in \'Network dependency tree\' insight\nThe Performance > Insights > Network dependency tree insight now shows you a list of used or unused preconnected origins and preconnect candidates, if any.\n\nPreconnect hints let your site establish early connections to important third-party origins and improve page load speed.Performance panel improvements\nThis version brings several improvements to the Performance panel.\n\nPreconnected origins in \'Network dependency tree\' insight\nThe Performance > Insights > Network dependency tree insight now shows you a list of used or unused preconnected origins and preconnect candidates, if any.\n\nPreconnect hints let your site establish early connections to important third-party origins and improve page load speed.Performance panel improvements\nThis version brings several improvements to the Performance panel.\n\nPreconnected origins in \'Network dependency tree\' insight\nThe Performance > Insights > Network dependency tree insight now shows you a list of used or unused preconnected origins and preconnect candidates, if any.\n\nPreconnect hints let your site establish early connections to important third-party origins and improve page load speed.Performance panel improvements\nThis version brings several improvements to the Performance panel.\n\nPreconnected origins in \'Network dependency tree\' insight\nThe Performance > Insights > Network dependency tree insight now shows you a list of used or unused preconnected origins and preconnect candidates, if any.\n\nPreconnect hints let your site establish early connections to important third-party origins and improve page load speed.', 1, 'Beginner', 1, '2025-07-09 20:56:29', '2025-07-13 13:47:50', 'https://www.youtube.com/watch?v=mClF6mJV5xM&ab_channel=T-Series'),
(2, 2, 'Grow your skills learn with us from anywhere', '1752095300_imgi_33_forex-trading-buy-sell-trend-stock-market-background_1017-38015.jpg', 'Grow your skills learn with us from anywhere', 'Performance panel improvements\nThis version brings several improvements to the Performance panel.\n\nPreconnected origins in \'Network dependency tree\' insight\nThe Performance > Insights > Network dependency tree insight now shows you a list of used or unused preconnected origins and preconnect candidates, if any.\n\nPreconnect hints let your site establish early connections to important third-party origins and improve page load speed.Performance panel improvements\nThis version brings several improvements to the Performance panel.\n\nPreconnected origins in \'Network dependency tree\' insight\nThe Performance > Insights > Network dependency tree insight now shows you a list of used or unused preconnected origins and preconnect candidates, if any.\n\nPreconnect hints let your site establish early connections to important third-party origins and improve page load speed.Performance panel improvements\nThis version brings several improvements to the Performance panel.\n\nPreconnected origins in \'Network dependency tree\' insight\nThe Performance > Insights > Network dependency tree insight now shows you a list of used or unused preconnected origins and preconnect candidates, if any.\n\nPreconnect hints let your site establish early connections to important third-party origins and improve page load speed.Performance panel improvements\nThis version brings several improvements to the Performance panel.\n\nPreconnected origins in \'Network dependency tree\' insight\nThe Performance > Insights > Network dependency tree insight now shows you a list of used or unused preconnected origins and preconnect candidates, if any.\n\nPreconnect hints let your site establish early connections to important third-party origins and improve page load speed.Performance panel improvements\nThis version brings several improvements to the Performance panel.\n\nPreconnected origins in \'Network dependency tree\' insight\nThe Performance > Insights > Network dependency tree insight now shows you a list of used or unused preconnected origins and preconnect candidates, if any.\n\nPreconnect hints let your site establish early connections to important third-party origins and improve page load speed.', 1, 'Beginner', 1, '2025-07-09 21:08:20', '2025-07-13 13:47:58', 'https://www.youtube.com/watch?v=mClF6mJV5xM&ab_channel=T-Series');

-- --------------------------------------------------------

--
-- Table structure for table `course_categories`
--

CREATE TABLE `course_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course_categories`
--

INSERT INTO `course_categories` (`id`, `name`, `created_at`) VALUES
(1, 'testing1', '2025-07-09 20:38:12'),
(2, 'testing2', '2025-07-09 20:38:22');

-- --------------------------------------------------------

--
-- Table structure for table `course_comments`
--

CREATE TABLE `course_comments` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course_comments`
--

INSERT INTO `course_comments` (`id`, `course_id`, `name`, `email`, `comment`, `profile_picture`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'testing', 'testing@gmail.com', 'testing', 'uploads/profile_6873b6e3b28381.69833757.jpg', 'approved', '2025-07-13 13:38:43', '2025-07-13 13:39:07');

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `testimonials`
--

INSERT INTO `testimonials` (`id`, `name`, `message`, `image_url`, `status`, `created_at`, `updated_at`) VALUES
(1, 'testin', 'testing', 'backend/uploads/testimonial_6873ec4a29f947.94433603.jpg', 'approved', '2025-07-13 17:26:34', '2025-07-13 17:26:51'),
(2, 'testing2', 'testing2', 'backend/uploads/testimonial_6873ec7a9f60e0.64248890.jpg', 'approved', '2025-07-13 17:27:22', '2025-07-13 17:28:27'),
(3, 'testing3', 'testig3', 'backend/uploads/testimonial_6873ec97003753.39928339.jpg', 'approved', '2025-07-13 17:27:51', '2025-07-13 17:28:32'),
(4, 'testing4', 'testing4', 'backend/uploads/testimonial_6873ecacb95770.13932944.jpg', 'approved', '2025-07-13 17:28:12', '2025-07-13 17:28:37'),
(5, 'bjfhdfdn', 'dhihdi', 'backend/uploads/testimonial_6874092f665089.91105057.jpg', 'approved', '2025-07-13 19:29:51', '2025-07-13 20:46:10'),
(8, 'njjjnjn', 'nujijijui uhuh', 'backend/uploads/testimonial_68741d014d0382.60342014.jpg', 'pending', '2025-07-13 20:54:25', '2025-07-13 20:54:25'),
(9, 'jnkjnjkjnj', 'jijijiji', 'backend/uploads/testimonial_68741d547464f5.18325136.jpg', 'pending', '2025-07-13 20:55:48', '2025-07-13 20:55:48');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`, `address`, `created_at`) VALUES
(1, 'testing1', 'testing@gmail.com', '9876543210', '$2y$10$lxZArT1vX.AGe3djiXHWVuQKdtI3Dv40Vh8BI6/uKFlJYG9eFAjpe', 'bhopal (M.P)', '2025-07-09 21:39:51');

-- --------------------------------------------------------

--
-- Table structure for table `webinars`
--

CREATE TABLE `webinars` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `long_description` text DEFAULT NULL,
  `webinar_date` date DEFAULT NULL,
  `webinar_time` time DEFAULT NULL,
  `hours` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `webinar_link` varchar(255) DEFAULT NULL,
  `banner_active` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `webinars`
--

INSERT INTO `webinars` (`id`, `category_id`, `name`, `thumbnail`, `short_description`, `long_description`, `webinar_date`, `webinar_time`, `hours`, `price`, `webinar_link`, `banner_active`, `created_at`, `updated_at`) VALUES
(2, 1, 'Grow your skills learn with us from anywhere', '1752086048_imgi_2_stock-market-trader-illustration_1284-9882 (1).jpg', 'Grow your skills learn with us from anywhere', 'testing', '2025-07-31', '07:08:00', 3, 2000.00, 'http://localhost/thetradification/project-settings/webinar.html', 1, '2025-07-09 18:34:08', '2025-07-13 11:57:14'),
(3, 1, 'Grow your skills learn with us from anywhere', '1752098330_imgi_230_binary-options-trading-platform-trading-exchange-interface-screen-realistic-black-laptop-isolated-background_175392-62.jpg', 'Grow your skills learn with us from anywhere', 'testing', '2025-07-31', '02:40:00', 2, 2002.00, 'http://localhost/thetradification/project-settings/webinar.html', 1, '2025-07-09 19:07:02', '2025-07-12 22:19:25');

-- --------------------------------------------------------

--
-- Table structure for table `webinar_categories`
--

CREATE TABLE `webinar_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `webinar_categories`
--

INSERT INTO `webinar_categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'testing', '2025-07-09 12:09:18', '2025-07-09 12:15:23'),
(2, 'testing1', '2025-07-09 20:34:32', '2025-07-09 20:34:32');

-- --------------------------------------------------------

--
-- Table structure for table `webinar_comments`
--

CREATE TABLE `webinar_comments` (
  `id` int(11) NOT NULL,
  `webinar_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `webinar_comments`
--

INSERT INTO `webinar_comments` (`id`, `webinar_id`, `name`, `email`, `comment`, `profile_picture`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 'testing', 'testing@gmail.com', 'testing', 'uploads/profile_6872d5f00861d5.70601439.jpg', 'approved', '2025-07-12 21:38:56', '2025-07-12 21:39:23');

-- --------------------------------------------------------

--
-- Table structure for table `webinar_testimonials`
--

CREATE TABLE `webinar_testimonials` (
  `id` int(11) NOT NULL,
  `webinar_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `course_categories`
--
ALTER TABLE `course_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `course_comments`
--
ALTER TABLE `course_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_course_comments_course` (`course_id`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `webinars`
--
ALTER TABLE `webinars`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `webinar_categories`
--
ALTER TABLE `webinar_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `webinar_comments`
--
ALTER TABLE `webinar_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_webinar_comments_webinar` (`webinar_id`);

--
-- Indexes for table `webinar_testimonials`
--
ALTER TABLE `webinar_testimonials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `webinar_id` (`webinar_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `course_categories`
--
ALTER TABLE `course_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `course_comments`
--
ALTER TABLE `course_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `webinars`
--
ALTER TABLE `webinars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `webinar_categories`
--
ALTER TABLE `webinar_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `webinar_comments`
--
ALTER TABLE `webinar_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `webinar_testimonials`
--
ALTER TABLE `webinar_testimonials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `course_categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `course_comments`
--
ALTER TABLE `course_comments`
  ADD CONSTRAINT `fk_course_comments_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `webinars`
--
ALTER TABLE `webinars`
  ADD CONSTRAINT `webinars_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `webinar_categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `webinar_comments`
--
ALTER TABLE `webinar_comments`
  ADD CONSTRAINT `fk_webinar_comments_webinar` FOREIGN KEY (`webinar_id`) REFERENCES `webinars` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `webinar_testimonials`
--
ALTER TABLE `webinar_testimonials`
  ADD CONSTRAINT `webinar_testimonials_ibfk_1` FOREIGN KEY (`webinar_id`) REFERENCES `webinars` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
