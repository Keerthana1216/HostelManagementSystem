-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 15, 2025 at 06:24 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `studentdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `aid` varchar(200) NOT NULL,
  `name` varchar(255) NOT NULL,
  `department` varchar(200) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`aid`, `name`, `department`, `email`, `password`, `mobile`) VALUES
('A1', 'Dr.K.M.Alaaudeen', 'CSE', 'alaaudeen@gmail.com', 'alaaudeen', '8987877676'),
('A2', 'KK', 'ECE', 'kk@gmail.com', 'kk', '9878989887'),
('P1', 'Dr.Richard', '', 'richard@gmail.com', 'richard', '8787767877');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `sid` varchar(200) NOT NULL,
  `status` varchar(25) NOT NULL,
  `date` date NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`sid`, `status`, `date`, `id`) VALUES
('GHB001', '1', '2025-03-25', 1),
('GHB002', '0', '2025-03-25', 2),
('GHB003', '0', '2025-03-25', 3),
('GHB004', '1', '2025-03-25', 4),
('GHG001', '1', '2025-03-25', 5),
('GHG002', '0', '2025-03-25', 6),
('GHG003', '1', '2025-03-25', 7),
('GHG004', '0', '2025-03-25', 8),
('GHG005', '1', '2025-03-25', 9),
('GHG006', '1', '2025-03-25', 10),
('GHG001', '0', '2025-04-15', 11),
('GHG002', '0', '2025-04-15', 12),
('GHG003', '1', '2025-04-15', 13);

-- --------------------------------------------------------

--
-- Table structure for table `batch`
--

CREATE TABLE `batch` (
  `s.no` varchar(200) NOT NULL,
  `batch_year` year(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `batch`
--

INSERT INTO `batch` (`s.no`, `batch_year`) VALUES
('1', '2021'),
('2', '2022'),
('3', '2023'),
('4', '2024');

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `s.no` varchar(200) NOT NULL,
  `Dept_name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`s.no`, `Dept_name`) VALUES
('6', 'AI&DS'),
('4', 'CIVIL'),
('1', 'CSE'),
('2', 'ECE'),
('3', 'EEE'),
('5', 'MECH');

-- --------------------------------------------------------

--
-- Table structure for table `food_schedule`
--

CREATE TABLE `food_schedule` (
  `id` int(11) NOT NULL,
  `day` varchar(20) NOT NULL,
  `meal_type` enum('Breakfast','Lunch','Dinner') NOT NULL,
  `menu` text NOT NULL,
  `timing` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `food_schedule`
--

INSERT INTO `food_schedule` (`id`, `day`, `meal_type`, `menu`, `timing`) VALUES
(1, 'Monday', 'Breakfast', 'Bread,Jam', ''),
(2, 'Monday', 'Lunch', 'Rice, Dal', ''),
(3, 'Monday', 'Dinner', 'Chapati', ''),
(4, 'Tuesday', 'Breakfast', 'Idli, Sambar', ''),
(5, 'Tuesday', 'Lunch', 'Rajma, Roti, Salad', ''),
(6, 'Tuesday', 'Dinner', 'Dosa, Coconut Chutney', ''),
(7, 'Wednesday', 'Breakfast', 'Dal', ''),
(8, 'Wednesday', 'Lunch', 'Rice', ''),
(9, 'Wednesday', 'Dinner', 'Bread', ''),
(10, 'Sunday', 'Breakfast', 'Biryani', ''),
(11, 'Sunday', 'Dinner', 'Tea', ''),
(12, 'Sunday', 'Lunch', 'Tea', ''),
(13, 'Saturday', 'Breakfast', 'Coffee', ''),
(14, 'Thursday', 'Dinner', 'Bread', ''),
(15, 'Thursday', 'Breakfast', 'Bread,Jam', '');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `sid` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `message` text NOT NULL,
  `status` enum('unread','read') DEFAULT 'unread',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`sid`, `type`, `message`, `status`, `created_at`) VALUES
