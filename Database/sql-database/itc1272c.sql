-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 03, 2024 at 04:11 PM
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
-- Database: `itc1272c`
--

-- --------------------------------------------------------

--
-- Table structure for table `tblaccounts`
--

CREATE TABLE `tblaccounts` (
  `username` varchar(50) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` varchar(50) NOT NULL,
  `usertype` varchar(20) NOT NULL,
  `userstatus` varchar(20) NOT NULL,
  `createdby` varchar(50) NOT NULL,
  `datecreated` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblaccounts`
--

INSERT INTO `tblaccounts` (`username`, `email`, `password`, `usertype`, `userstatus`, `createdby`, `datecreated`) VALUES
('21-00986', NULL, 'arellano', 'STUDENT', 'ACTIVE', 'admin', '05/02/2024'),
('22-00987', NULL, '1234', 'STUDENT', 'ACTIVE', 'admin', '05/01/2024'),
('22-00999', NULL, '123456', 'STUDENT', 'ACTIVE', 'admin', '05/01/2024'),
('admin', NULL, '1234', 'ADMINISTRATOR', 'ACTIVE', 'admin', '02/17/2024'),
('registrar', NULL, '12345', 'REGISTRAR', 'ACTIVE', 'admin', '05/01/2024');

-- --------------------------------------------------------

--
-- Table structure for table `tblgrades`
--

CREATE TABLE `tblgrades` (
  `studentnumber` varchar(50) NOT NULL,
  `code` varchar(50) NOT NULL,
  `grade` varchar(20) NOT NULL,
  `encodedby` varchar(20) NOT NULL,
  `dateencoded` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblgrades`
--

INSERT INTO `tblgrades` (`studentnumber`, `code`, `grade`, `encodedby`, `dateencoded`) VALUES
('21-00986', 'GCAS 05', '1.25', 'admin', '05/02/2024'),
('21-00986', 'GCAS123', '1.75', 'admin', '05/02/2024'),
('22-00987', 'ITC110', '1.00', 'admin', '05/01/2024'),
('22-00987', 'ITC111', '1.00', 'admin', '05/01/2024');

-- --------------------------------------------------------

--
-- Table structure for table `tbllogs`
--

CREATE TABLE `tbllogs` (
  `datelog` varchar(50) NOT NULL,
  `timelog` varchar(50) NOT NULL,
  `action` varchar(20) NOT NULL,
  `module` varchar(20) NOT NULL,
  `ID` varchar(20) NOT NULL,
  `performedby` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblstudents`
--

CREATE TABLE `tblstudents` (
  `studentnumber` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `middlename` varchar(50) NOT NULL,
  `course` varchar(50) NOT NULL,
  `yearlevel` varchar(20) NOT NULL,
  `createdby` varchar(50) NOT NULL,
  `datecreated` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblstudents`
--

INSERT INTO `tblstudents` (`studentnumber`, `lastname`, `firstname`, `middlename`, `course`, `yearlevel`, `createdby`, `datecreated`) VALUES
('21-00986', 'Tomas', 'Carl', 'Cruz', 'BSIT', '3rd', 'admin', '05/02/2024'),
('22-00987', 'Romano', 'Jhun Mark', 'Magalang', 'BSCS', '2nd', 'admin', '05/01/2024'),
('22-00999', 'Tomas', 'james', 'Mo', 'BSCS', '2nd', 'admin', '05/01/2024');

-- --------------------------------------------------------

--
-- Table structure for table `tblsubjects`
--

CREATE TABLE `tblsubjects` (
  `code` varchar(50) NOT NULL,
  `description` varchar(50) NOT NULL,
  `unit` varchar(5) NOT NULL,
  `course` varchar(20) NOT NULL,
  `prerequisite1` varchar(20) NOT NULL,
  `prerequisite2` varchar(20) NOT NULL,
  `prerequisite3` varchar(20) NOT NULL,
  `createdby` varchar(20) NOT NULL,
  `datecreated` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblsubjects`
--

INSERT INTO `tblsubjects` (`code`, `description`, `unit`, `course`, `prerequisite1`, `prerequisite2`, `prerequisite3`, `createdby`, `datecreated`) VALUES
('CS123', 'qweqweqweqwe', '3 uni', 'BSIT', 'GCAS123', '', '', 'admin', '05/02/2024'),
('GCAS 05', 'afsawdasdw', '3 uni', 'BSIT', '', '', '', 'admin', '05/01/2024'),
('GCAS123', 'diskarte o diploma ', '3 uni', 'BSIT', 'GCAS 05', '', '', 'admin', '05/01/2024'),
('ITC110', 'Introduction to Computing ', '3 uni', 'BSCS', '', '', '', 'admin', '05/01/2024'),
('ITC111', 'Computer Programming 1', '3 uni', 'BSCS', '', '', '', 'admin', '05/01/2024'),
('ITC112', 'Intro to Graphics and Design', '3 uni', 'BSCS', '', '', '', 'admin', '05/01/2024'),
('ITC120', 'Computer Programming 2', '3 uni', 'BSCS', 'ITC111', '', '', 'admin', '05/01/2024'),
('ITC121', 'Operating Systems', '3 uni', 'BSCS', 'ITC110', 'ITC111', '', 'admin', '05/01/2024');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tblaccounts`
--
ALTER TABLE `tblaccounts`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `tblgrades`
--
ALTER TABLE `tblgrades`
  ADD PRIMARY KEY (`studentnumber`,`code`);

--
-- Indexes for table `tblstudents`
--
ALTER TABLE `tblstudents`
  ADD PRIMARY KEY (`studentnumber`);

--
-- Indexes for table `tblsubjects`
--
ALTER TABLE `tblsubjects`
  ADD PRIMARY KEY (`code`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
