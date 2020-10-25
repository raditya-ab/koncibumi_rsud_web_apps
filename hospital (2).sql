-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 21, 2020 at 06:35 AM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hospital`
--

-- --------------------------------------------------------

--
-- Table structure for table `kunjungan`
--

CREATE TABLE `kunjungan` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `medical_number` varchar(32) DEFAULT NULL,
  `tanggal_kunjungan` datetime DEFAULT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kunjungan`
--

INSERT INTO `kunjungan` (`id`, `patient_id`, `medical_number`, `tanggal_kunjungan`, `doctor_id`, `created_at`) VALUES
(1, 1, '987654321', '2020-10-08 00:00:00', 1, '2020-10-08 17:02:42'),
(2, 1, '987654321', '2020-10-08 00:00:00', 1, '2020-10-08 17:02:52');

-- --------------------------------------------------------

--
-- Table structure for table `master_doctor`
--

CREATE TABLE `master_doctor` (
  `id` int(11) NOT NULL,
  `nik` varchar(32) DEFAULT NULL,
  `first_name` varchar(512) DEFAULT NULL,
  `last_name` varchar(512) DEFAULT NULL,
  `mobile_number` varchar(16) DEFAULT NULL,
  `poli` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `master_doctor`
--

INSERT INTO `master_doctor` (`id`, `nik`, `first_name`, `last_name`, `mobile_number`, `poli`, `status`, `created_at`) VALUES
(1, '123456', 'Josep', 'Tambunan', '0812345678', 1, 1, '2020-10-08 05:11:18');

-- --------------------------------------------------------

--
-- Table structure for table `master_farmasi`
--

CREATE TABLE `master_farmasi` (
  `id` int(11) NOT NULL,
  `nik` varchar(32) DEFAULT NULL,
  `first_name` varchar(32) DEFAULT NULL,
  `last_name` varchar(32) DEFAULT NULL,
  `mobile_number` varchar(16) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `master_medicine`
--

CREATE TABLE `master_medicine` (
  `id` int(11) NOT NULL,
  `brand` varchar(64) DEFAULT NULL,
  `name` varchar(256) DEFAULT NULL,
  `golongan` enum('I','II','G') DEFAULT NULL,
  `restricted` tinyint(1) DEFAULT NULL,
  `qty` varchar(8) DEFAULT NULL,
  `satuan` varchar(8) DEFAULT NULL,
  `description` longtext,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `master_medicine`
--

INSERT INTO `master_medicine` (`id`, `brand`, `name`, `golongan`, `restricted`, `qty`, `satuan`, `description`, `created_at`) VALUES
(1, 'Kalbe Farma', 'Procold', 'I', 0, '40', 'papan', 'Obat Panas', '2020-10-08 05:25:06'),
(3, 'Dexa Medika', 'Vicks 44', 'II', 1, '500', 'ml', 'Obat Batuk', '2020-10-08 05:26:30');

-- --------------------------------------------------------

--
-- Table structure for table `order_patient`
--

CREATE TABLE `order_patient` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `delivery_date` datetime DEFAULT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `doctor_approve_time` datetime DEFAULT NULL,
  `farmasi_id` int(11) DEFAULT NULL,
  `farmasi_approve_time` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `keluhan` varchar(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `order_patient`
--

INSERT INTO `order_patient` (`id`, `patient_id`, `delivery_date`, `doctor_id`, `doctor_approve_time`, `farmasi_id`, `farmasi_approve_time`, `created_at`, `updated_at`, `status`, `keluhan`) VALUES
(1, 1, '2020-10-20 17:10:11', NULL, NULL, NULL, NULL, '2020-10-20 17:10:11', NULL, 2, '1'),
(2, 1, '2020-10-20 17:58:03', NULL, NULL, NULL, NULL, '2020-10-20 17:58:03', NULL, 1, '1');

-- --------------------------------------------------------

--
-- Table structure for table `patient_login`
--

CREATE TABLE `patient_login` (
  `id` int(11) NOT NULL,
  `no_bpjs` varchar(32) DEFAULT NULL,
  `no_medrec` varchar(256) DEFAULT NULL,
  `password` varchar(512) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `last_activity` datetime DEFAULT NULL,
  `date_created` datetime DEFAULT NULL,
  `forgot_exp` varchar(512) DEFAULT NULL,
  `remembered_time` datetime DEFAULT NULL,
  `remembered_exp` varchar(512) DEFAULT NULL,
  `verification_code` varchar(512) DEFAULT NULL,
  `ip_address` varchar(16) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `remember_token` varchar(512) DEFAULT NULL,
  `first_name` varchar(32) DEFAULT NULL,
  `last_name` varchar(64) DEFAULT NULL,
  `dob` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `patient_login`
--

INSERT INTO `patient_login` (`id`, `no_bpjs`, `no_medrec`, `password`, `last_login`, `last_activity`, `date_created`, `forgot_exp`, `remembered_time`, `remembered_exp`, `verification_code`, `ip_address`, `status`, `remember_token`, `first_name`, `last_name`, `dob`) VALUES
(1, '123456789', '987654321', '$6$rounds=5000$saltsalt$1exCt/dOPxcYhFKNrpFEJZM3XdbauNE1U6SSfdUbaG/VkuiiZ9y002RSRJzuNQ03V0IN9WSP0CpiOXuoSRVIU0', '2020-10-21 10:03:30', '2020-10-21 10:03:30', '2020-10-20 12:10:51', NULL, NULL, NULL, NULL, NULL, NULL, 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJsb2NhbGhvc3QiLCJpYXQiOjE2MDMxODg2NTEsInVpZCI6IjEifQ.YCZi9A0W1LEvxaZGOoMkOwMC8X1n3JQnL20sKLRLvkI', 'Josep', 'Tambunan', '1989-07-13'),
(2, '4', '8', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1989-07-13');

-- --------------------------------------------------------

--
-- Table structure for table `patient_profile`
--

CREATE TABLE `patient_profile` (
  `id` int(11) NOT NULL,
  `patient_login_id` int(11) DEFAULT NULL,
  `first_name` varchar(64) DEFAULT NULL,
  `last_name` varchar(64) DEFAULT NULL,
  `mobile_number` varchar(16) DEFAULT NULL,
  `address` longtext,
  `latitude` float DEFAULT NULL,
  `longitude` float DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `gender` enum('L','P') DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `patient_profile`
--

INSERT INTO `patient_profile` (`id`, `patient_login_id`, `first_name`, `last_name`, `mobile_number`, `address`, `latitude`, `longitude`, `dob`, `gender`, `created_at`) VALUES
(1, 1, 'Josep', 'Tambunan', NULL, 'Jalan Raya Margonda No 31', 106.817, -6.22732, '1989-07-13', NULL, '2020-10-20 12:10:51');

-- --------------------------------------------------------

--
-- Table structure for table `receipt_detail`
--

CREATE TABLE `receipt_detail` (
  `id` int(11) NOT NULL,
  `receipt_header_id` int(11) DEFAULT NULL,
  `obat` int(11) DEFAULT NULL,
  `dosis` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `receipt_detail`
--

INSERT INTO `receipt_detail` (`id`, `receipt_header_id`, `obat`, `dosis`) VALUES
(1, 1, 1, 10),
(2, 1, 3, 20),
(3, 2, 1, 10),
(4, 2, 3, 20);

-- --------------------------------------------------------

--
-- Table structure for table `receipt_header`
--

CREATE TABLE `receipt_header` (
  `id` int(11) NOT NULL,
  `kunjungan_id` int(11) DEFAULT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `receipt_header`
--

INSERT INTO `receipt_header` (`id`, `kunjungan_id`, `doctor_id`, `created_at`) VALUES
(1, 1, 1, '2020-10-08 17:02:42'),
(2, 2, 1, '2020-10-08 17:02:52');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kunjungan`
--
ALTER TABLE `kunjungan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `master_doctor`
--
ALTER TABLE `master_doctor`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `master_farmasi`
--
ALTER TABLE `master_farmasi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `master_medicine`
--
ALTER TABLE `master_medicine`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_patient`
--
ALTER TABLE `order_patient`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `patient_login`
--
ALTER TABLE `patient_login`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `patient_profile`
--
ALTER TABLE `patient_profile`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `receipt_detail`
--
ALTER TABLE `receipt_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `receipt_header`
--
ALTER TABLE `receipt_header`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kunjungan`
--
ALTER TABLE `kunjungan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `master_doctor`
--
ALTER TABLE `master_doctor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `master_farmasi`
--
ALTER TABLE `master_farmasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `master_medicine`
--
ALTER TABLE `master_medicine`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `order_patient`
--
ALTER TABLE `order_patient`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `patient_login`
--
ALTER TABLE `patient_login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `patient_profile`
--
ALTER TABLE `patient_profile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `receipt_detail`
--
ALTER TABLE `receipt_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `receipt_header`
--
ALTER TABLE `receipt_header`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
