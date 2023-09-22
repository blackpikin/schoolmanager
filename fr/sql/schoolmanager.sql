-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 18, 2023 at 06:32 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `schoolmanager`
--

-- --------------------------------------------------------

--
-- Table structure for table `absences`
--

CREATE TABLE `absences` (
  `id` int(11) NOT NULL,
  `student_code` text NOT NULL,
  `academic_year` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `term` text NOT NULL,
  `absences` int(11) NOT NULL,
  `punishment` int(11) NOT NULL,
  `warning` int(11) NOT NULL,
  `suspension` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `academicyear_class`
--

CREATE TABLE `academicyear_class` (
  `id` int(11) NOT NULL,
  `student_code` text DEFAULT NULL,
  `academic_year_id` int(11) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `academic_year`
--

CREATE TABLE `academic_year` (
  `id` int(11) NOT NULL,
  `start` text DEFAULT NULL,
  `end` text DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `id` int(11) NOT NULL,
  `general_name` text DEFAULT NULL,
  `sub_name` text DEFAULT NULL,
  `cycle` text DEFAULT NULL,
  `mockable` int(11) DEFAULT NULL,
  `section` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `computed_averages`
--

CREATE TABLE `computed_averages` (
  `id` int(11) NOT NULL,
  `student` text NOT NULL,
  `exam_id` int(11) NOT NULL,
  `year_id` int(11) NOT NULL,
  `position` int(11) NOT NULL,
  `term` text NOT NULL,
  `average` decimal(11,2) NOT NULL,
  `overall_remark` text NOT NULL,
  `annual_av` decimal(11,2) NOT NULL,
  `class_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `computed_totals`
--

CREATE TABLE `computed_totals` (
  `id` int(11) NOT NULL,
  `subject` text NOT NULL,
  `student` text NOT NULL,
  `exam_id` int(11) NOT NULL,
  `year_id` int(11) NOT NULL,
  `total` decimal(11,2) NOT NULL,
  `rank` int(11) NOT NULL,
  `remark` text NOT NULL,
  `grade` text NOT NULL,
  `subject_group` int(11) NOT NULL,
  `class_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `conducts`
--

CREATE TABLE `conducts` (
  `id` int(11) NOT NULL,
  `typeof` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `tittle` text NOT NULL,
  `description` text NOT NULL,
  `photo` longblob NOT NULL,
  `photo_ext` text NOT NULL,
  `student_code` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `discount`
--

CREATE TABLE `discount` (
  `id` int(11) NOT NULL,
  `student_code` text NOT NULL,
  `discount_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `dateof` date NOT NULL,
  `monthYear` text NOT NULL,
  `yearOnly` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `event` text NOT NULL,
  `dateof` date NOT NULL,
  `duration` int(11) NOT NULL,
  `colored` varchar(255) NOT NULL,
  `monthYear` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exams`
--

CREATE TABLE `exams` (
  `id` int(11) NOT NULL,
  `term` text DEFAULT NULL,
  `sequence` text DEFAULT NULL,
  `academic_year` int(11) DEFAULT NULL,
  `weighted` int(11) NOT NULL DEFAULT 0,
  `percentage` int(11) NOT NULL DEFAULT 100,
  `status` int(11) DEFAULT NULL,
  `section` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `receiver` text NOT NULL,
  `expense_id` int(11) NOT NULL,
  `reason` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `dateof` date NOT NULL,
  `monthYear` text NOT NULL,
  `yearOnly` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expense_sources`
--

CREATE TABLE `expense_sources` (
  `id` int(11) NOT NULL,
  `source` text NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fees`
--

CREATE TABLE `fees` (
  `id` int(11) NOT NULL,
  `student_code` text NOT NULL,
  `academic_year` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `totalpaid` int(11) NOT NULL,
  `dateof` date NOT NULL DEFAULT current_timestamp(),
  `monthYear` text NOT NULL,
  `yearOnly` text NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fee_settings`
--

CREATE TABLE `fee_settings` (
  `id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `totalfee` int(11) NOT NULL,
  `registration` int(11) NOT NULL,
  `pta` int(11) NOT NULL,
  `first_ins` int(11) NOT NULL,
  `second_ins` int(11) NOT NULL,
  `typeof` text NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mark_sheet`
--

CREATE TABLE `mark_sheet` (
  `id` int(11) NOT NULL,
  `student_code` text DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `academic_year` int(11) DEFAULT NULL,
  `subject` text DEFAULT NULL,
  `exam` int(11) DEFAULT NULL,
  `mark` decimal(11,2) DEFAULT NULL,
  `competence` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `other_cash`
--

CREATE TABLE `other_cash` (
  `id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `source` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `dateof` date NOT NULL,
  `monthYear` text NOT NULL,
  `yearOnly` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pupils`
--

CREATE TABLE `pupils` (
  `id` int(11) NOT NULL,
  `name` text DEFAULT NULL,
  `gender` text DEFAULT NULL,
  `dob` text DEFAULT NULL,
  `pob` text DEFAULT NULL,
  `guardian` text DEFAULT NULL,
  `guardian_number` text DEFAULT NULL,
  `guardian_email` text DEFAULT NULL,
  `guardian_address` text DEFAULT NULL,
  `picture` longblob DEFAULT NULL,
  `picture_ext` text DEFAULT NULL,
  `student_code` text DEFAULT NULL,
  `mother_name` text NOT NULL,
  `father_name` text NOT NULL,
  `adm_num` text NOT NULL,
  `section` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reasons`
--

CREATE TABLE `reasons` (
  `id` int(11) NOT NULL,
  `reason` text DEFAULT NULL,
  `percent` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `revenue_sources`
--

CREATE TABLE `revenue_sources` (
  `id` int(11) NOT NULL,
  `source` text NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `school_info`
--

CREATE TABLE `school_info` (
  `id` int(11) NOT NULL,
  `name` text DEFAULT NULL,
  `motto` text DEFAULT NULL,
  `email` text DEFAULT NULL,
  `phone` text DEFAULT NULL,
  `website` text DEFAULT NULL,
  `pobox` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff_files`
--

CREATE TABLE `staff_files` (
  `id` int(11) NOT NULL,
  `doc_name` text DEFAULT NULL,
  `doc_data` longblob DEFAULT NULL,
  `data_ext` text DEFAULT NULL,
  `dateof` text DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff_subjects`
--

CREATE TABLE `staff_subjects` (
  `id` int(11) NOT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `academic_year` int(11) DEFAULT NULL,
  `class_id` text DEFAULT NULL,
  `subject` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `name` text DEFAULT NULL,
  `gender` text DEFAULT NULL,
  `dob` text DEFAULT NULL,
  `pob` text DEFAULT NULL,
  `guardian` text DEFAULT NULL,
  `guardian_number` text DEFAULT NULL,
  `guardian_email` text DEFAULT NULL,
  `guardian_address` text DEFAULT NULL,
  `picture` longblob DEFAULT NULL,
  `picture_ext` text DEFAULT NULL,
  `student_code` text DEFAULT NULL,
  `mother_name` text NOT NULL,
  `father_name` text NOT NULL,
  `adm_num` text NOT NULL,
  `section` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_files`
--

CREATE TABLE `student_files` (
  `id` int(11) NOT NULL,
  `doc_name` text DEFAULT NULL,
  `doc_data` longblob DEFAULT NULL,
  `data_ext` text DEFAULT NULL,
  `dateof` text DEFAULT NULL,
  `student_code` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `subject` text DEFAULT NULL,
  `class_name` int(11) DEFAULT NULL,
  `coef` int(11) DEFAULT NULL,
  `rep_group` int(11) NOT NULL,
  `section` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` text DEFAULT NULL,
  `role` varchar(255) DEFAULT NULL,
  `phone` text DEFAULT NULL,
  `email` text DEFAULT NULL,
  `subjects` text DEFAULT NULL,
  `password` text DEFAULT NULL,
  `dateof` datetime(6) DEFAULT NULL,
  `section` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32 COLLATE=utf32_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absences`
--
ALTER TABLE `absences`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `academicyear_class`
--
ALTER TABLE `academicyear_class`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `academic_year`
--
ALTER TABLE `academic_year`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `start` (`start`) USING HASH,
  ADD UNIQUE KEY `end` (`end`) USING HASH;

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `computed_averages`
--
ALTER TABLE `computed_averages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `computed_totals`
--
ALTER TABLE `computed_totals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `conducts`
--
ALTER TABLE `conducts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `discount`
--
ALTER TABLE `discount`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exams`
--
ALTER TABLE `exams`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expense_sources`
--
ALTER TABLE `expense_sources`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fees`
--
ALTER TABLE `fees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fee_settings`
--
ALTER TABLE `fee_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mark_sheet`
--
ALTER TABLE `mark_sheet`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `other_cash`
--
ALTER TABLE `other_cash`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pupils`
--
ALTER TABLE `pupils`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reasons`
--
ALTER TABLE `reasons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `revenue_sources`
--
ALTER TABLE `revenue_sources`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `school_info`
--
ALTER TABLE `school_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff_files`
--
ALTER TABLE `staff_files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff_subjects`
--
ALTER TABLE `staff_subjects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_files`
--
ALTER TABLE `student_files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone` (`phone`) USING HASH,
  ADD UNIQUE KEY `email` (`email`) USING HASH;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absences`
--
ALTER TABLE `absences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `academicyear_class`
--
ALTER TABLE `academicyear_class`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `academic_year`
--
ALTER TABLE `academic_year`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `computed_averages`
--
ALTER TABLE `computed_averages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `computed_totals`
--
ALTER TABLE `computed_totals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `conducts`
--
ALTER TABLE `conducts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `discount`
--
ALTER TABLE `discount`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `exams`
--
ALTER TABLE `exams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expense_sources`
--
ALTER TABLE `expense_sources`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fees`
--
ALTER TABLE `fees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fee_settings`
--
ALTER TABLE `fee_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mark_sheet`
--
ALTER TABLE `mark_sheet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `other_cash`
--
ALTER TABLE `other_cash`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pupils`
--
ALTER TABLE `pupils`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reasons`
--
ALTER TABLE `reasons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `revenue_sources`
--
ALTER TABLE `revenue_sources`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `school_info`
--
ALTER TABLE `school_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff_files`
--
ALTER TABLE `staff_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff_subjects`
--
ALTER TABLE `staff_subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `student_files`
--
ALTER TABLE `student_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