(1, 'outpass', 'Outpass request from Student ID GHB002 is awaiting admin approval.', 'unread', '2025-03-28 04:13:53'),
(2, 'outpass', 'Outpass request from Student ID GHB002 is awaiting admin approval.', 'unread', '2025-03-28 05:17:27'),
(3, 'outpass', 'Outpass request from Student ID GHB002 is awaiting admin approval.', 'unread', '2025-03-28 05:25:18');

-- --------------------------------------------------------

--
-- Table structure for table `outpass_requests`
--

CREATE TABLE `outpass_requests` (
  `sid` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `room_no` varchar(20) NOT NULL,
  `reason` text NOT NULL,
  `leave_date` date NOT NULL,
  `return_date` date NOT NULL,
  `leave_time` time NOT NULL,
  `return_time` time NOT NULL,
  `destination` varchar(255) NOT NULL,
  `sstatus` varchar(20) DEFAULT 'Pending',
  `astatus` varchar(20) DEFAULT 'Pending',
  `pstatus` varchar(20) DEFAULT 'Pending',
  `approved_by` varchar(50) DEFAULT NULL,
  `tstatus` varchar(20) DEFAULT 'Pending',
  `fstatus` varchar(20) DEFAULT 'Pending',
  `id` int(11) NOT NULL,
  `final_status` varchar(20) DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `outpass_requests`
--

INSERT INTO `outpass_requests` (`sid`, `name`, `room_no`, `reason`, `leave_date`, `return_date`, `leave_time`, `return_time`, `destination`, `sstatus`, `astatus`, `pstatus`, `approved_by`, `tstatus`, `fstatus`, `id`, `final_status`) VALUES
('GHB002', 'Joel', 'F06', 'pain', '2025-04-11', '2025-04-12', '12:01:00', '12:01:00', 'chennai', 'Outdated Request', 'Approved', 'Approved', NULL, 'Approved', 'Approved', 81, 'Outdated Request'),
('GHB002', 'Joel', 'F06', 'fever', '2025-04-12', '2025-04-13', '12:50:00', '12:50:00', 'Trichy', 'Rejected', 'Pending', 'Approved', NULL, 'Rejected', 'Pending', 88, 'Rejected'),
('GHG002', 'Iys', 'S7', 'fever', '2025-04-15', '2025-04-16', '01:25:00', '01:25:00', 'chennai', 'Approved', 'Approved', 'Approved', NULL, 'Approved', 'Approved', 96, 'Approved'),
('GHG006', 'Keerthana', 'S8', 'pain', '2025-04-15', '2025-04-16', '09:35:00', '09:36:00', 'chennai', 'Approved', 'Approved', 'Approved', NULL, 'Approved', 'Approved', 98, 'Approved');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `room_no` varchar(10) NOT NULL,
  `capacity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`room_no`, `capacity`) VALUES
('F05', 4),
('F06', 3),
('F07', 4),
('F08', 2),
('F09', 3),
('F10', 1),
('F11', 4),
('F12', 3),
('S7', 3),
('S8', 4),
('S9', 3);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `sid` varchar(200) NOT NULL,
  `name` varchar(200) NOT NULL,
  `st_id` varchar(200) NOT NULL,
  `department` varchar(350) NOT NULL,
  `gender` varchar(350) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `joining_year` year(4) NOT NULL,
  `passout_year` year(4) NOT NULL,
  `current_year` varchar(10) NOT NULL,
  `address` varchar(200) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `room_no` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`sid`, `name`, `st_id`, `department`, `gender`, `email`, `password`, `joining_year`, `passout_year`, `current_year`, `address`, `mobile`, `room_no`) VALUES
('GHB001', 'Abi', '950321104002', 'CSE', 'Male', 'abi@gmail.com', 'abi', '2021', '2025', 'IV', '1/AAA,BBB', '9887767678', 'F06'),
('GHB002', 'Joel', '950321104023', 'CSE', 'Male', 'joel@gmail.com', 'joel', '2021', '2025', 'IV', '2/CCC,BBB', '9898878787', 'F06'),
('GHB003', 'Denni', '950321104015', 'CSE', 'Male', 'denni@gmail.com', 'denni', '2021', '2025', 'IV', '3/HHH,KKK', '9887898789', 'F07'),
('GHB004', 'Mark', '950321104033', 'AI&DS', 'Male', 'mark@gmail.com', 'mark', '2022', '2026', 'III', '4/KKK,HHH', '9987898789', 'F11'),
('GHB005', 'Guna', '9503211030766', 'MECH', 'Male', 'guna@gmail.com', 'guna', '2022', '2026', 'III', '5/UUU,JJJ', '9887878988', ''),
('GHG001', 'Riya', '950221104027', 'ECE', 'Female', 'riya@gmail.com', 'riya', '2022', '2026', 'III', '4/UUU,KKK', '9887898989', 'F10'),
('GHG002', 'Iys', '950321104018', 'ECE', 'Female', 'iys@gmail.com', 'iys', '2021', '2025', 'IV', '2/JJJ,III', '9887879999', 'S7'),
('GHG003', 'Kranti', '9503211040943', 'AI&DS', 'Female', 'kranti@gmail.com', 'kranti', '2022', '2026', 'III', '3/UUU,JJJ', '6787898999', ''),
('GHG005', 'Devi', '950221104026', 'MECH', 'Female', 'devi@gmail.com', 'devi', '2023', '2027', 'II', '5/OOO,UUU', '8987898789', 'S7'),
('GHG006', 'Keerthana', '950121104027', 'ECE', 'Female', 'keerthana@gmail.com', 'keerthana', '2024', '2028', 'IV', '6/LLL,GGG', '9899997876', 'S8');

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `rid` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `gender` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `department` varchar(200) NOT NULL,
  `current_year` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`rid`, `name`, `email`, `password`, `gender`, `mobile`, `department`, `current_year`) VALUES
('F1', 'Shainy ', 'shainy@gmail.com', 'shainy', 'Female', '9898989886', 'CSE', 'I'),
('F2', 'Jeyantha', 'jeyantha@gmail.com', 'jeyantha', 'Female', '9878877677', 'CSE', 'IV'),
('F3', 'Abarna', 'abarna@gmail.com', 'abarna', 'Female', '9888989898', 'CSE', 'III'),
('F4', 'Mohan', 'mohan@gmail.com', 'mohan', 'Male', '9898988789', 'MECH', 'II'),
('F5', 'Gayathri', 'gayathri@gmail.com', 'gayathri', 'female', '9889998878', 'EEE', 'III'),
('F6', 'Dhana', 'Dhana@gmail.com', 'dhana', 'Female', '8989898899', 'ECE', 'IV'),
('R1', 'G.Santhiya', 'santhiya@gmail.com', 'santhiya', 'Female', '9887898791', '', ''),
('R2', 'E.Pushparagam', 'pushpa@gmail.com', 'pushpa', 'Female', '9887988776', '', ''),
('R3', 'Dr.K.M.Alaaudeen', 'alaaudeen@gmail.com', 'alaaudeen', 'Male', '8767878999', '', ''),
('R4', 'Vinith', 'vinith@gmail.com', 'vinith', 'Male', '8999898799', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`aid`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `batch`
--
ALTER TABLE `batch`
  ADD PRIMARY KEY (`batch_year`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`Dept_name`);

--
-- Indexes for table `food_schedule`
--
ALTER TABLE `food_schedule`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`sid`);

--
-- Indexes for table `outpass_requests`
--
ALTER TABLE `outpass_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`room_no`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`sid`),
  ADD UNIQUE KEY `sid` (`sid`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`rid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `food_schedule`
--
ALTER TABLE `food_schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `sid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `outpass_requests`
--
ALTER TABLE `outpass_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
