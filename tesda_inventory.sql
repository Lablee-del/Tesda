-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 08, 2025 at 12:24 AM
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
-- Database: `tesda_inventory`
--

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `item_id` int(11) NOT NULL,
  `stock_number` varchar(50) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `unit` varchar(50) DEFAULT NULL,
  `reorder_point` int(11) DEFAULT NULL,
  `unit_cost` decimal(10,2) DEFAULT NULL,
  `quantity_on_hand` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`item_id`, `stock_number`, `description`, `unit`, `reorder_point`, `unit_cost`, `quantity_on_hand`) VALUES
(3, 'A.01.a', 'ARCHFILE FOLDER, Tagila Lock ', 'pc', 4, 200.00, 50),
(4, 'A.02.a', 'AIR FRESHINER REFILL, Automatic Spray Refill(glade), 269ml/175g', 'can', 5, 200.00, 50);

-- --------------------------------------------------------

--
-- Table structure for table `ris`
--

CREATE TABLE `ris` (
  `ris_id` int(11) NOT NULL,
  `entity_name` varchar(255) DEFAULT NULL,
  `fund_cluster` varchar(100) DEFAULT NULL,
  `division` varchar(100) DEFAULT NULL,
  `office` varchar(100) DEFAULT NULL,
  `responsibility_center_code` varchar(100) DEFAULT NULL,
  `ris_no` varchar(100) DEFAULT NULL,
  `date_requested` date DEFAULT NULL,
  `purpose` text DEFAULT NULL,
  `requested_by` varchar(255) DEFAULT NULL,
  `approved_by` varchar(255) DEFAULT NULL,
  `issued_by` varchar(255) DEFAULT NULL,
  `received_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ris`
--

INSERT INTO `ris` (`ris_id`, `entity_name`, `fund_cluster`, `division`, `office`, `responsibility_center_code`, `ris_no`, `date_requested`, `purpose`, `requested_by`, `approved_by`, `issued_by`, `received_by`, `created_at`) VALUES
(1, 'aaa', 'aaa', 'aaaaa', 'aaa', 'aaa', 'aaa', '2000-10-20', 'aaa', '', '', '', '', '2025-07-07 20:31:31'),
(2, 'ahaa', 'sss', 'sss', 'sss', 'sss', 'sss', '2000-09-10', 'ja', '', '', '', '', '2025-07-07 20:37:26'),
(4, 'SAMPLE 1', 'TESDA', 'DIV 1', 'OFF', '', 'AUTO GENERATED', '2000-10-20', '', NULL, NULL, NULL, NULL, '2025-07-07 22:18:46');

-- --------------------------------------------------------

--
-- Table structure for table `ris_items`
--

CREATE TABLE `ris_items` (
  `item_id` int(11) NOT NULL,
  `ris_id` int(11) DEFAULT NULL,
  `stock_number` varchar(100) DEFAULT NULL,
  `stock_available` varchar(10) DEFAULT NULL,
  `issued_quantity` int(11) DEFAULT NULL,
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ris_items`
--

INSERT INTO `ris_items` (`item_id`, `ris_id`, `stock_number`, `stock_available`, `issued_quantity`, `remarks`) VALUES
(1, 1, 'A.01.a', 'Yes', 2, ''),
(2, 1, 'A.02.a', 'Yes', 3, ''),
(3, 2, 'A.01.a', 'Yes', 2, ''),
(4, 2, 'A.02.a', 'Yes', 3, ''),
(7, 4, 'A.01.a', 'Yes', 2, ''),
(8, 4, 'A.02.a', 'Yes', 5, '');

-- --------------------------------------------------------

--
-- Table structure for table `rpci`
--

CREATE TABLE `rpci` (
  `rpci_id` int(11) NOT NULL,
  `inventory_type` varchar(100) DEFAULT NULL,
  `as_of_date` date DEFAULT NULL,
  `fund_cluster` varchar(50) DEFAULT NULL,
  `article` varchar(100) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `stock_number` varchar(50) DEFAULT NULL,
  `unit` varchar(50) DEFAULT NULL,
  `unit_value` decimal(10,2) DEFAULT NULL,
  `balance_per_card` int(11) DEFAULT NULL,
  `on_hand_per_count` int(11) DEFAULT NULL,
  `shortage` int(11) DEFAULT 0,
  `overage` int(11) DEFAULT 0,
  `remarks` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rsmi`
--

CREATE TABLE `rsmi` (
  `rsmi_id` int(11) NOT NULL,
  `date_generated` datetime NOT NULL,
  `total_issued` int(11) NOT NULL,
  `month_year` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_card`
--

CREATE TABLE `stock_card` (
  `stock_card_id` int(11) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `transaction_date` date DEFAULT NULL,
  `reference` varchar(100) DEFAULT NULL,
  `receipt_qty` int(11) DEFAULT 0,
  `issue_qty` int(11) DEFAULT 0,
  `balance_qty` int(11) DEFAULT NULL,
  `issued_to_office` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `ris`
--
ALTER TABLE `ris`
  ADD PRIMARY KEY (`ris_id`);

--
-- Indexes for table `ris_items`
--
ALTER TABLE `ris_items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `ris_id` (`ris_id`);

--
-- Indexes for table `rpci`
--
ALTER TABLE `rpci`
  ADD PRIMARY KEY (`rpci_id`);

--
-- Indexes for table `rsmi`
--
ALTER TABLE `rsmi`
  ADD PRIMARY KEY (`rsmi_id`);

--
-- Indexes for table `stock_card`
--
ALTER TABLE `stock_card`
  ADD PRIMARY KEY (`stock_card_id`),
  ADD KEY `item_id` (`item_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `ris`
--
ALTER TABLE `ris`
  MODIFY `ris_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `ris_items`
--
ALTER TABLE `ris_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `rpci`
--
ALTER TABLE `rpci`
  MODIFY `rpci_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rsmi`
--
ALTER TABLE `rsmi`
  MODIFY `rsmi_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_card`
--
ALTER TABLE `stock_card`
  MODIFY `stock_card_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ris_items`
--
ALTER TABLE `ris_items`
  ADD CONSTRAINT `ris_items_ibfk_1` FOREIGN KEY (`ris_id`) REFERENCES `ris` (`ris_id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_card`
--
ALTER TABLE `stock_card`
  ADD CONSTRAINT `stock_card_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
