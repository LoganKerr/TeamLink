-- phpMyAdmin SQL Dump
-- version 4.7.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Sep 20, 2018 at 03:12 AM
-- Server version: 5.6.35
-- PHP Version: 7.1.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `venture`
--

-- --------------------------------------------------------

--
-- Table structure for table `faculty`
--

CREATE TABLE `faculty` (
`id` int(11) NOT NULL,
`department` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
`id` int(11) NOT NULL,
`role` varchar(256) NOT NULL,
`team_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role`, `team_id`) VALUES
(8, 'Owner', 27),
(10, 'Singer', 27),
(12, 'DRUMMER', 27);

-- --------------------------------------------------------

--
-- Table structure for table `role_assoc`
--

CREATE TABLE `role_assoc` (
`id` int(11) NOT NULL,
`user_id` int(11) DEFAULT NULL,
`team_id` int(11) NOT NULL,
`role_id` int(11) NOT NULL,
`selected` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `role_assoc`
--

INSERT INTO `role_assoc` (`id`, `user_id`, `team_id`, `role_id`, `selected`) VALUES
(15, 13, 27, 8, 1);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
`id` int(11) NOT NULL,
`major` varchar(256) NOT NULL,
`interests` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `major`, `interests`) VALUES
(8, 'Computer Science', '<b>No XSS here baby</b>'),
(9, '<image></image>', 'hofstra');

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE `teams` (
`id` int(11) NOT NULL,
`owner` int(11) NOT NULL,
`title` varchar(256) NOT NULL,
`description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `teams`
--

INSERT INTO `teams` (`id`, `owner`, `title`, `description`) VALUES
(27, 13, 'Band', 'we spit fire');

-- --------------------------------------------------------

--
-- Table structure for table `universities`
--

CREATE TABLE `universities` (
`id` int(11) NOT NULL,
`title` varchar(256) NOT NULL,
`expired` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `universities`
--

INSERT INTO `universities` (`id`, `title`, `expired`) VALUES
(1, 'Hofstra', 0),
(2, 'Stony Brook', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
`id` int(11) NOT NULL,
`university_id` int(11) DEFAULT NULL,
`student_id` int(11) DEFAULT NULL,
`faculty_id` int(11) DEFAULT NULL,
`admin` tinyint(1) NOT NULL,
`email` varchar(256) NOT NULL,
`firstName` varchar(256) NOT NULL,
`lastName` varchar(256) NOT NULL,
`passHash` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `university_id`, `student_id`, `faculty_id`, `admin`, `email`, `firstName`, `lastName`, `passHash`) VALUES
(13, 1, 8, NULL, 1, 'lkerr1998@gmail.com', 'Logan', 'Kerr', '$2y$10$s/d78kRd5V2bx4Y2wkMMp.K7QZglTMcgI/FzG5NC7HUgaam4xvm8e'),
(14, 1, 9, NULL, 1, 'lkerr2@pride.hofstra.edu', 'Derrick', 'Hamilton', '$2y$10$B3T7s.IVWqVDBBgRTJSPYedEc6gosedbwGSRKxWkzb6Z9lYe/..hW');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `faculty`
--
ALTER TABLE `faculty`
ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_assoc`
--
ALTER TABLE `role_assoc`
ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
ADD PRIMARY KEY (`id`);

--
-- Indexes for table `teams`
--
ALTER TABLE `teams`
ADD PRIMARY KEY (`id`);

--
-- Indexes for table `universities`
--
ALTER TABLE `universities`
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
-- AUTO_INCREMENT for table `faculty`
--
ALTER TABLE `faculty`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `role_assoc`
--
ALTER TABLE `role_assoc`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `teams`
--
ALTER TABLE `teams`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
--
-- AUTO_INCREMENT for table `universities`
--
ALTER TABLE `universities`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
