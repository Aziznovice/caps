-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 19, 2024 at 01:44 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `archiving_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `acc_user`
--

CREATE TABLE `acc_user` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `type` int(11) NOT NULL,
  `deleted` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `acc_user`
--

INSERT INTO `acc_user` (`id`, `first_name`, `last_name`, `email`, `password`, `type`, `deleted`) VALUES
(5, 'Archive', 'administrator', 'admin@archive.com', '202cb962ac59075b964b07152d234b70', 1, 0),
(10, 'andrie', 'pajiri', 'andrie@gmail.com', '202cb962ac59075b964b07152d234b70', 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `bookmarks`
--

CREATE TABLE `bookmarks` (
  `id` int(255) NOT NULL,
  `user_id` int(255) NOT NULL,
  `post_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `bookmarks`
--

INSERT INTO `bookmarks` (`id`, `user_id`, `post_id`) VALUES
(26, 42, 152),
(60, 23, 198),
(61, 23, 191);

-- --------------------------------------------------------

--
-- Table structure for table `posts_archive`
--

CREATE TABLE `posts_archive` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL,
  `message` text NOT NULL,
  `capstonemembers` varchar(255) NOT NULL,
  `capstone_advisor` varchar(255) NOT NULL,
  `capstone_mentor` varchar(255) NOT NULL,
  `panel_member` varchar(255) NOT NULL,
  `copyright` varchar(255) NOT NULL,
  `pdf_name` varchar(255) NOT NULL,
  `pdfdisplay` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `status` enum('published','draft','archived','') NOT NULL DEFAULT 'published',
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `posts_archive`
--

INSERT INTO `posts_archive` (`id`, `title`, `message`, `capstonemembers`, `capstone_advisor`, `capstone_mentor`, `panel_member`, `copyright`, `pdf_name`, `pdfdisplay`, `category_id`, `userid`, `status`, `created`, `updated`) VALUES
(191, 'Arduino Base Fire, Gas, Smoke Alarm Detector with GSM Module', 'Nowadays, establishment security becomes the best solution to overcome establishment intrusion problems when the user is not in the site of the establishment. This project focuses on developing a security system for houses, apartments, and other establishments using an Arduino-based fire, gas, and smoke alarm detector with a GSM module. The system utilizes infrared flame and Methane, Butane, LPG and Smoke Gas Sensor - MQ-2 detectors controlled by a microcontroller to detect fire and gas incidents. The overall project is divided into two parts: hardware development and software programming for a mobile application that connects to the device to operate the hardware structure.', 'Jay-R Jhan G. Cuario; Mark Cristian T. Bongat; Julius D. Dela Cruz; Chirstina C. Satumbaga; Ian Kenneth I. Pilien', '', '', '', '', '191_arduino_base_fire__gas__smoke_alarm_detector_with_gsm_module.pdf', 1, 38, 5, 'published', '2024-01-30 17:48:32', '2024-03-18 12:19:37'),
(192, 'Arduino - Based Human Motion Sensor Alarm System', 'The Arduino - Based Human Motion Sensor Alarm System is a proposal presented to the Faculty of the College of Information and Computing Science at Zamboanga Peninsula Polytechnic State University. It is a project aimed at developing a security system using Arduino and motion sensors to enhance the security of dormitories and other establishments. The system detects unauthorized movement in restricted areas, sends SMS notifications to the owner, and activates an alarm if intruders try to enter. The project&amp;amp;amp;amp;amp;amp;#039;s main objective is to provide an effective and low-cost security solution that can be easily programmed by users. This proposal outlines the purpose, description, objectives, scope, and limitations of the study. It also includes a table of contents and an executive summary that highlights the key points of the project.', 'Bagcat, Venus E.; Basir, Siti Sharmiza O.; Dacula, Zyrene Anne Marie A.; Salikala, Datu Alrasdi D.', 'Mr.Ian Kenneth I. Pilien', '', '', '', '192_arduino___based_human_motion_sensor_alarm_system.pdf', 1, 39, 5, 'published', '2024-01-30 17:51:02', '2024-01-30 19:08:21');

-- --------------------------------------------------------

--
-- Table structure for table `student_acc`
--

CREATE TABLE `student_acc` (
  `id` int(11) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `id_number` int(11) NOT NULL,
  `type_of_access` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `added_after_last_visit` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `student_acc`
--

INSERT INTO `student_acc` (`id`, `firstname`, `lastname`, `email`, `password`, `id_number`, `type_of_access`, `created_at`, `added_after_last_visit`) VALUES
(23, 'pajiri', 'pajiri', 'admin@archive.com', '$2y$10$sjJICAzfauSze7lV75pG9u/mVDNtUKuTSAsOL5b6lh083NkVukVCy', 123345, 0, '2024-01-26 01:00:39', 1),
(40, 'mamBelyn ', 'Enguerra', 'belyn@gmail.com', '$2y$10$jVPWq/FyaGr6YyMsA6VYDuoN9hxsp/uZJK/AKhfdaY7hNOKyhfYue', 12345, 0, '2024-01-26 01:00:39', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_year_and_section`
--

CREATE TABLE `tbl_year_and_section` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_year_and_section`
--

INSERT INTO `tbl_year_and_section` (`id`, `name`) VALUES
(38, '2020'),
(39, '2021'),
(40, '2022'),
(41, '2023'),
(83, '2024'),
(84, '2024');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `acc_user`
--
ALTER TABLE `acc_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookmarks`
--
ALTER TABLE `bookmarks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts_archive`
--
ALTER TABLE `posts_archive`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_acc`
--
ALTER TABLE `student_acc`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_year_and_section`
--
ALTER TABLE `tbl_year_and_section`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `acc_user`
--
ALTER TABLE `acc_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `bookmarks`
--
ALTER TABLE `bookmarks`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `posts_archive`
--
ALTER TABLE `posts_archive`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=220;

--
-- AUTO_INCREMENT for table `student_acc`
--
ALTER TABLE `student_acc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;

--
-- AUTO_INCREMENT for table `tbl_year_and_section`
--
ALTER TABLE `tbl_year_and_section`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
