-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 10, 2022 at 03:44 PM
-- Server version: 5.7.23-23
-- PHP Version: 7.3.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `aditecch_sid`
--

-- --------------------------------------------------------

--
-- Table structure for table `pa_tokens`
--

CREATE TABLE `pa_tokens` (
  `id` int(11) NOT NULL,
  `access_token` text COLLATE utf8_unicode_ci,
  `refresh_token` text COLLATE utf8_unicode_ci,
  `date_updated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `pa_tokens`
--

INSERT INTO `pa_tokens` (`id`, `access_token`, `refresh_token`, `date_updated`) VALUES
(1, '1000.5cd56761cd52e2eb9fb110719a1f93bf.13f4a80c842ac17cf2204d9fa7828c45', '1000.a894f577917a14e5e0f7e5c6070bdda2.02ba9ff3094bbcb32c471b6ed2eb7c94', '0000-00-00 00:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pa_tokens`
--
ALTER TABLE `pa_tokens`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pa_tokens`
--
ALTER TABLE `pa_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
