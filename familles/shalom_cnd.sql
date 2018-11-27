-- phpMyAdmin SQL Dump
-- version 4.4.13.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 30, 2015 at 12:38 AM
-- Server version: 5.6.27-0ubuntu1
-- PHP Version: 5.6.11-1ubuntu3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shalom_cnd`
--

-- --------------------------------------------------------

--
-- Table structure for table `dependents`
--

DROP TABLE IF EXISTS `dependents`;
CREATE TABLE IF NOT EXISTS `dependents` (
  `pid` int(11) NOT NULL AUTO_INCREMENT,
  `relation` varchar(255) DEFAULT NULL,
  `nom` varchar(255) DEFAULT NULL,
  `prenom` varchar(255) DEFAULT NULL,
  `sexe` char(1) DEFAULT NULL,
  `age` varchar(10) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `nip` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dependents`
--


-- --------------------------------------------------------

--
-- Table structure for table `families`
--

DROP TABLE IF EXISTS `families`;
CREATE TABLE IF NOT EXISTS `families` (
  `fnum` int(11) NOT NULL,
  `famno` int(11) NOT NULL,
  `nip` int(11) NOT NULL,
  `number` varchar(6) CHARACTER SET utf8 DEFAULT NULL,
  `street` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `appt` varchar(5) CHARACTER SET utf8 DEFAULT NULL,
  `code` varchar(7) CHARACTER SET utf8 DEFAULT NULL,
  `tel_h` varchar(15) CHARACTER SET utf8 DEFAULT NULL,
  `tel_alt` varchar(40) CHARACTER SET utf8 DEFAULT NULL,
  `note` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  `foyer` varchar(15) DEFAULT NULL,
  `montant` int(11) DEFAULT NULL,
  `location` varchar(30) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=71 DEFAULT CHARSET=latin1;


-- --------------------------------------------------------

--
-- Table structure for table `person_category`
--

DROP TABLE IF EXISTS `person_category`;
CREATE TABLE IF NOT EXISTS `person_category` (
  `cid` int(4) NOT NULL,
  `category` enum('Demandeur','Conjoint','Enfant','Autre') COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `person_category`
--

INSERT INTO `person_category` (`cid`, `category`) VALUES
(1, 'Demandeur'),
(2, 'Conjoint'),
(3, 'Enfant'),
(4, 'Autre');

--
-- Indexes for dumped tables
--
--
-- Table structure for table `groceries`
--

DROP TABLE IF EXISTS `groceries`;
CREATE TABLE IF NOT EXISTS `groceries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `foyer` int(3) NOT NULL,
  `item1` varchar(255) NOT NULL,
  `item2` varchar(255) NOT NULL 
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Indexes for table `groceries`
--
ALTER TABLE `groceries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dependents`
--
ALTER TABLE `dependents`
  ADD PRIMARY KEY (`pid`),
  ADD KEY `nip` (`nip`);

--
-- Indexes for table `families`
--
ALTER TABLE `families`
  ADD PRIMARY KEY (`nip`),
  ADD UNIQUE KEY `fnum` (`fnum`,`nip`,`number`);

--
-- Indexes for table `person_category`
--
ALTER TABLE `person_category`
  ADD UNIQUE KEY `cid` (`cid`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
