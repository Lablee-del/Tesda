-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 09, 2025 at 01:52 PM
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
(7, 'A.01.a', 'ARCHFILE FOLDER, Tagila Lock', 'pc', NULL, NULL, 0),
(8, 'A.02.a', 'AIR FRESHINER REFILL, Automatic Spray Refill(glade), 269ml/175g', 'can', NULL, NULL, 0),
(9, 'A.03.a', 'ALCOHOL, 70% ethy/isopropyl, with moisturizer, gallon', 'gallon', NULL, NULL, 0),
(10, 'A.03.b', 'ALCOHOL, 70% ethyl/isopropyl, 500ml', 'bottle', NULL, NULL, 0),
(11, 'B.01.a', 'BATTERY, dry cell, AA, 4pcs/pack, 1.5V, heavy duty', 'pack', NULL, NULL, 0),
(12, 'B.01.b', 'BATTERY, dry cell, AAA, 4pcs/pack, 1.5V, heavy duty', 'pack', NULL, NULL, 0),
(13, 'B.01.c', 'BATTERY, dry cell, 9V1', 'pc', NULL, NULL, 0),
(14, 'B.01.d', 'BATTERY, Li-on for thermo scanner', 'pc', NULL, NULL, 0),
(15, 'B.02.a', 'BLEACH, Zonrox', 'gallon', NULL, NULL, 0),
(16, 'C.01.a', 'CALCULATOR', 'pc', NULL, NULL, 0),
(17, 'C.02.a', 'CERTIFICATE HOLDER, A4', 'pc', NULL, NULL, 0),
(18, 'C.03.a', 'CLIP, backfold, large, 41mm, 12pcs/box', 'box', NULL, NULL, 0),
(19, 'C.03.b', 'CLIP, backfold, medium, 25mm, 12pcs/box', 'box', NULL, NULL, 0),
(20, 'C.03.c', 'CLIP, backfold, small, 19mm, 12pcs/box', 'box', NULL, NULL, 0),
(21, 'C.03.d', 'CLIP, backfold, extra small, 15mm, 12pcs/box', 'box', NULL, NULL, 0),
(22, 'C.04.a', 'CORRECTION TAPE, film based', 'pc', NULL, NULL, 0),
(23, 'C.05.a', 'CUTTER PAPER, blade/knife', 'pc', NULL, NULL, 0),
(24, 'C.06.a', 'CLING WRAP, 12inches x 300meters', 'roll', NULL, NULL, 0),
(25, 'D.01.a', 'DISHWASHING LIQUID, 500ml', 'bottle', NULL, NULL, 0),
(26, 'D.02.a', 'DISINFECTANT SPRAY, aerosol type', 'can', NULL, NULL, 0),
(27, 'D.03.a', 'DRAWER LOCK, set with key', 'set', NULL, NULL, 0),
(28, 'E.01.a', 'ENVELOPE EXPANDABLE , brown, long', 'pc', NULL, NULL, 0),
(29, 'F.01.a', 'FASTENER, plastic', 'box', NULL, NULL, 0),
(30, 'F.02.a', 'FOLDER, Tag Board, White, 100pcs/pack, Long', 'pack', NULL, NULL, 0),
(31, 'F.02.b', 'FOLDER EXPANDING, Long, pressboard 100pcs/pack, white & blue', 'pack', NULL, NULL, 0),
(32, 'F.03.a', 'FABRIC CONDITIONER, Softener', 'gallon', NULL, NULL, 0),
(33, 'G.01.a', 'GLUE STICK, all purpose, 22 grams,', 'pc', NULL, NULL, 0),
(34, 'G.02.a', 'GLASS CLEANER, with Spray cap 500ml', 'bottle', NULL, NULL, 0),
(35, 'H.01.a', 'HANDSOAP, Liquid, 500ml', 'btl', NULL, NULL, 0),
(36, 'I.01.a', 'INDEX TAB', 'box', NULL, NULL, 0),
(37, 'I.02.a', 'INK, Canon, GI 790, Magenta', 'bottle', NULL, NULL, 0),
(38, 'I.02.b', 'INK, Canon, GI 790, Yellow', 'bottle', NULL, NULL, 0),
(39, 'I.02.c', 'INK, Canon, GI 790, Black', 'bottle', NULL, NULL, 0),
(40, 'I.02.d', 'INK, Canon, GI 790, Cyan', 'bottle', NULL, NULL, 0),
(41, 'I.03.a', 'INK HP, 682, black', 'cart', NULL, NULL, 0),
(42, 'I.03.b', 'INK HP, 682, colored', 'cart', NULL, NULL, 0),
(43, 'I.04.a', 'INK, Canon, 810 Black', 'cart', NULL, NULL, 0),
(44, 'I.04.b', 'INK, Canon, 811 Colored', 'cart', NULL, NULL, 0),
(45, 'I.05.a', 'INK, Epson 003, Black', 'bottle', NULL, NULL, 0),
(46, 'I.05.b', 'INK, Epson 003, Cyan', 'bottle', NULL, NULL, 0),
(47, 'I.05.c', 'INK, Epson 003, Magenta', 'bottle', NULL, NULL, 0),
(48, 'I.05.d', 'INK, Epson 003, Yellow', 'bottle', NULL, NULL, 0),
(49, 'I.06.a', 'INSECTICIDE, Aerosol type, waterbased, 600ml/can', 'can', NULL, NULL, 0),
(50, 'K.01.a', 'KITCHEN TOWEL, Paper Towel, roll, 2ply', 'roll', NULL, NULL, 0),
(51, 'L.01.a', 'LED BULB', 'pc', NULL, NULL, 0),
(52, 'N.01.a', 'NOTARIAL SEAL', 'pack', NULL, NULL, 0),
(53, 'N.02.a', 'NOTE PAD, stick on, 2\"x3\"', 'pc', NULL, NULL, 0),
(54, 'N.02.b', 'NOTE PAD, stick on, 3\"x3\"', 'pc', NULL, NULL, 0),
(55, 'N.02.c', 'NOTE PAD, stick on, 4\"x3\"', 'pc', NULL, NULL, 0),
(56, 'N.02.d', 'NOTE PAD, stick on, d3-4 (4\'s -1\"x3\")', 'pc', NULL, NULL, 0),
(57, 'P.01.a', 'PAPER, Board, A4, white, 180gsm, 100sheets/pack', 'pack', NULL, NULL, 0),
(58, 'P.01.b', 'PAPER, Board, A4, white, 200gsm, 100sheets/pack', 'pack', NULL, NULL, 0),
(59, 'P.01.c', 'PAPER, Board, Morocco, A4, 200gsm, 100sheets/pack', 'pack', NULL, NULL, 0),
(60, 'P.02.a', 'PAPER CLIP, 50mm, jumbo, vinyl coated', 'box', NULL, NULL, 0),
(61, 'P.02.b', 'PAPER CLIP, 33mm, vinyl coated', 'box', NULL, NULL, 0),
(62, 'P.03.a', 'PAPER, Multicopy, PPC, s20, 8.5\" x 13\"', 'ream', NULL, NULL, 0),
(63, 'P.03.b', 'PAPER, Multicopy, PPC, s20, 8.5\" x 14\"', 'ream', NULL, NULL, 0),
(64, 'P.03.c', 'PAPER, Multicopy, PPC, s20, A4', 'ream', NULL, NULL, 0),
(65, 'P.03.d', 'PAPER, Multicopy, PPC, s20, Short', 'ream', NULL, NULL, 0),
(66, 'P.04.a', 'PEN SIGN, gel or liquid ink, retractable, 0.7mm Black/ Blue, 12pcs/box', 'box', NULL, NULL, 0),
(67, 'p.04.b', 'PEN SIGN, Hi-tecpoint V10Grip, 1.0, 12pcs/box, Black/Blue', 'box', NULL, NULL, 0),
(68, 'P.04.c', 'PEN, ballpoint, retractable, 0.7mm, Black/Blue', 'box', NULL, NULL, 0),
(69, 'P.04.d', 'PEN, Fine, Retractable, 0.5mm', 'pc', NULL, NULL, 0),
(70, 'P.05.a', 'POST IT- Sticky Note, \"Sign Here\", \"Please Sign\",', 'pack', NULL, NULL, 0),
(71, 'P.06.a', 'PUSH PINS, 100pcs/box', 'box', NULL, NULL, 0),
(72, 'R.01.a', 'RECORD BOOK, Logbook, 300 pages', 'pc', NULL, NULL, 0),
(73, 'R.02.a', 'RULER, Steel, 12 inches', 'pc', NULL, NULL, 0),
(74, 'R.03.a', 'RAGS', 'pc', NULL, NULL, 0),
(75, 'S.01.a', 'STAPLER', 'pc', NULL, NULL, 0),
(76, 'S.01.b', 'STAPLE WIRE, Standard, 5000 staples/box', 'box', NULL, NULL, 0),
(77, 'S.01.c', 'STAPLE WIRE, Bostitch, 5000 staples/box', 'box', NULL, NULL, 0),
(78, 'S.01.d', 'STAPLER REMOVER', 'pc', NULL, NULL, 0),
(79, 'S.02.a', 'SCOURING PAD, Dishwashing sponge', 'pc', NULL, NULL, 0),
(80, 'T.01.a', 'TAPE, clear, 1inch', 'roll', NULL, NULL, 0),
(81, 'T.01.b', 'TAPE, Cloth, Duct tape', 'roll', NULL, NULL, 0),
(82, 'T.01.c', 'TAPE, double sided, 1inch', 'roll', NULL, NULL, 0),
(83, 'T.01.d', 'TAPE, Packing, 2\"', 'roll', NULL, NULL, 0),
(84, 'T.01.e', 'TAPE, transparent, 2\"', 'roll', NULL, NULL, 0),
(85, 'T.01.f', 'TAPE, transparent, 3\"', 'roll', NULL, NULL, 0),
(86, 'T.02.a', 'TAPE, refill for Epson LW-K400 printer/label 12mm', 'pcs', NULL, NULL, 0),
(87, 'T.03.a', 'TAPE DISPENSER', 'pc', NULL, NULL, 0),
(88, 'T.04.a', 'TOILET BOWL BRUSH, round headed brush', 'pc', NULL, NULL, 0),
(89, 'T.04.b', 'TOILET BOWL CLEANER, Liquid, 900ml', 'bottle', NULL, NULL, 0),
(90, 'T.05.a', 'TISSUE BATHROOM, Green Tea, 180g, 10pcs/pack', 'pack', NULL, NULL, 0),
(91, 'T.05.b', 'TISSUE FACIAL, Econo Box, 2ply, 200-250pulls', 'box', NULL, NULL, 0),
(92, 'T.05.c', 'TOILET TISSUE PAPER, 2ply, 12\'s per pack, 1000 sheets per roll', 'pack', NULL, NULL, 0),
(93, 'U.01.a', 'USB, Flash Drive, 64GB', 'pc', NULL, NULL, 0);

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
(14, '123', 'qweq', 'qwe', 'qwe', 'qe', '2025/07/0001', '2025-07-09', '', '123', 'qwe', 'qwe', 'qe', '2025-07-09 11:47:50');

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
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- AUTO_INCREMENT for table `ris`
--
ALTER TABLE `ris`
  MODIFY `ris_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `ris_items`
--
ALTER TABLE `ris_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=648;

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
