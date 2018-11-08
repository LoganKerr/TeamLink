-- phpMyAdmin SQL Dump
-- version 4.7.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Nov 08, 2018 at 08:32 PM
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

--
-- Dumping data for table `faculty`
--

INSERT INTO `faculty` (`id`, `department`) VALUES
(2, 'tech'),
(3, 'doot'),
(4, 'test');

-- --------------------------------------------------------

--
-- Table structure for table `interests`
--

CREATE TABLE `interests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `tag` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `interests`
--

INSERT INTO `interests` (`id`, `user_id`, `tag`) VALUES
(1, 13, 'test'),
(2, 13, 'test2');

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
(21, 'test', 28),
(23, 'test', 33),
(24, 'code monkey', 34),
(25, 'test2', 28);

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
(33, 13, 28, 21, 1),
(34, 13, 33, 23, 0),
(35, 13, 34, 24, 0);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `major` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `major`) VALUES
(8, ''),
(9, '<image></image>'),
(10, 'test'),
(11, 'test');

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
(28, 14, 'Book Club', 'We read books for fun!'),
(30, 14, 'test', 'test'),
(33, 13, 'Band (Name pending)', 'Let\'s start a rock band together!'),
(34, 13, 'Software Team', 'Let\'s get together and make some apps');

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
(14, 1, 9, NULL, 1, 'dhamilton1@pride.hofstra.edu', 'Derrick', 'Hamilton', '$2y$10$SyV.E8cAYy2UdOGwIZ9vducBefYnP2YgD0THLUnmTYs2ZZujzTEGG'),
(15, 1, NULL, 2, 0, 'test', 'Logan', 'Kerr', '$2y$10$n0YarW/O15EJ1FkMA82Rb.C7BHXu/HwS7db7bo1oNrZRwUVimv5aC'),
(16, 1, NULL, 3, 0, 'test1', 'logan', 'kerr', '$2y$10$21OrxkWs3gWr8MBKZao1jeRJiIbAM8zhw4iOrCHD8jfMltrru4a92'),
(17, 1, 11, NULL, 0, 'test@gmail.com', 'Logan', 'Kerr', '$2y$10$T5NZaxDxnP7QvYCrF9TLae65ZGkTtaA5C4UEqAPHPTJU7JqtCbddy');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `faculty`
--
ALTER TABLE `faculty`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `interests`
--
ALTER TABLE `interests`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `interests`
--
ALTER TABLE `interests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT for table `role_assoc`
--
ALTER TABLE `role_assoc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `teams`
--
ALTER TABLE `teams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
--
-- AUTO_INCREMENT for table `universities`
--
ALTER TABLE `universities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;