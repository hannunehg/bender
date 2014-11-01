-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 31, 2014 at 10:10 AM
-- Server version: 5.1.30-community
-- PHP Version: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `alba`
--
CREATE DATABASE IF NOT EXISTS `alba` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `alba`;

-- --------------------------------------------------------

--
-- Table structure for table `pieces`
--

CREATE TABLE IF NOT EXISTS `pieces` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dimension` int(11) NOT NULL,
  `angle` int(11) NOT NULL,
  `seq_number` int(11) NOT NULL,
  `unit_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `unit_id_fk` (`unit_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=62 ;

--
-- Dumping data for table `pieces`
--

INSERT INTO `pieces` (`id`, `dimension`, `angle`, `seq_number`, `unit_id`) VALUES
(56, 3, 3, 2, 103),
(53, 2, 2, 1, 102),
(52, 1, 1, 0, 102),
(55, 2, 2, 1, 103),
(54, 1, 1, 0, 103),
(51, 2, 2, 1, 101),
(50, 1, 1, 0, 101),
(44, 1, 1, 0, 98),
(45, 2, 2, 1, 98),
(46, 3, 3, 2, 98),
(47, 4, 4, 3, 98),
(48, 5, 5, 4, 98),
(49, 6, 6, 5, 98),
(57, 4, 4, 3, 103),
(58, 1, 1, 0, 104),
(59, 2, 2, 1, 104),
(60, 3, 3, 2, 104),
(61, 4, 4, 3, 104);

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE IF NOT EXISTS `units` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `unit_name` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=105 ;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`id`, `unit_name`) VALUES
(99, 'Ø´Ø³Ø´Ø´Ø³'),
(103, 'Ø¨Ø¨Ø¨Ø¨Ø¨'),
(98, 'BBB'),
(100, 'Ø±Ø¶Ø§ Ø´Ù…Ø§Ø³Ù†Ù‡'),
(101, 'BBB'),
(102, 'Ù…Ù„Ø§Ùƒ'),
(104, 'Ø§Ø®ØªØ¨Ø§Ø±');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
