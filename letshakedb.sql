-- phpMyAdmin SQL Dump
-- version 4.4.12
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 02, 2015 at 04:40 PM
-- Server version: 5.6.25
-- PHP Version: 5.5.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `letshakedb`
--

-- --------------------------------------------------------

--
-- Table structure for table `apikeys`
--

CREATE TABLE IF NOT EXISTS `apikeys` (
  `appId` varchar(50) NOT NULL,
  `appSecret` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `apikeys`
--

INSERT INTO `apikeys` (`appId`, `appSecret`) VALUES
('letshake', 'letshake123');

-- --------------------------------------------------------

--
-- Table structure for table `friends`
--

CREATE TABLE IF NOT EXISTS `friends` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `friendId` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `friends`
--

INSERT INTO `friends` (`id`, `userId`, `friendId`) VALUES
(1, 1, 2),
(2, 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `scores`
--

CREATE TABLE IF NOT EXISTS `scores` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `score` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `scores`
--

INSERT INTO `scores` (`id`, `userId`, `score`) VALUES
(1, 1, 900),
(2, 2, 500),
(3, 3, 400),
(4, 4, 300),
(5, 5, 200),
(6, 6, 100),
(7, 7, 600);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL,
  `fbId` varchar(100) NOT NULL,
  `token` varchar(200) DEFAULT NULL,
  `loginDate` date DEFAULT NULL,
  `logoutDate` date DEFAULT NULL,
  `name` varchar(200) DEFAULT NULL,
  `locale` varchar(100) DEFAULT NULL,
  `deviceModel` varchar(100) DEFAULT NULL,
  `osVersion` varchar(100) DEFAULT NULL,
  `udidDevice` varchar(100) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fbId`, `token`, `loginDate`, `logoutDate`, `name`, `locale`, `deviceModel`, `osVersion`, `udidDevice`) VALUES
(1, '123', 'MTIzLTE0NDExODMwMjI0NzAzZmU4ODA=', '2015-09-02', NULL, 'user1', 'en_US', 'iPhone 6 plus', 'iOS 8.1', '666'),
(2, '124', 'MTI0LTE0NDEwMzgzNTdmZGYyZDhkYWU=', '2015-08-31', NULL, 'user2', 'en_US', 'iPhone 5', 'iOS 8.1', '555'),
(3, '125', 'MTI1LTE0NDExMDk1MTM1YWY2MjM1Yjg=', '2015-09-01', NULL, 'user3', 'en_US', 'iPhone 6 plus', 'iOS 8.1', '777'),
(4, '126', 'MTI2LTE0NDExMDk1Mzk5OTNkYjcxYjA=', '2015-09-01', NULL, 'user4', 'en_US', 'iPhone 6 plus', 'iOS 8.1', '888'),
(5, '127', 'MTI3LTE0NDExMDk1NzBhY2IyNGZhMjA=', '2015-09-01', NULL, 'user5', 'en_US', 'iPhone 6 plus', 'iOS 8.1', '999'),
(6, '128', 'MTI4LTE0NDExMDk1ODcxMWI1NmRjNGE=', '2015-09-01', NULL, 'user6', 'en_US', 'iPhone 6 plus', 'iOS 8.1', '10000'),
(7, '129', 'MTI5LTE0NDExMDk2MDY1NTFiOGRlYjg=', '2015-09-01', NULL, 'user7', 'en_US', 'iPhone 6 plus', 'iOS 8.1', '10001');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `friends`
--
ALTER TABLE `friends`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `scores`
--
ALTER TABLE `scores`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `friends`
--
ALTER TABLE `friends`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `scores`
--
ALTER TABLE `scores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
