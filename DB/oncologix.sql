-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 09, 2025 at 06:42 PM
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
-- Database: `oncologix`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`username`, `password`) VALUES
('bhargav', 'bhargav@30'),
('Vivek_vara', 'Vivek@69');

-- --------------------------------------------------------

--
-- Table structure for table `cancer`
--

CREATE TABLE `cancer` (
  `can_id` int(11) NOT NULL,
  `cancer_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cancer`
--

INSERT INTO `cancer` (`can_id`, `cancer_name`) VALUES
(1, 'Lung Cancer'),
(2, 'Kidney Cancer'),
(3, 'Stomach Cancer'),
(4, 'Brain Cancer'),
(5, 'Blood Cancer');

-- --------------------------------------------------------

--
-- Table structure for table `cases`
--

CREATE TABLE `cases` (
  `c_id` int(11) NOT NULL,
  `p_id` int(11) DEFAULT NULL,
  `doc_id` int(11) NOT NULL,
  `h_id` int(11) DEFAULT NULL,
  `t_id` int(11) DEFAULT NULL,
  `cancer_type` varchar(100) NOT NULL,
  `patient_date` date NOT NULL DEFAULT current_timestamp(),
  `approval_status` varchar(255) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cases`
--

INSERT INTO `cases` (`c_id`, `p_id`, `doc_id`, `h_id`, `t_id`, `cancer_type`, `patient_date`, `approval_status`) VALUES
(15, 16, 1, 16, 16, 'Lung Cancer', '2008-11-11', 'approved');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_register`
--

CREATE TABLE `doctor_register` (
  `doc_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `experience` int(2) NOT NULL,
  `specialization` varchar(255) NOT NULL,
  `password` varchar(100) NOT NULL,
  `doc_photo` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor_register`
--

INSERT INTO `doctor_register` (`doc_id`, `name`, `experience`, `specialization`, `password`, `doc_photo`, `email`) VALUES
(1, 'Bhargav Gohel', 12, 'lung cancer', '123', 'd1.jpg', 'gohelbhargav02@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `hospital`
--

CREATE TABLE `hospital` (
  `h_id` int(11) NOT NULL,
  `h_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contactNo` varchar(15) NOT NULL,
  `country` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hospital`
--

INSERT INTO `hospital` (`h_id`, `h_name`, `email`, `contactNo`, `country`, `state`, `city`) VALUES
(16, 'Apollo Cancer Institute', 'apollo.cancer@example.com', '+91 98234 56789', 'India', 'Gujarat', 'Ahmedabad'),
(17, 'civil hospital', 'civil07@gmail.com', '+91 98234 56789', 'India', 'Gujarat', 'Rajkot');

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE `patient` (
  `p_id` int(11) NOT NULL,
  `P_name` varchar(100) NOT NULL,
  `age` int(11) NOT NULL,
  `p_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`p_id`, `P_name`, `age`, `p_image`) VALUES
(16, 'Anil Mehta', 45, 'p1.jpg'),
(17, 'Akshay Kumar', 46, 'p2.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `treatment`
--

CREATE TABLE `treatment` (
  `t_id` int(11) NOT NULL,
  `t_given` text NOT NULL,
  `M_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `treatment`
--

INSERT INTO `treatment` (`t_id`, `t_given`, `M_image`) VALUES
(16, 'This treatment given for the lung cancer', 't2.jpg'),
(17, 'This treatment given for the lung cancer', 't2.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `cancer`
--
ALTER TABLE `cancer`
  ADD PRIMARY KEY (`can_id`);

--
-- Indexes for table `cases`
--
ALTER TABLE `cases`
  ADD PRIMARY KEY (`c_id`),
  ADD KEY `p_id` (`p_id`),
  ADD KEY `h_id` (`h_id`),
  ADD KEY `t_id` (`t_id`),
  ADD KEY `doc_id` (`doc_id`);

--
-- Indexes for table `doctor_register`
--
ALTER TABLE `doctor_register`
  ADD PRIMARY KEY (`doc_id`);

--
-- Indexes for table `hospital`
--
ALTER TABLE `hospital`
  ADD PRIMARY KEY (`h_id`);

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`p_id`);

--
-- Indexes for table `treatment`
--
ALTER TABLE `treatment`
  ADD PRIMARY KEY (`t_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cancer`
--
ALTER TABLE `cancer`
  MODIFY `can_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `cases`
--
ALTER TABLE `cases`
  MODIFY `c_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `doctor_register`
--
ALTER TABLE `doctor_register`
  MODIFY `doc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `hospital`
--
ALTER TABLE `hospital`
  MODIFY `h_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `patient`
--
ALTER TABLE `patient`
  MODIFY `p_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `treatment`
--
ALTER TABLE `treatment`
  MODIFY `t_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cases`
--
ALTER TABLE `cases`
  ADD CONSTRAINT `cases_ibfk_1` FOREIGN KEY (`p_id`) REFERENCES `patient` (`p_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cases_ibfk_3` FOREIGN KEY (`h_id`) REFERENCES `hospital` (`h_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cases_ibfk_4` FOREIGN KEY (`t_id`) REFERENCES `treatment` (`t_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cases_ibfk_5` FOREIGN KEY (`doc_id`) REFERENCES `doctor_register` (`doc_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
