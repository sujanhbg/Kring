-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/First
--
-- Host: 127.0.0.1
-- Generation Time: Jan 24, 2021 at 10:20 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 8.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bdenglish4exam`
--

-- --------------------------------------------------------

--
-- Table structure for table `configs`
--

CREATE TABLE `configs` (
  `ID` int(11) NOT NULL,
  `name` varchar(55) NOT NULL,
  `value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `configs`
--

INSERT INTO `configs` (`ID`, `name`, `value`) VALUES
(1, 'ProjectName', 'BDEnglish4Exam'),
(2, 'OrgName', 'BDEnglish4Exam Publications Ltd.');

-- --------------------------------------------------------

--
-- Table structure for table `eng_level`
--

CREATE TABLE `eng_level` (
  `ID` int(11) NOT NULL,
  `level` varchar(255) NOT NULL,
  `level_desc` varchar(255) DEFAULT NULL,
  `CEFR_Level` varchar(5) NOT NULL,
  `level_icon` varchar(111) DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0,
  `published` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `eng_level`
--

INSERT INTO `eng_level` (`ID`, `level`, `level_desc`, `CEFR_Level`, `level_icon`, `deleted`, `published`) VALUES
(1, 'Beginner', 'I do not speak any English.', '', NULL, 0, 1),
(2, 'Elementary', 'I can say and understand a few things in English.', 'A1/2', NULL, 0, 1),
(3, 'Pre-intermediate', 'I can communicate simply and understand in familiar situations but only with some difficulty.', 'A2', NULL, 0, 1),
(4, 'Low Intermediate', 'I can make simple sentences and can understand the main points of a conversation but need much more vocabulary.', 'B1', NULL, 0, 1),
(5, 'Intermediate', 'I can speak and understand reasonably well and can use basic tenses but have problems with more complex grammar and vocabulary.', 'B1', NULL, 0, 1),
(6, 'Upper Intermediate', 'I can communicate without much difficulty but still make quite a lot of mistakes and misunderstand sometimes.', 'B2', NULL, 0, 1),
(7, 'Pre-advanced', 'I speak and understand well but still make mistakes and sometimes people do not understand me clearly', 'C1', NULL, 0, 1),
(8, 'Advanced', 'I speak and understand very well but sometimes have problems with unfamiliar situations and vocabulary.', 'C2', NULL, 0, 1),
(9, 'Very Advanced', 'I speak and understand English completely fluently.', 'C2', NULL, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `ID` int(11) NOT NULL COMMENT 'ID of user',
  `firstname` varchar(40) DEFAULT NULL COMMENT 'First name of user',
  `lastname` varchar(40) DEFAULT NULL COMMENT 'Last name of user',
  `password` varchar(23) NOT NULL DEFAULT '1111' COMMENT 'Password',
  `email` varchar(40) NOT NULL COMMENT 'Email of user',
  `createdate` date NOT NULL COMMENT 'Date of creation',
  `role` varchar(20) NOT NULL COMMENT 'Role of user',
  `active` int(11) NOT NULL DEFAULT 1 COMMENT '1=active 0=inactive',
  `create_by` int(11) DEFAULT NULL,
  `create_from` varchar(255) NOT NULL,
  `active_code` varchar(255) DEFAULT NULL,
  `photo` varchar(222) DEFAULT 'imgs/NOPHOTO.jpg',
  `cell` text DEFAULT NULL,
  `username` varchar(111) DEFAULT NULL,
  `gender` varchar(8) DEFAULT NULL,
  `nationality` varchar(22) DEFAULT NULL,
  `telephone` varchar(32) DEFAULT NULL,
  `streetaddr` varchar(111) DEFAULT NULL,
  `city` varchar(111) DEFAULT NULL,
  `region` varchar(22) DEFAULT NULL,
  `country` varchar(32) DEFAULT NULL,
  `postalcode` int(11) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `deleted` int(11) NOT NULL DEFAULT 0,
  `cell_verified` int(11) NOT NULL DEFAULT 0,
  `cellotp` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`ID`, `firstname`, `lastname`, `password`, `email`, `createdate`, `role`, `active`, `create_by`, `create_from`, `active_code`, `photo`, `cell`, `username`, `gender`, `nationality`, `telephone`, `streetaddr`, `city`, `region`, `country`, `postalcode`, `birthdate`, `deleted`, `cell_verified`, `cellotp`) VALUES
(10023601, 'Sujan', 'C.Barty', '3d1a05c3d0bfe79d4688a22', 'sjnx@outlook.com', '2016-01-16', '22', 1, 10023601, '127.0.0.1', 'aa', 'thumbs/members/10023601/5ef3213be8b45950da3c00b3fffce38d.jpg', '8801713892750', 'sujan', '1', 'BD', '8544558899', 'M-25/3, 1 No building', 'Mirpur-14', 'Dhaka', 'Bangladesh', 1216, '1984-11-19', 0, 0, NULL),
(10023663, 'Alhelal', 'Admin', '0c557ad5b3a65fd81ebe998', 'alattar@kalni.net', '2020-09-04', '23', 1, 10023601, '', NULL, 'imgs/NOPHOTO.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL),
(10023664, 'Sujan', 'Staff Mode', '2c6d139cf26f895a9761a9e', 'sujan@kalni.net', '2020-09-05', '44', 1, 10023601, '103.92.204.26', '0860665057851452645c01d75dd2565f', 'imgs/NOPHOTO.jpg', '0171998877720', '08606650', 'Male', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_loginerr`
--

CREATE TABLE `user_loginerr` (
  `ID` int(11) NOT NULL,
  `usernm` varchar(100) NOT NULL,
  `pass` varchar(50) NOT NULL,
  `time` datetime NOT NULL,
  `ip` varchar(22) NOT NULL,
  `otherinfo` varchar(255) NOT NULL,
  `sess` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_loginerr`
--

INSERT INTO `user_loginerr` (`ID`, `usernm`, `pass`, `time`, `ip`, `otherinfo`, `sess`) VALUES
(7, 'sjnx@outlook.com', '1234321', '2021-01-24 17:26:32', '127.0.0.1', 'Host- mcpos.local', '1hd4s9rsemqtgd0nicr01vh8ng'),
(8, '', '', '2021-01-24 17:27:25', '127.0.0.1', 'Host- mcpos.local', '1hd4s9rsemqtgd0nicr01vh8ng'),
(9, '', '', '2021-01-24 17:27:26', '127.0.0.1', 'Host- mcpos.local', '1hd4s9rsemqtgd0nicr01vh8ng'),
(10, '', '', '2021-01-24 17:27:27', '127.0.0.1', 'Host- mcpos.local', '1hd4s9rsemqtgd0nicr01vh8ng'),
(11, '', '', '2021-01-24 17:27:29', '127.0.0.1', 'Host- mcpos.local', '1hd4s9rsemqtgd0nicr01vh8ng'),
(12, '', '', '2021-01-24 17:27:37', '127.0.0.1', 'Host- mcpos.local', '1hd4s9rsemqtgd0nicr01vh8ng'),
(13, '', '', '2021-01-24 17:27:39', '127.0.0.1', 'Host- mcpos.local', '1hd4s9rsemqtgd0nicr01vh8ng'),
(14, '', '', '2021-01-24 17:27:39', '127.0.0.1', 'Host- mcpos.local', '1hd4s9rsemqtgd0nicr01vh8ng'),
(15, '', '', '2021-01-24 17:31:52', '127.0.0.1', 'Host- mcpos.local', '1hd4s9rsemqtgd0nicr01vh8ng'),
(16, '', '', '2021-01-24 17:32:00', '127.0.0.1', 'Host- mcpos.local', '1hd4s9rsemqtgd0nicr01vh8ng'),
(17, '', '', '2021-01-24 17:32:02', '127.0.0.1', 'Host- mcpos.local', '1hd4s9rsemqtgd0nicr01vh8ng'),
(18, '', '', '2021-01-24 17:32:02', '127.0.0.1', 'Host- mcpos.local', '1hd4s9rsemqtgd0nicr01vh8ng'),
(19, '', '', '2021-01-24 17:32:03', '127.0.0.1', 'Host- mcpos.local', '1hd4s9rsemqtgd0nicr01vh8ng'),
(20, '', '', '2021-01-24 17:32:59', '127.0.0.1', 'Host- mcpos.local', '1hd4s9rsemqtgd0nicr01vh8ng'),
(21, '', '', '2021-01-24 17:33:00', '127.0.0.1', 'Host- mcpos.local', '1hd4s9rsemqtgd0nicr01vh8ng'),
(22, '', '', '2021-01-24 17:33:55', '127.0.0.1', 'Host- mcpos.local', '1hd4s9rsemqtgd0nicr01vh8ng'),
(23, '', '', '2021-01-24 17:34:01', '127.0.0.1', 'Host- mcpos.local', '1hd4s9rsemqtgd0nicr01vh8ng'),
(24, '', '', '2021-01-24 17:34:05', '127.0.0.1', 'Host- mcpos.local', '1hd4s9rsemqtgd0nicr01vh8ng'),
(25, '', '', '2021-01-24 17:34:10', '127.0.0.1', 'Host- mcpos.local', '1hd4s9rsemqtgd0nicr01vh8ng'),
(26, 'sjnx@outlook.com', 'sdff', '2021-01-24 17:34:38', '127.0.0.1', 'Host- mcpos.local', '1hd4s9rsemqtgd0nicr01vh8ng'),
(27, 'sjnx@outlook.com', 'asdfg', '2021-01-24 17:34:43', '127.0.0.1', 'Host- mcpos.local', '1hd4s9rsemqtgd0nicr01vh8ng'),
(28, 'sjnx@outlook.com', '', '2021-01-24 17:34:52', '127.0.0.1', 'Host- mcpos.local', '1hd4s9rsemqtgd0nicr01vh8ng'),
(29, '', '', '2021-01-24 17:37:30', '127.0.0.1', 'Host- mcpos.local', '1hd4s9rsemqtgd0nicr01vh8ng'),
(30, '', '', '2021-01-24 17:37:31', '127.0.0.1', 'Host- mcpos.local', '1hd4s9rsemqtgd0nicr01vh8ng'),
(31, '', '', '2021-01-24 17:37:32', '127.0.0.1', 'Host- mcpos.local', '1hd4s9rsemqtgd0nicr01vh8ng'),
(32, '', '', '2021-01-24 17:38:02', '127.0.0.1', 'Host- mcpos.local', '1hd4s9rsemqtgd0nicr01vh8ng'),
(33, '', '', '2021-01-24 17:38:13', '127.0.0.1', 'Host- mcpos.local', '1hd4s9rsemqtgd0nicr01vh8ng'),
(34, '', '', '2021-01-24 17:38:16', '127.0.0.1', 'Host- mcpos.local', '1hd4s9rsemqtgd0nicr01vh8ng'),
(35, '', '', '2021-01-24 17:38:17', '127.0.0.1', 'Host- mcpos.local', '1hd4s9rsemqtgd0nicr01vh8ng'),
(36, '', '', '2021-01-24 17:39:34', '127.0.0.1', 'Host- mcpos.local', '1hd4s9rsemqtgd0nicr01vh8ng'),
(37, '', '', '2021-01-24 17:40:10', '127.0.0.1', 'Host- mcpos.local', '1hd4s9rsemqtgd0nicr01vh8ng'),
(38, '', '', '2021-01-24 17:40:13', '127.0.0.1', 'Host- mcpos.local', '1hd4s9rsemqtgd0nicr01vh8ng'),
(39, '', '', '2021-01-24 17:40:14', '127.0.0.1', 'Host- mcpos.local', '1hd4s9rsemqtgd0nicr01vh8ng'),
(40, '', '', '2021-01-24 17:40:16', '127.0.0.1', 'Host- mcpos.local', '1hd4s9rsemqtgd0nicr01vh8ng'),
(41, '', '', '2021-01-24 17:40:17', '127.0.0.1', 'Host- mcpos.local', '1hd4s9rsemqtgd0nicr01vh8ng'),
(42, '', '', '2021-01-24 19:28:30', '127.0.0.1', 'Host- mcpos.local', '1hd4s9rsemqtgd0nicr01vh8ng'),
(43, 'sjnx@outlook.com', 'sjnx@outlook.com', '2021-01-24 20:55:10', '127.0.0.1', 'Host- mcpos.local', '1hd4s9rsemqtgd0nicr01vh8ng'),
(44, '', '', '2021-01-24 20:56:11', '127.0.0.1', 'Host- mcpos.local', '1hd4s9rsemqtgd0nicr01vh8ng'),
(45, '', '', '2021-01-24 20:56:13', '127.0.0.1', 'Host- mcpos.local', '1hd4s9rsemqtgd0nicr01vh8ng'),
(46, '', '', '2021-01-24 20:56:14', '127.0.0.1', 'Host- mcpos.local', '1hd4s9rsemqtgd0nicr01vh8ng'),
(47, '', '', '2021-01-24 20:56:15', '127.0.0.1', 'Host- mcpos.local', '1hd4s9rsemqtgd0nicr01vh8ng'),
(48, '', '', '2021-01-24 20:56:15', '127.0.0.1', 'Host- mcpos.local', '1hd4s9rsemqtgd0nicr01vh8ng'),
(49, '', '', '2021-01-24 20:56:16', '127.0.0.1', 'Host- mcpos.local', '1hd4s9rsemqtgd0nicr01vh8ng'),
(50, '', '', '2021-01-24 20:56:17', '127.0.0.1', 'Host- mcpos.local', '1hd4s9rsemqtgd0nicr01vh8ng'),
(51, '', '', '2021-01-24 20:58:12', '127.0.0.1', 'Host- mcpos.local', '1hd4s9rsemqtgd0nicr01vh8ng'),
(52, '', '', '2021-01-24 20:58:30', '127.0.0.1', 'Host- mcpos.local', '1hd4s9rsemqtgd0nicr01vh8ng'),
(53, '', '', '2021-01-24 20:59:30', '127.0.0.1', 'Host- mcpos.local', '1hd4s9rsemqtgd0nicr01vh8ng'),
(54, '', '', '2021-01-24 21:03:15', '127.0.0.1', 'Host- mcpos.local', '1hd4s9rsemqtgd0nicr01vh8ng'),
(55, '', '', '2021-01-24 21:03:39', '127.0.0.1', 'Host- mcpos.local', '1hd4s9rsemqtgd0nicr01vh8ng'),
(56, '', '', '2021-01-24 21:03:48', '127.0.0.1', 'Host- mcpos.local', '1hd4s9rsemqtgd0nicr01vh8ng'),
(57, '', '', '2021-01-24 21:04:03', '127.0.0.1', 'Host- mcpos.local', '1hd4s9rsemqtgd0nicr01vh8ng'),
(58, '', '', '2021-01-24 21:07:11', '127.0.0.1', 'Host- mcpos.local', '1hd4s9rsemqtgd0nicr01vh8ng'),
(59, '', '', '2021-01-24 21:07:15', '127.0.0.1', 'Host- mcpos.local', '1hd4s9rsemqtgd0nicr01vh8ng'),
(60, '', '', '2021-01-24 21:07:17', '127.0.0.1', 'Host- mcpos.local', '1hd4s9rsemqtgd0nicr01vh8ng'),
(61, '', '', '2021-01-24 21:08:15', '127.0.0.1', 'Host- mcpos.local', '1hd4s9rsemqtgd0nicr01vh8ng'),
(62, '', '', '2021-01-24 21:08:33', '127.0.0.1', 'Host- mcpos.local', '1hd4s9rsemqtgd0nicr01vh8ng'),
(63, '', '', '2021-01-24 21:09:00', '127.0.0.1', 'Host- mcpos.local', '1hd4s9rsemqtgd0nicr01vh8ng'),
(64, '', '', '2021-01-24 21:09:34', '127.0.0.1', 'Host- mcpos.local', '1hd4s9rsemqtgd0nicr01vh8ng'),
(65, 'sjnx@outlook.com', 'errrrr', '2021-01-24 21:18:26', '127.0.0.1', 'Host- mcpos.local', '1hd4s9rsemqtgd0nicr01vh8ng');

-- --------------------------------------------------------

--
-- Table structure for table `user_login_history`
--

CREATE TABLE `user_login_history` (
  `ID` int(11) NOT NULL,
  `UID` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `IP` varchar(22) NOT NULL,
  `otherdtl` varchar(222) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_login_history`
--

INSERT INTO `user_login_history` (`ID`, `UID`, `date`, `IP`, `otherdtl`) VALUES
(1, 10023601, '2021-01-24 21:20:39', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.141 Safari/537.36'),
(2, 10023601, '2021-01-25 02:40:00', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.141 Safari/537.36'),
(3, 10023601, '2021-01-25 02:42:10', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.141 Safari/537.36'),
(4, 10023601, '2021-01-25 02:45:09', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.141 Safari/537.36'),
(5, 10023601, '2021-01-25 02:45:48', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.141 Safari/537.36'),
(6, 10023601, '2021-01-25 02:46:02', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.141 Safari/537.36'),
(7, 10023601, '2021-01-25 03:17:10', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.141 Safari/537.36');

-- --------------------------------------------------------

--
-- Table structure for table `usr_block`
--

CREATE TABLE `usr_block` (
  `ID` int(11) NOT NULL,
  `email` varchar(111) NOT NULL,
  `block` int(11) NOT NULL DEFAULT 1,
  `block_time` datetime NOT NULL,
  `blockfor` varchar(255) NOT NULL COMMENT 'Block time count___ * for unlimited time..... or define it as minite',
  `comment` varchar(255) DEFAULT NULL,
  `unblocktime` datetime NOT NULL COMMENT 'set time for limit time block',
  `unblockcode` varchar(32) NOT NULL,
  `unblock` int(11) NOT NULL DEFAULT 0,
  `unblockby` int(11) NOT NULL,
  `menu_unblc_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `configs`
--
ALTER TABLE `configs`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `eng_level`
--
ALTER TABLE `eng_level`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `user_loginerr`
--
ALTER TABLE `user_loginerr`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `user_login_history`
--
ALTER TABLE `user_login_history`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `usr_block`
--
ALTER TABLE `usr_block`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `configs`
--
ALTER TABLE `configs`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `eng_level`
--
ALTER TABLE `eng_level`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID of user', AUTO_INCREMENT=10023665;

--
-- AUTO_INCREMENT for table `user_loginerr`
--
ALTER TABLE `user_loginerr`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `user_login_history`
--
ALTER TABLE `user_login_history`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
