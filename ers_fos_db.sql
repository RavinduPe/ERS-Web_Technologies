-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql309.infinityfree.com
-- Generation Time: Nov 09, 2023 at 03:30 AM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_35002363_ers_fos_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `email` varchar(100) NOT NULL,
  `password` varchar(155) NOT NULL,
  `name` varchar(25) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'Student',
  `status` varchar(100) NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`email`, `password`, `name`, `role`, `status`) VALUES
('admin_master@nexus.com', '$2y$10$DWDHkfoxdQr/RHb7sdiaMeIy./lOZmFBQcOZaTedLFxTTB1xu.TEi', 'Master', 'Admin_Master', 'active'),
('asintha1997@gmail.com', '$2y$10$pIZwkWacf0tf2p0UxzazRuPlRM.rvB4qKHdFSJ.jaxZqf0lTlt1SS', 'Asintha', 'Admin_Student', 'active'),
('stud_admin1@nexus.com', '$2y$10$IUzrF9GhBdTzDXXbmxA19.XZuxKo9le3hETfrRsqKG35goK4w1npS', 'Student admin 1', 'Admin_Student', 'active'),
('subj_admin1@nexus.com', '$2y$10$6IniUusMCkDLxZFhTVWyL.Nk0BBkFuzzLUzSCdFOqy32NexOPRNvi', 'Visithan', 'Admin_Subject', 'active'),
('subj_admin2@nexus.com', '$2y$10$7v728eNqfjD61XwpVjLwvO/o4cNMvUmDW7QeqluimnhJsGNSzaqt.', 'Shankar', 'Admin_Subject', 'active'),
('viththagan@nexus.com', '$2y$10$HrF7DQS3U0xzZ5Xaom37LO4EAWXBK9zhhPBOsD.YqeIMvE4.kHgyG', 'viththagan', 'Admin_Subject', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `admin_details`
--

CREATE TABLE `admin_details` (
  `adminId` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `title` varchar(10) NOT NULL,
  `fullName` varchar(255) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `mobileNo` int(10) DEFAULT NULL,
  `profile_img` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin_details`
--

INSERT INTO `admin_details` (`adminId`, `email`, `title`, `fullName`, `department`, `mobileNo`, `profile_img`) VALUES
(1, 'admin_master@nexus.com', 'Mr', 'Master', 'FoS', 771234567, '1.jpg'),
(2, 'stud_admin1@nexus.com', 'Mr', 'Thileepan T', 'CSC', 2147483647, 'blankProfile.png'),
(3, 'subj_admin1@nexus.com', 'Mr', 'Visithan', 'DCS', 774589852, '3.jpg'),
(4, 'viththagan@nexus.com', '', NULL, NULL, NULL, NULL),
(5, 'subj_admin2@nexus.com', '', '', 'Bio', NULL, NULL),
(6, 'asintha1997@gmail.com', '', 'Asintha udeshitha', 'Student', 703833130, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `combination`
--

CREATE TABLE `combination` (
  `combinationID` int(11) NOT NULL,
  `combinationName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `combination`
--

INSERT INTO `combination` (`combinationID`, `combinationName`) VALUES
(1, 'CSC - Direct Intake'),
(2, 'BOT, ZOO, FSC'),
(3, 'CHE, BOT, FSC'),
(4, 'CHE, BOT, ZOO'),
(5, 'CHE, ZOO, FSC'),
(6, 'CHE, PMM, AMM'),
(7, 'CSC, AMM, CHE'),
(8, 'CSC, AMM, PHY'),
(9, 'CSC, AMM, STA'),
(10, 'CSC, PMM, AMM'),
(11, 'CSC, PMM, CHE'),
(12, 'CSC, STA, PMM'),
(13, 'PHY, PMM, AMM');

-- --------------------------------------------------------

--
-- Table structure for table `combination_subjects`
--

CREATE TABLE `combination_subjects` (
  `combinationID` int(11) NOT NULL,
  `subject` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `combination_subjects`
--

INSERT INTO `combination_subjects` (`combinationID`, `subject`) VALUES
(1, 'CSC - Direct Intake'),
(2, 'BOT'),
(2, 'FSC'),
(2, 'ZOO'),
(3, 'BOT'),
(3, 'CHE'),
(3, 'FSC'),
(4, 'BOT'),
(4, 'CHE'),
(4, 'ZOO'),
(5, 'CHE'),
(5, 'FSC'),
(5, 'ZOO'),
(6, 'AMM'),
(6, 'CHE'),
(6, 'PMM'),
(7, 'AMM'),
(7, 'CHE'),
(7, 'CSC'),
(8, 'AMM'),
(8, 'CSC'),
(8, 'PHY'),
(9, 'AMM'),
(9, 'CSC'),
(9, 'STA'),
(10, 'AMM'),
(10, 'CSC'),
(10, 'PMM'),
(11, 'CHE'),
(11, 'CSC'),
(11, 'PMM'),
(12, 'CSC'),
(12, 'PMM'),
(12, 'STA'),
(13, 'AMM'),
(13, 'PHY'),
(13, 'PMM');

-- --------------------------------------------------------

--
-- Table structure for table `exam_reg`
--

CREATE TABLE `exam_reg` (
  `exam_id` int(11) NOT NULL,
  `academic_year` varchar(10) NOT NULL,
  `semester` enum('1','2') NOT NULL,
  `status` enum('draft','registration','closed','hidden') DEFAULT 'draft',
  `closing_date` date NOT NULL DEFAULT '2020-01-01',
  `date_created` date DEFAULT '2020-01-01'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `exam_reg`
--

INSERT INTO `exam_reg` (`exam_id`, `academic_year`, `semester`, `status`, `closing_date`, `date_created`) VALUES
(1, '2020', '1', 'hidden', '2023-08-28', '2023-08-28'),
(2, '2020', '2', 'registration', '2023-09-14', '2023-09-01');

-- --------------------------------------------------------

--
-- Table structure for table `exam_reg_excep`
--

CREATE TABLE `exam_reg_excep` (
  `exam_id` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `type` enum('proper','repeat') NOT NULL,
  `regNo` varchar(12) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `exam_reg_excep`
--

INSERT INTO `exam_reg_excep` (`exam_id`, `level`, `type`, `regNo`) VALUES
(2, 1, 'proper', '2020/CSC/007'),
(2, 2, 'proper', '2020/CSC/007'),
(2, 2, 'proper', '2020/CSC/051'),
(2, 2, 'proper', '2020/CSC/074'),
(2, 2, 'repeat', '2020/CSC/074'),
(2, 3, 'proper', '2020/CSC/074');

-- --------------------------------------------------------

--
-- Table structure for table `exam_stud_index`
--

CREATE TABLE `exam_stud_index` (
  `exam_id` int(11) NOT NULL,
  `regNo` varchar(12) NOT NULL,
  `indexNo` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `exam_stud_index`
--

INSERT INTO `exam_stud_index` (`exam_id`, `regNo`, `indexNo`) VALUES
(2, '2020/CSC/007', 'S11228'),
(2, '2020/CSC/033', 'S11251'),
(2, '2020/CSC/051', 'S11267'),
(2, '2020/CSC/074', 'S11287'),
(2, '2020/SB/001', 'S11293'),
(2, '2020/SB/002', 'S11294'),
(2, '2020/SB/005', 'S11296'),
(2, '2020/SB/006', 'S11297'),
(2, '2020/SB/008', 'S11298'),
(2, '2020/SB/010', 'S11299'),
(2, '2020/SB/012', 'S11301'),
(2, '2020/SB/014', 'S11303'),
(2, '2020/SB/020', 'S11309'),
(2, '2020/SB/091', 'S11373'),
(2, '2020/SP/007', 'S11032'),
(2, '2020/SP/009', 'S11034'),
(2, '2020/SP/015', 'S11040'),
(2, '2020/SP/017', 'S11042'),
(2, '2020/SP/018', 'S11043'),
(2, '2020/SP/041', 'S11057'),
(2, '2020/SP/044', 'S11060'),
(2, '2020/SP/070', 'S11075'),
(2, '2020/SP/092', 'S11089'),
(2, '2020/SP/121', 'S11108'),
(2, '2020/SP/145', 'SP19030'),
(2, '2020/SP/170', 'S11145'),
(2, '2021/CSC/080', 'S11749');

-- --------------------------------------------------------

--
-- Table structure for table `reg_units`
--

CREATE TABLE `reg_units` (
  `regId` int(11) NOT NULL,
  `exam_unit_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `reg_units`
--

INSERT INTO `reg_units` (`regId`, `exam_unit_id`) VALUES
(14, 2),
(14, 21),
(14, 24),
(14, 25),
(18, 48),
(18, 50),
(18, 53),
(18, 54),
(18, 55),
(18, 63),
(18, 64),
(18, 65),
(19, 48),
(19, 50),
(19, 53),
(19, 54),
(19, 55),
(19, 63),
(19, 64),
(19, 65),
(20, 48),
(20, 50),
(20, 58),
(20, 59),
(20, 63),
(20, 64),
(20, 65),
(21, 48),
(21, 50),
(21, 58),
(21, 59),
(21, 63),
(21, 64),
(21, 65),
(22, 48),
(22, 50),
(22, 58),
(22, 59),
(22, 63),
(22, 64),
(22, 65),
(23, 48),
(23, 50),
(23, 53),
(23, 54),
(23, 55),
(23, 58),
(23, 59),
(24, 48),
(24, 50),
(24, 53),
(24, 54),
(24, 55),
(24, 58),
(24, 59),
(25, 48),
(25, 50),
(25, 53),
(25, 54),
(25, 55),
(25, 58),
(25, 59),
(26, 53),
(26, 54),
(26, 55),
(26, 58),
(26, 59),
(26, 63),
(26, 64),
(26, 65),
(27, 53),
(27, 54),
(27, 55),
(27, 58),
(27, 59),
(27, 63),
(27, 64),
(27, 65),
(29, 48),
(29, 50),
(29, 76),
(29, 78),
(29, 81),
(29, 82),
(30, 48),
(30, 50),
(30, 76),
(30, 78),
(30, 81),
(30, 82),
(31, 43),
(31, 44),
(31, 45),
(31, 66),
(31, 69),
(31, 70),
(31, 81),
(31, 82),
(32, 43),
(32, 44),
(32, 45),
(32, 66),
(32, 69),
(32, 70),
(32, 81),
(32, 82),
(33, 43),
(33, 44),
(33, 45),
(33, 66),
(33, 69),
(33, 70),
(33, 81),
(33, 82),
(34, 43),
(34, 44),
(34, 45),
(34, 76),
(34, 78),
(34, 81),
(34, 82),
(35, 43),
(35, 44),
(35, 45),
(35, 76),
(35, 78),
(35, 81),
(35, 82),
(36, 43),
(36, 44),
(36, 45),
(36, 73),
(36, 74),
(36, 76),
(36, 78),
(37, 43),
(37, 44),
(37, 45),
(37, 73),
(37, 74),
(37, 76),
(37, 78),
(38, 43),
(38, 44),
(38, 45),
(38, 73),
(38, 74),
(38, 76),
(38, 78),
(39, 43),
(39, 44),
(39, 45),
(39, 73),
(39, 74),
(39, 76),
(39, 78),
(40, 43),
(40, 44),
(40, 45),
(40, 73),
(40, 74),
(40, 76),
(40, 78),
(42, 31),
(42, 32),
(42, 33),
(42, 38),
(42, 39),
(43, 31),
(43, 32),
(43, 33),
(43, 38),
(43, 39),
(43, 40),
(43, 41),
(44, 31),
(44, 32),
(44, 33),
(44, 38),
(44, 39),
(44, 40),
(44, 41),
(45, 31),
(45, 32),
(45, 33),
(45, 38),
(45, 39),
(45, 40),
(45, 41),
(46, 2),
(46, 21);

-- --------------------------------------------------------

--
-- Table structure for table `repeat_slips`
--

CREATE TABLE `repeat_slips` (
  `regId` int(11) NOT NULL,
  `payment_slip` varchar(50) NOT NULL,
  `payment_slip_status` enum('accepted','pending','rejected') NOT NULL DEFAULT 'pending',
  `senate_approval_letter` varchar(50) NOT NULL,
  `senate_approval_letter_status` enum('accepted','pending','rejected') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `repeat_slips`
--


-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `regNo` varchar(12) NOT NULL,
  `title` varchar(5) DEFAULT NULL,
  `nameWithInitial` varchar(60) DEFAULT NULL,
  `fullName` varchar(150) DEFAULT NULL,
  `district` varchar(30) DEFAULT NULL,
  `mobileNo` varchar(11) DEFAULT NULL,
  `landlineNo` varchar(11) DEFAULT NULL,
  `homeAddress` varchar(300) DEFAULT NULL,
  `addressInJaffna` varchar(300) DEFAULT NULL,
  `profile_img` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`regNo`, `title`, `nameWithInitial`, `fullName`, `district`, `mobileNo`, `landlineNo`, `homeAddress`, `addressInJaffna`, `profile_img`) VALUES
('2014/CSC/051', 'mr', 'test', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2018/SB/001', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2019/CSC/041', 'Mr', 'Saanusan', NULL, NULL, NULL, NULL, NULL, 'Kandaramadam', '2019CSC041.jpg'),
('2019/SP/178', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2020/CSC/007', 'Mr', 'C. R. B. Nilwakka', 'Chamod Rashmika Bandara Nilwakka', 'Kandy', '0772684933', '0779472689', 'No 4/56, Matale Rd, Wattegama.', 'Duvarakai, vamas lane, palali Rd, kondavil.', '2020CSC007.jpg'),
('2020/CSC/010', 'Ms', 'G.Dharshika', 'Dharshika Gnanaseelan', 'Kandy', '0767106659', '0812235149', '21/12B Riverdale Road, Anniwatta, Kandy.', '21/12B Riverdale Road, Anniwatta, Kandy.', 'blankProfile.png'),
('2020/CSC/017', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2020/CSC/027', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2020/CSC/028', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2020/CSC/033', 'Ms', 'M.Sankavi', 'Mohan Sankavi', 'Vavuniya', '0716041059', 'N/A', 'No 7, NHDA Quarters, SLRC Lane, Park Road, Vavuniya.', 'Infront of Technical College, Brown Road, Jaffna.', '2020CSC033.jpg'),
('2020/CSC/046', 'Mr', 'RPAU Karunarathna', 'Asintha  udeshitha', 'Kegalle', '0728581211', '1234567890', 'Andoluwa', 'Andoluwa', '2020CSC046.png'),
('2020/CSC/050', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2020/CSC/051', 'Mr', 'R.N.Viththagan', 'Roy Nesarajah Viththagan', 'Jaffna', '0771234567', '0123456789', 'Jaffna', 'Jaffna', '2020CSC051.jpg'),
('2020/CSC/052', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2020/CSC/055', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2020/CSC/057', 'Mr', 'S. Vithurshan', 'Sivakumar Vithurshan', 'Jaffna', '0123456789', '0123456789', 'kokuvil', 'kokuvil', NULL),
('2020/CSC/061', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2020/CSC/065', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2020/CSC/066', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2020/CSC/067', 'Mr', 'W.R.Deshitha', 'Wathukarage Ravidu deshitha', 'Ratnapura', '+9471918846', '-', 'Dumbara Manana, Rathnapura.', ' ', 'blankProfile.png'),
('2020/CSC/074', 'Mr', 'P.Saarukesan', 'Premkumar Saarukesan', 'Batticaloa', '0764722514', '0652054047', 'Chenkalady', 'Kandaramadam', '2020CSC074.jpg'),
('2020/CSC/075', NULL, '', '', '', '', '', '', '', NULL),
('2020/SB/001', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2020/SB/002', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2020/SB/005', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2020/SB/006', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2020/SB/008', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2020/SB/010', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2020/SB/012', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2020/SB/014', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2020/SB/020', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2020/SB/035', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2020/SB/038', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2020/SB/091', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2020/SP/001', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2020/SP/002', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2020/SP/003', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2020/SP/004', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2020/SP/005', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2020/SP/007', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2020/SP/008', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2020/SP/009', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2020/SP/012', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2020/SP/014', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2020/SP/015', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2020/SP/017', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2020/SP/018', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2020/SP/019', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2020/SP/035', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2020/SP/041', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2020/SP/044', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2020/SP/047', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2020/SP/059', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2020/SP/068', 'Miss', 'J.Jeyatheekshy', 'Jeyatheekshy Jeyarajen', 'Batticaloa', '0760586135', '0760586135', 'No.04, Building Quaters, Navalady Road, Kallady ,Batticaloa', 'Thirunelveli, Jaffna', '2020SP068.jpg'),
('2020/SP/070', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2020/SP/092', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2020/SP/121', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2020/SP/129', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2020/SP/143', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2020/SP/145', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2020/SP/170', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('2021/CSC/028', NULL, '', '', '', '', '', '', '', NULL),
('2021/CSC/080', 'Ms', 'J. Varsha', 'Varsha Jeyarajalingam', 'Colombo', '0768766755', '0112363600', '12-3/2, Collingwood Place, Colombo-06.', '1082, K.K.S Road, Kokuvil, Jaffna', '2021CSC080.png');

-- --------------------------------------------------------

--
-- Table structure for table `student_check`
--

CREATE TABLE `student_check` (
  `regNo` varchar(12) NOT NULL,
  `email` varchar(80) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'unregistered',
  `verificationCode` int(11) DEFAULT NULL,
  `verificationStatus` varchar(15) NOT NULL DEFAULT 'not_verified'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `student_check`
--

INSERT INTO `student_check` (`regNo`, `email`, `password`, `status`, `verificationCode`, `verificationStatus`) VALUES
('2014/CSC/051', '2014CSC051@gmail.com', '$2y$10$43cjXmjEzaBbdy5aNR/LquaQqXrqVU9r/Hj4tcshbN9UUHhNlCzIO', 'active', NULL, 'verified'),
('2018/SB/001', '001sb18@test.com', '$2y$10$beowQ3HLK6AmzhFjT7qkLud2bCwuQZHXzevwHOEVfEIT1loisfVP.', 'unregistered', 422791, 'not_verified'),
('2019/CSC/041', 'saanusansaanu@gmail.com', '$2y$10$rOKfjhDCai20ZtBWEZTdXuQnP.kqaHRv4xDMIdnE8j8Vxqa8qPDKC', 'active', 0, 'verified'),
('2019/SP/178', '178sp19@test.com', NULL, 'unregistered', NULL, 'not_verified'),
('2020/CSC/007', 'cnilwakka@gmail.com', '$2y$10$f64XVozpm4azju5H1fdZKe1QFSLr/U2QWLwojsETCK12/IHniPI9W', 'active', 0, 'verified'),
('2020/CSC/010', 'dharshikagnanaseelan4@gmail.com', '$2y$10$ewPtbft5YqpV6qkGcZjSL.s/hwCgiQjnYLOUjNRisKD9DLP7pHLhe', 'active', 0, 'verified'),
('2020/CSC/017', 'kasunlakmal9821@gmail.com', '$2y$10$fVrs03BwAHRTou6ljZx5nOrMcMV0c30YI87gvIwmVaBg9gFUym2T2', 'unregistered', 936663, 'not_verified'),
('2020/CSC/027', 'kgobi24lk@gmail.com', NULL, 'unregistered', NULL, 'not_verified'),
('2020/CSC/028', 'lahiruishan400@gmail.com', '$2y$10$VOZ9Qtc5/AaVMQls8FKSOOUk0ujsoDHJIk4ZRBrsfUZF/CGEh/B5u', 'active', 0, 'verified'),
('2020/CSC/033', 'sankavimohan2000@gmail.com', '$2y$10$vCwvApVHm39xjDlAvQtIlOpLYz1DsP0t2gx82RJI9kem7qIfUclHS', 'active', 0, 'verified'),
('2020/CSC/046', 'audeshitha@gmail.com', '$2y$10$IK1KYlGrrxEToFExo2It2OdClNukZTqXVd0Fg4cuyBDv7XNWt3ERi', 'active', 0, 'verified'),
('2020/CSC/050', 'nimantha.rathnayaka1999@gmail.com', NULL, 'unregistered', NULL, 'not_verified'),
('2020/CSC/051', 'viththagan1999@gmail.com', '$2y$10$43cjXmjEzaBbdy5aNR/LquaQqXrqVU9r/Hj4tcshbN9UUHhNlCzIO', 'active', 0, 'verified'),
('2020/CSC/052', '52csc20@test.com', NULL, 'unregistered', NULL, 'not_verified'),
('2020/CSC/055', 'sathasivamnerujan35@gmail.com', NULL, 'unregistered', NULL, 'not_verified'),
('2020/CSC/057', 'sivavithu15@live.com', '$2y$10$.E1MuzO7Bux8La8hnn8ZW.hipP55CUkgaltLmoDSRq/eVzXv8/CiW', 'active', 0, 'verified'),
('2020/CSC/061', 'vimalanthushani1122@gmail.com', NULL, 'unregistered', NULL, 'not_verified'),
('2020/CSC/065', 'vieronicka27@gmail.com', '$2y$10$NO9stDEgF3lkVlDNTxc4d.BSlqGWzGsU9YvmmN8fWnee56JWy9DGa', 'active', 0, 'verified'),
('2020/CSC/066', 'v.sayanishan.sv@gmail.com', NULL, 'unregistered', NULL, 'not_verified'),
('2020/CSC/067', 'ravindudeshitha01@gmail.com', '$2y$10$1hY8ugQrggwX/NMYb40cIO1Knl6F9nSWT/IgeVI/jNmnZmmpYZUQK', 'active', 0, 'verified'),
('2020/CSC/074', 'saaru27kesan@gmail.com', '$2y$10$1SqgzSYrm/51NsExtP4cMOLbMk8CZFSij5NcusNmbnqENN3G9AyMO', 'active', 0, 'verified'),
('2020/CSC/075', 'anathansinega@gmail.com', '$2y$10$dD8TJUyT0mj8GpJkP9CAQOtBFzBzDxjxi3brvsX0Cca.CfKhgpsbC', 'active', 0, 'verified'),
('2020/SB/001', '2020SB001@gmail.com', '$2y$10$1SqgzSYrm/51NsExtP4cMOLbMk8CZFSij5NcusNmbnqENN3G9AyMO', 'active', 0, 'verified'),
('2020/SB/002', '2020SB002@gmail.com', '$2y$10$1SqgzSYrm/51NsExtP4cMOLbMk8CZFSij5NcusNmbnqENN3G9AyMO', 'active', 0, 'verified'),
('2020/SB/005', '2020SB005@gmail.com', '$2y$10$1SqgzSYrm/51NsExtP4cMOLbMk8CZFSij5NcusNmbnqENN3G9AyMO', 'active', 0, 'verified'),
('2020/SB/006', '2020SB006@gmail.com', '$2y$10$1SqgzSYrm/51NsExtP4cMOLbMk8CZFSij5NcusNmbnqENN3G9AyMO', 'active', 0, 'verified'),
('2020/SB/008', '2020SB008@gmail.com', '$2y$10$1SqgzSYrm/51NsExtP4cMOLbMk8CZFSij5NcusNmbnqENN3G9AyMO', 'active', 0, 'verified'),
('2020/SB/010', '2020SB010@gmail.com', '$2y$10$1SqgzSYrm/51NsExtP4cMOLbMk8CZFSij5NcusNmbnqENN3G9AyMO', 'active', 0, 'verified'),
('2020/SB/012', '2020SB012@gmail.com', '$2y$10$1SqgzSYrm/51NsExtP4cMOLbMk8CZFSij5NcusNmbnqENN3G9AyMO', 'active', 0, 'verified'),
('2020/SB/014', '2020SB014@gmail.com', '$2y$10$1SqgzSYrm/51NsExtP4cMOLbMk8CZFSij5NcusNmbnqENN3G9AyMO', 'active', 0, 'verified'),
('2020/SB/020', 'student@gmail.com', '$2y$10$1SqgzSYrm/51NsExtP4cMOLbMk8CZFSij5NcusNmbnqENN3G9AyMO', 'active', 0, 'verified'),
('2020/SB/035', '2020SB035@gmail.com', '$2y$10$1SqgzSYrm/51NsExtP4cMOLbMk8CZFSij5NcusNmbnqENN3G9AyMO', 'active', 0, 'verified'),
('2020/SB/038', '2020SB038@gmail.com', '$2y$10$1SqgzSYrm/51NsExtP4cMOLbMk8CZFSij5NcusNmbnqENN3G9AyMO', 'active', 0, 'verified'),
('2020/SB/081', 'js.shapnika@gmail.com', '$2y$10$iusdS7HsFHvm9OCyNP9fcuW2m954X9lrIPD33ZvAdMpnhkyz8L7Jm', 'active', 0, 'verified'),
('2020/SB/091', '2020SB091@gmail.com', '$2y$10$1SqgzSYrm/51NsExtP4cMOLbMk8CZFSij5NcusNmbnqENN3G9AyMO', 'active', 0, 'verified'),
('2020/SP/001', '2020SP001@gmail.com', '$2y$10$1SqgzSYrm/51NsExtP4cMOLbMk8CZFSij5NcusNmbnqENN3G9AyMO', 'active', 0, 'verified'),
('2020/SP/002', '2020SP002@gmail.com', '$2y$10$1SqgzSYrm/51NsExtP4cMOLbMk8CZFSij5NcusNmbnqENN3G9AyMO', 'active', 0, 'verified'),
('2020/SP/003', '2020SP003@gmail.com', '$2y$10$1SqgzSYrm/51NsExtP4cMOLbMk8CZFSij5NcusNmbnqENN3G9AyMO', 'active', 0, 'verified'),
('2020/SP/004', '2020SP004@gmail.com', '$2y$10$1SqgzSYrm/51NsExtP4cMOLbMk8CZFSij5NcusNmbnqENN3G9AyMO', 'active', 0, 'verified'),
('2020/SP/005', '2020SP005@gmail.com', '$2y$10$1SqgzSYrm/51NsExtP4cMOLbMk8CZFSij5NcusNmbnqENN3G9AyMO', 'active', 0, 'verified'),
('2020/SP/007', '2020SP007@gmail.com', '$2y$10$1SqgzSYrm/51NsExtP4cMOLbMk8CZFSij5NcusNmbnqENN3G9AyMO', 'active', 0, 'verified'),
('2020/SP/008', '2020SP008@gmail.com', '$2y$10$1SqgzSYrm/51NsExtP4cMOLbMk8CZFSij5NcusNmbnqENN3G9AyMO', 'active', 0, 'verified'),
('2020/SP/009', '2020SP009@gmail.com', '$2y$10$1SqgzSYrm/51NsExtP4cMOLbMk8CZFSij5NcusNmbnqENN3G9AyMO', 'active', 0, 'verified'),
('2020/SP/012', '2020SP012@gmail.com', '$2y$10$1SqgzSYrm/51NsExtP4cMOLbMk8CZFSij5NcusNmbnqENN3G9AyMO', 'active', 0, 'verified'),
('2020/SP/014', '2020SP014@gmail.com', '$2y$10$1SqgzSYrm/51NsExtP4cMOLbMk8CZFSij5NcusNmbnqENN3G9AyMO', 'active', 0, 'verified'),
('2020/SP/015', '2020SP015@gmail.com', '$2y$10$1SqgzSYrm/51NsExtP4cMOLbMk8CZFSij5NcusNmbnqENN3G9AyMO', 'active', 0, 'verified'),
('2020/SP/017', '2020SP017@gmail.com', '$2y$10$1SqgzSYrm/51NsExtP4cMOLbMk8CZFSij5NcusNmbnqENN3G9AyMO', 'active', 0, 'verified'),
('2020/SP/018', '2020SP018@gmail.com', '$2y$10$1SqgzSYrm/51NsExtP4cMOLbMk8CZFSij5NcusNmbnqENN3G9AyMO', 'active', 0, 'verified'),
('2020/SP/019', '2020SP019@gmail.com', '$2y$10$1SqgzSYrm/51NsExtP4cMOLbMk8CZFSij5NcusNmbnqENN3G9AyMO', 'active', 0, 'verified'),
('2020/SP/035', '2020SP035@gmail.com', '$2y$10$1SqgzSYrm/51NsExtP4cMOLbMk8CZFSij5NcusNmbnqENN3G9AyMO', 'active', 0, 'verified'),
('2020/SP/041', '2020SP041@gmail.com', '$2y$10$1SqgzSYrm/51NsExtP4cMOLbMk8CZFSij5NcusNmbnqENN3G9AyMO', 'active', 0, 'verified'),
('2020/SP/044', '2020SP044@gmail.com', '$2y$10$1SqgzSYrm/51NsExtP4cMOLbMk8CZFSij5NcusNmbnqENN3G9AyMO', 'active', 0, 'verified'),
('2020/SP/047', '2020SP047@gmail.com', '$2y$10$1SqgzSYrm/51NsExtP4cMOLbMk8CZFSij5NcusNmbnqENN3G9AyMO', 'active', 0, 'verified'),
('2020/SP/059', '2020SP059@gmail.com', '$2y$10$1SqgzSYrm/51NsExtP4cMOLbMk8CZFSij5NcusNmbnqENN3G9AyMO', 'active', 0, 'verified'),
('2020/SP/068', 'theekshy27@gmail.com', '$2y$10$9IvVe6SXBRE3Pz5qtBvCJ.Evj4fKjN2aJjA4UOPdStrfnNRepRVVq', 'active', 0, 'verified'),
('2020/SP/070', '2020SP070@gmail.com', '$2y$10$1SqgzSYrm/51NsExtP4cMOLbMk8CZFSij5NcusNmbnqENN3G9AyMO', 'active', 0, 'verified'),
('2020/SP/092', '2020SP092@gmail.com', '$2y$10$1SqgzSYrm/51NsExtP4cMOLbMk8CZFSij5NcusNmbnqENN3G9AyMO', 'active', 0, 'verified'),
('2020/SP/121', '2020SP121@gmail.com', '$2y$10$1SqgzSYrm/51NsExtP4cMOLbMk8CZFSij5NcusNmbnqENN3G9AyMO', 'active', 0, 'verified'),
('2020/SP/129', 'kugatharshan26@gmail.com', NULL, 'unregistered', NULL, 'not_verified'),
('2020/SP/143', '2020SP143@gmail.com', '$2y$10$1SqgzSYrm/51NsExtP4cMOLbMk8CZFSij5NcusNmbnqENN3G9AyMO', 'active', 0, 'verified'),
('2020/SP/145', '2020SB145@gmail.com', '$2y$10$1SqgzSYrm/51NsExtP4cMOLbMk8CZFSij5NcusNmbnqENN3G9AyMO', 'active', 0, 'verified'),
('2020/SP/170', '2020SP170@gmail.com', '$2y$10$1SqgzSYrm/51NsExtP4cMOLbMk8CZFSij5NcusNmbnqENN3G9AyMO', 'active', 0, 'verified'),
('2021/CSC/028', '2021CSC028@gmail.com', '$2y$10$fdxz.kjCMMdxLrhCYZSZjO6T8.w4ZuXRja8Giouo6LLsIaMEd28dO', 'active', 0, 'verified'),
('2021/CSC/080', 'varujeya@gmail.com', '$2y$10$ruNdUYfduFryNF3/X902i.8dglkW8jbUDgEqCens.3ub0vvLRGDfm', 'active', 0, 'verified');

-- --------------------------------------------------------

--
-- Table structure for table `stud_exam_reg`
--

CREATE TABLE `stud_exam_reg` (
  `regId` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `stud_regNo` varchar(12) NOT NULL,
  `level` int(11) NOT NULL,
  `combId` int(11) NOT NULL,
  `type` enum('proper','repeat') NOT NULL,
  `reg_date` date DEFAULT '2020-01-01'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `stud_exam_reg`
--

INSERT INTO `stud_exam_reg` (`regId`, `exam_id`, `stud_regNo`, `level`, `combId`, `type`, `reg_date`) VALUES
(14, 2, '2021/CSC/080', 1, 1, 'proper', '2023-09-05'),
(18, 2, '2020/SB/020', 2, 3, 'proper', '2023-09-06'),
(19, 2, '2020/SB/091', 2, 3, 'proper', '2023-09-06'),
(20, 2, '2020/SB/001', 2, 4, 'proper', '2023-09-06'),
(21, 2, '2020/SB/008', 2, 4, 'proper', '2023-09-06'),
(22, 2, '2020/SB/010', 2, 4, 'proper', '2023-09-06'),
(23, 2, '2020/SB/002', 2, 5, 'proper', '2023-09-06'),
(24, 2, '2020/SB/005', 2, 5, 'proper', '2023-09-06'),
(25, 2, '2020/SB/006', 2, 5, 'proper', '2023-09-06'),
(26, 2, '2020/SB/012', 2, 2, 'proper', '2023-09-06'),
(27, 2, '2020/SB/014', 2, 2, 'proper', '2023-09-06'),
(29, 2, '2020/SP/145', 2, 6, 'proper', '2023-09-06'),
(30, 2, '2020/SP/009', 2, 6, 'proper', '2023-09-06'),
(31, 2, '2020/SP/070', 2, 8, 'proper', '2023-09-06'),
(32, 2, '2020/SP/121', 2, 8, 'proper', '2023-09-06'),
(33, 2, '2020/SP/170', 2, 8, 'proper', '2023-09-06'),
(34, 2, '2020/SP/044', 2, 10, 'proper', '2023-09-06'),
(35, 2, '2020/SP/092', 2, 10, 'proper', '2023-09-06'),
(36, 2, '2020/SP/007', 2, 12, 'proper', '2023-09-06'),
(37, 2, '2020/SP/015', 2, 12, 'proper', '2023-09-06'),
(38, 2, '2020/SP/017', 2, 12, 'proper', '2023-09-06'),
(39, 2, '2020/SP/018', 2, 12, 'proper', '2023-09-06'),
(40, 2, '2020/SP/041', 2, 12, 'proper', '2023-09-06'),
(42, 2, '2020/CSC/074', 2, 1, 'proper', '2023-09-06'),
(43, 2, '2020/CSC/007', 2, 1, 'proper', '2023-09-07'),
(44, 2, '2020/CSC/033', 2, 1, 'proper', '2023-09-08'),
(45, 2, '2020/CSC/051', 2, 1, 'proper', '2023-11-04'),
(46, 2, '2020/CSC/074', 1, 1, 'proper', '2023-11-05');

-- --------------------------------------------------------

--
-- Table structure for table `subject`
--

CREATE TABLE `subject` (
  `subject` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subject`
--

INSERT INTO `subject` (`subject`) VALUES
('AMM'),
('BOT'),
('CHE'),
('CSC'),
('CSC - Direct Intake'),
('FSC'),
('PHY'),
('PMM'),
('STA'),
('ZOO');

-- --------------------------------------------------------

--
-- Table structure for table `unit`
--

CREATE TABLE `unit` (
  `unitId` int(11) NOT NULL,
  `unitCode` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `subject` varchar(50) NOT NULL,
  `level` int(1) NOT NULL,
  `acYearAdded` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `unit`
--

INSERT INTO `unit` (`unitId`, `unitCode`, `name`, `subject`, `level`, `acYearAdded`) VALUES
(1, 'CSC101S3', 'Foundations of Computer Science', 'CSC - Direct Intake', 1, 2017),
(2, 'CSC102S3', 'Computer Programming I', 'CSC - Direct Intake', 1, 2017),
(3, 'CSC103S3', 'Introduction to Computer Systems', 'CSC - Direct Intake', 1, 2017),
(4, 'CSC102G3', ' Computer Programming I', 'CSC', 1, 2017),
(5, 'CSC104G2', 'Design of Algorithms', 'CSC', 1, 2017),
(6, 'PMM103G3', 'Foundations of Mathematics', 'PMM', 1, 2017),
(7, 'PMM104G2', 'Calculus', 'PMM', 1, 2017),
(8, 'AMM103G3', 'Applied Methods II', 'AMM', 1, 2017),
(9, 'AMM104G2', 'Mechanics II', 'AMM', 1, 2017),
(10, 'STA103G3', 'Basic Statistical Inference', 'STA', 1, 2017),
(11, 'STA104G2', 'STA104G2', 'STA', 1, 2017),
(12, 'PHY107G3', 'PHY107G3', 'PHY', 1, 2017),
(13, 'BOA103G2', 'BOA103G2', 'BOT', 1, 2017),
(14, 'BOA104G2', 'BOA104G2', 'BOT', 1, 2017),
(15, 'BOA105G2', 'BOA105G2', 'BOT', 1, 2017),
(16, 'FIS103G2', 'FIS103G2', 'FSC', 1, 2017),
(17, 'FIS104G2', 'FIS104G2', 'FSC', 1, 2017),
(18, 'FIS105G2', 'FIS105G2', 'FSC', 1, 2017),
(19, 'ZOL104G2', 'ZOL104G2', 'ZOO', 1, 2017),
(20, 'ZOL105G2', 'ZOL105G2', 'ZOO', 1, 2017),
(21, 'CSC106S3', 'CSC106S3', 'CSC - Direct Intake', 1, 2017),
(22, 'CSC108S2', 'CSC108S2', 'CSC - Direct Intake', 1, 2017),
(23, 'CSC109S2', 'CSC109S2', 'CSC - Direct Intake', 1, 2017),
(24, 'CSC111S2', 'CSC111S2', 'CSC - Direct Intake', 1, 2017),
(25, 'CSC112S3', 'CSC112S3', 'CSC - Direct Intake', 1, 2017),
(26, 'CHE102G2', 'CHE102G2', 'CHE', 1, 2017),
(27, 'CHE104G3', 'CHE104G3', 'CHE', 1, 2017),
(28, 'CHE106G1', 'CHE106G1', 'CHE', 1, 2017),
(29, 'CSC104S2', 'Mathematics for Computing I', 'CSC - Direct Intake', 1, 2017),
(30, 'CSC201S2', 'Database Systems Concepts and Design', 'CSC - Direct Intake', 2, 2017),
(31, 'CSC202S2', 'Computer Programming II', 'CSC - Direct Intake', 2, 2017),
(32, 'CSC203S2', 'Operating Systems', 'CSC - Direct Intake', 2, 2017),
(33, 'CSC204S2', 'Data Structures & Algorithms', 'CSC - Direct Intake', 2, 2017),
(34, 'CSC205S2', 'Software Engineering', 'CSC - Direct Intake', 2, 2017),
(35, 'CSC206S4', 'Mathematics for Computing III', 'CSC - Direct Intake', 2, 2017),
(36, 'CSC207S3', 'Computer Architecture', 'CSC - Direct Intake', 2, 2017),
(37, 'CSC208S3', 'Concepts of Programming Languages', 'CSC - Direct Intake', 2, 2017),
(38, 'CSC209S3', 'Bioinformatics', 'CSC - Direct Intake', 2, 2017),
(39, 'CSC210S3', 'Web Technologies', 'CSC - Direct Intake', 2, 2017),
(40, 'CSC211S2', 'Emerging Trends in Computer Science', 'CSC - Direct Intake', 2, 2017),
(41, 'CSC212S2', 'Professional Practice', 'CSC - Direct Intake', 2, 2017),
(42, 'CSC201G2', 'Database Systems Concepts and Design', 'CSC', 2, 2017),
(43, 'CSC202G2', 'Computer Programming II', 'CSC', 2, 2017),
(44, 'CSC203G2', 'Operating Systems', 'CSC', 2, 2017),
(45, 'CSC204G2', 'Data Structures & Algorithms', 'CSC', 2, 2017),
(46, 'CSC205G2', 'Software Engineering', 'CSC', 2, 2017),
(47, 'CHE201G2', 'Coordination and Organometallic Chemistry', 'CHE', 2, 2017),
(48, 'CHE202G3', 'Quantum Mechanical Approach to Atomic and Molecular Structure and Molecular Spectroscopy', 'CHE', 2, 2017),
(49, 'CHE203G2', 'Organic Chemistry II', 'CHE', 2, 2017),
(50, 'CHE204G2', 'Inorganic  and Organic Chemistry Laboratory II', 'CHE', 2, 2017),
(51, 'FIS201G2', 'Laboratory Techniques', 'FSC', 2, 2017),
(52, 'FIS202G2', 'Aquatic Fauna and Flora', 'FSC', 2, 2017),
(53, 'FIS203G2', 'Principles of aquatic ecology and fish behaviour', 'FSC', 2, 2017),
(54, 'FIS204G2', 'Fish biology and embryology', 'FSC', 2, 2017),
(55, 'FIS205G2', 'Fish Parasitology and Diseases', 'FSC', 2, 2017),
(56, 'ZOL201G2', 'Invertebrate Phylogeny and Biology', 'ZOO', 2, 2017),
(57, 'ZOL202G2', 'Vertebrate Phylogeny and Biology', 'ZOO', 2, 2017),
(58, 'ZOL203G2', 'Comparative Anatomy and Physiology', 'ZOO', 2, 2017),
(59, 'ZOL204G2', 'Animal Ecology', 'ZOO', 2, 2017),
(60, 'ZOL205G2', 'Animal Behaviour', 'ZOO', 2, 2017),
(61, 'BOA201G2', 'Plant Morphology and Anatomy', 'BOT', 2, 2017),
(62, 'BOA202G2', 'Plant Systematics', 'BOT', 2, 2017),
(63, 'BOA203G2', 'Biochemistry', 'BOT', 2, 2017),
(64, 'BOA204G2', 'Genetics', 'BOT', 2, 2017),
(65, 'BOA205G2', 'General Microbiology', 'BOT', 2, 2017),
(66, 'PHY201G2', 'Practical Physics II', 'PHY', 2, 2017),
(67, 'PHY202G2', 'Solid State Physics', 'PHY', 2, 2017),
(68, 'PHY203G2', 'Optics and Special Relativity', 'PHY', 2, 2017),
(69, 'PHY204G2', 'Electromagnetism', 'PHY', 2, 2017),
(70, 'PHY205G2', 'Computational Physics', 'PHY', 2, 2017),
(71, 'STA201G3', 'Statistical Theory', 'STA', 2, 2017),
(72, 'STA202G2', 'Sampling Techniques', 'STA', 2, 2017),
(73, 'STA203G3', 'Design and Analysis of Experiments', 'STA', 2, 2017),
(74, 'STA204G2', 'Statistical Inference', 'STA', 2, 2017),
(75, 'PMM201G3', 'Linear Algebra', 'PMM', 2, 2017),
(76, 'PMM203G3', 'Analysis', 'PMM', 2, 2017),
(77, 'PMM202G2', 'Advanced Calculus', 'PMM', 2, 2017),
(78, 'PMM204G2', 'Linear Algebra and Analytic Geometry', 'PMM', 2, 2017),
(79, 'AMM201G3', 'Mathematical Methods', 'AMM', 2, 2017),
(80, 'AMM202G2', 'Fluid Dynamics', 'AMM', 2, 2017),
(81, 'AMM203G3', 'Linear Programming', 'AMM', 2, 2017),
(82, 'AMM204G2', 'Linear Algebra and Analytic Geometry', 'AMM', 2, 2017),
(83, 'CSC403S3', 'Data Science', 'CSC - Direct Intake', 4, 2019);

-- --------------------------------------------------------

--
-- Table structure for table `unit_sub_exam`
--

CREATE TABLE `unit_sub_exam` (
  `exam_unit_id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `unitId` int(11) NOT NULL,
  `type` enum('proper','repeat') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `unit_sub_exam`
--

INSERT INTO `unit_sub_exam` (`exam_unit_id`, `exam_id`, `unitId`, `type`) VALUES
(90, 2, 2, 'proper'),
(103, 2, 6, 'proper'),
(104, 2, 7, 'proper'),
(91, 2, 21, 'proper'),
(94, 2, 22, 'proper'),
(95, 2, 23, 'proper'),
(93, 2, 24, 'proper'),
(92, 2, 25, 'proper'),
(98, 2, 31, 'proper'),
(105, 2, 31, 'repeat'),
(97, 2, 32, 'proper'),
(106, 2, 32, 'repeat'),
(96, 2, 33, 'proper'),
(107, 2, 33, 'repeat'),
(99, 2, 38, 'proper'),
(102, 2, 39, 'proper'),
(101, 2, 40, 'proper'),
(100, 2, 41, 'proper'),
(108, 2, 41, 'repeat'),
(21, 2, 43, 'proper'),
(22, 2, 44, 'proper'),
(23, 2, 45, 'proper'),
(54, 2, 48, 'proper'),
(55, 2, 50, 'proper'),
(36, 2, 53, 'proper'),
(37, 2, 54, 'proper'),
(38, 2, 55, 'proper'),
(39, 2, 58, 'proper'),
(40, 2, 59, 'proper'),
(33, 2, 63, 'proper'),
(34, 2, 64, 'proper'),
(35, 2, 65, 'proper'),
(30, 2, 66, 'proper'),
(31, 2, 69, 'proper'),
(32, 2, 70, 'proper'),
(28, 2, 73, 'proper'),
(29, 2, 74, 'proper'),
(50, 2, 76, 'proper'),
(51, 2, 78, 'proper'),
(52, 2, 81, 'proper'),
(53, 2, 82, 'proper');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `admin_details`
--
ALTER TABLE `admin_details`
  ADD PRIMARY KEY (`adminId`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `combination`
--
ALTER TABLE `combination`
  ADD PRIMARY KEY (`combinationID`);

--
-- Indexes for table `combination_subjects`
--
ALTER TABLE `combination_subjects`
  ADD PRIMARY KEY (`combinationID`,`subject`),
  ADD KEY `subject` (`subject`);

--
-- Indexes for table `exam_reg`
--
ALTER TABLE `exam_reg`
  ADD PRIMARY KEY (`exam_id`),
  ADD UNIQUE KEY `academic_year` (`academic_year`,`semester`);

--
-- Indexes for table `exam_reg_excep`
--
ALTER TABLE `exam_reg_excep`
  ADD PRIMARY KEY (`exam_id`,`level`,`type`,`regNo`),
  ADD KEY `exam_reg_excep_ibfk_2` (`regNo`);

--
-- Indexes for table `exam_stud_index`
--
ALTER TABLE `exam_stud_index`
  ADD PRIMARY KEY (`exam_id`,`regNo`),
  ADD KEY `exam_stud_index_ibfk_2` (`regNo`);

--
-- Indexes for table `reg_units`
--
ALTER TABLE `reg_units`
  ADD PRIMARY KEY (`regId`,`exam_unit_id`);

--
-- Indexes for table `repeat_slips`
--
ALTER TABLE `repeat_slips`
  ADD PRIMARY KEY (`regId`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`regNo`);

--
-- Indexes for table `student_check`
--
ALTER TABLE `student_check`
  ADD PRIMARY KEY (`regNo`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `stud_exam_reg`
--
ALTER TABLE `stud_exam_reg`
  ADD PRIMARY KEY (`regId`),
  ADD UNIQUE KEY `exam_id` (`exam_id`,`stud_regNo`,`level`,`combId`,`type`) USING BTREE,
  ADD KEY `stud_regNo` (`stud_regNo`),
  ADD KEY `combId` (`combId`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`subject`);

--
-- Indexes for table `unit`
--
ALTER TABLE `unit`
  ADD PRIMARY KEY (`unitId`),
  ADD UNIQUE KEY `unitCode` (`unitCode`,`acYearAdded`),
  ADD KEY `subject` (`subject`);

--
-- Indexes for table `unit_sub_exam`
--
ALTER TABLE `unit_sub_exam`
  ADD PRIMARY KEY (`exam_unit_id`),
  ADD UNIQUE KEY `exam_id` (`exam_id`,`unitId`,`type`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_details`
--
ALTER TABLE `admin_details`
  MODIFY `adminId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `combination`
--
ALTER TABLE `combination`
  MODIFY `combinationID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `exam_reg`
--
ALTER TABLE `exam_reg`
  MODIFY `exam_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `stud_exam_reg`
--
ALTER TABLE `stud_exam_reg`
  MODIFY `regId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `unit`
--
ALTER TABLE `unit`
  MODIFY `unitId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `unit_sub_exam`
--
ALTER TABLE `unit_sub_exam`
  MODIFY `exam_unit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_details`
--
ALTER TABLE `admin_details`
  ADD CONSTRAINT `admin_details_ibfk_1` FOREIGN KEY (`email`) REFERENCES `admin` (`email`) ON UPDATE CASCADE;

--
-- Constraints for table `combination_subjects`
--
ALTER TABLE `combination_subjects`
  ADD CONSTRAINT `combination_subjects_ibfk_1` FOREIGN KEY (`combinationID`) REFERENCES `combination` (`combinationID`),
  ADD CONSTRAINT `combination_subjects_ibfk_2` FOREIGN KEY (`subject`) REFERENCES `subject` (`subject`);

--
-- Constraints for table `exam_reg_excep`
--
ALTER TABLE `exam_reg_excep`
  ADD CONSTRAINT `exam_reg_excep_ibfk_1` FOREIGN KEY (`exam_id`) REFERENCES `exam_reg` (`exam_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `exam_reg_excep_ibfk_2` FOREIGN KEY (`regNo`) REFERENCES `student_check` (`regNo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `exam_stud_index`
--
ALTER TABLE `exam_stud_index`
  ADD CONSTRAINT `exam_stud_index_ibfk_1` FOREIGN KEY (`exam_id`) REFERENCES `exam_reg` (`exam_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `exam_stud_index_ibfk_2` FOREIGN KEY (`regNo`) REFERENCES `student_check` (`regNo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reg_units`
--
ALTER TABLE `reg_units`
  ADD CONSTRAINT `reg_units_ibfk_1` FOREIGN KEY (`regId`) REFERENCES `stud_exam_reg` (`regId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `repeat_slips`
--
ALTER TABLE `repeat_slips`
  ADD CONSTRAINT `FK_repeat_slps_examID` FOREIGN KEY (`regId`) REFERENCES `stud_exam_reg` (`regId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`regNo`) REFERENCES `student_check` (`regNo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `stud_exam_reg`
--
ALTER TABLE `stud_exam_reg`
  ADD CONSTRAINT `stud_exam_reg_ibfk_1` FOREIGN KEY (`exam_id`) REFERENCES `exam_reg` (`exam_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
