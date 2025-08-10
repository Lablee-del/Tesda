-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 10, 2025 at 07:06 PM
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
-- Table structure for table `inventory_entries`
--

CREATE TABLE `inventory_entries` (
  `entry_id` int(11) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `unit_cost` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory_entries`
--

INSERT INTO `inventory_entries` (`entry_id`, `item_id`, `quantity`, `unit_cost`, `created_at`, `is_active`) VALUES
(306, 223, 11, 11.00, '2025-08-10 16:26:45', 1),
(307, 223, -5, 0.00, '2025-08-10 16:27:10', 1),
(308, 223, 13, 5.00, '2025-08-10 16:27:20', 1),
(309, 224, 1, 100.00, '2025-08-10 16:48:12', 1),
(310, 225, 1, 100.00, '2025-08-10 16:49:32', 1),
(311, 223, 20, 20.00, '2025-08-10 16:58:29', 1);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `item_id` int(11) NOT NULL,
  `stock_number` varchar(50) NOT NULL,
  `item_name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `unit` varchar(50) NOT NULL,
  `reorder_point` int(11) DEFAULT NULL,
  `parent_item_id` int(11) DEFAULT NULL,
  `quantity_on_hand` int(11) DEFAULT 0,
  `unit_cost` decimal(10,4) DEFAULT NULL,
  `initial_quantity` int(11) DEFAULT 0,
  `average_unit_cost` decimal(10,4) DEFAULT NULL,
  `calculated_unit_cost` decimal(10,4) DEFAULT NULL,
  `calculated_quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`item_id`, `stock_number`, `item_name`, `description`, `unit`, `reorder_point`, `parent_item_id`, `quantity_on_hand`, `unit_cost`, `initial_quantity`, `average_unit_cost`, `calculated_unit_cost`, `calculated_quantity`) VALUES
(1, 'A.01.a', 'ARCHFILE FOLDER', 'Tagila Lock', 'pc', 2, NULL, 8, 11.0000, 8, NULL, NULL, NULL),
(2, 'A.02.a', 'AIR FRESHINER REFILL', 'Automatic Spray Refill(glade)', 'can', 0, NULL, 0, 10.0000, 0, NULL, NULL, NULL),
(3, 'A.03.a', 'ALCOHOL', '70% ethy/isopropyl, with moisturizer, gallon', 'gallon', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(4, 'A.03.b', 'ALCOHOL', '70% ethyl/isopropyl, 500ml', 'bottle', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(5, 'B.01.a', 'BATTERY', 'dry cell, AA, 4pcs/pack, 1.5V, heavy duty', 'pack', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(6, 'B.01.b', 'BATTERY', 'dry cell, AAA, 4pcs/pack, 1.5V, heavy duty', 'pack', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(7, 'B.01.c', 'BATTERY', 'dry cell, 9V1', 'pc', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(8, 'B.01.d', 'BATTERY', 'Li-on for thermo scanner', 'pc', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(9, 'B.02.a', 'BLEACH', 'Zonrox', 'gallon', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(10, 'C.01.a', 'CALCULATOR', '', 'pc', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(11, 'C.02.a', 'CERTIFICATE HOLDER', 'A4', 'pc', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(12, 'C.03.a', 'CLIP', 'backfold, large, 41mm, 12pcs/box', 'box', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(13, 'C.03.b', 'CLIP', 'backfold, medium, 25mm, 12pcs/box', 'box', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(14, 'C.03.c', 'CLIP', 'backfold, small, 19mm, 12pcs/box', 'box', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(15, 'C.03.d', 'CLIP', 'backfold, extra small, 15mm, 12pcs/box', 'box', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(16, 'C.04.a', 'CORRECTION TAPE', 'film based', 'pc', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(17, 'C.05.a', 'CUTTER PAPER', 'blade/knife', 'pc', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(18, 'C.06.a', 'CLING WRAP', '12inches x 300meters', 'roll', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(19, 'D.01.a', 'DISHWASHING LIQUID', '500ml', 'bottle', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(20, 'D.02.a', 'DISINFECTANT SPRAY', 'aerosol type', 'can', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(21, 'D.03.a', 'DRAWER LOCK', 'set with key', 'set', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(22, 'E.01.a', 'ENVELOPE EXPANDABLE', 'brown, long', 'pc', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(23, 'F.01.a', 'FASTENER', 'plastic', 'box', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(24, 'F.02.a', 'FOLDER', 'Tag Board, White, 100pcs/pack, Long', 'pack', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(25, 'F.02.b', 'FOLDER EXPANDING', 'Long, pressboard 100pcs/pack, white & blue', 'pack', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(26, 'F.03.a', 'FABRIC CONDITIONER', 'Softener', 'gallon', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(27, 'G.01.a', 'GLUE STICK', 'all purpose, 22 grams,', 'pc', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(28, 'G.02.a', 'GLASS CLEANER', 'with Spray cap 500ml', 'bottle', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(29, 'H.01.a', 'HANDSOAP', 'Liquid, 500ml', 'btl', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(30, 'I.01.a', 'INDEX TAB', '', 'box', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(31, 'I.02.a', 'INK', 'Canon, GI 790, Magenta', 'bottle', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(32, 'I.02.b', 'INK', 'Canon, GI 790, Yellow', 'bottle', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(33, 'I.02.c', 'INK', 'Canon, GI 790, Black', 'bottle', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(34, 'I.02.d', 'INK', 'Canon, GI 790, Cyan', 'bottle', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(35, 'I.03.a', 'INK HP', '682, black', 'cart', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(36, 'I.03.b', 'INK HP', '682, colored', 'cart', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(37, 'I.04.a', 'INK', 'Canon, 810 Black', 'cart', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(38, 'I.04.b', 'INK', 'Canon, 811 Colored', 'cart', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(39, 'I.05.a', 'INK', 'Epson 003, Black', 'bottle', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(40, 'I.05.b', 'INK', 'Epson 003, Cyan', 'bottle', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(41, 'I.05.c', 'INK', 'Epson 003, Magenta', 'bottle', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(42, 'I.05.d', 'INK', 'Epson 003, Yellow', 'bottle', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(43, 'I.06.a', 'INSECTICIDE', 'Aerosol type, waterbased, 600ml/can', 'can', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(44, 'K.01.a', 'KITCHEN TOWEL', 'Paper Towel, roll, 2ply', 'roll', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(45, 'L.01.a', 'LED BULB', '', 'pc', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(46, 'N.01.a', 'NOTARIAL SEAL', '', 'pack', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(47, 'N.02.a', 'NOTE PAD', 'stick on, 2\"x3\"', 'pc', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(48, 'N.02.b', 'NOTE PAD', 'stick on, 3\"x3\"', 'pc', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(49, 'N.02.c', 'NOTE PAD', 'stick on, 4\"x3\"', 'pc', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(50, 'N.02.d', 'NOTE PAD', 'stick on, d3-4 (4\'s -1\"x3\")', 'pc', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(51, 'P.01.a', 'PAPER', 'Board, A4, white, 180gsm, 100sheets/pack', 'pack', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(52, 'P.01.b', 'PAPER', 'Board, A4, white, 200gsm, 100sheets/pack', 'pack', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(53, 'P.01.c', 'PAPER', 'Board, Morocco, A4, 200gsm, 100sheets/pack', 'pack', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(54, 'P.02.a', 'PAPER CLIP', '50mm, jumbo, vinyl coated', 'box', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(55, 'P.02.b', 'PAPER CLIP', '33mm, vinyl coated', 'box', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(56, 'P.03.a', 'PAPER', 'Multicopy, PPC, s20, 8.5\" x 13\"', 'ream', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(57, 'P.03.b', 'PAPER', 'Multicopy, PPC, s20, 8.5\" x 14\"', 'ream', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(58, 'P.03.c', 'PAPER', 'Multicopy, PPC, s20, A4', 'ream', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(59, 'P.03.d', 'PAPER', 'Multicopy, PPC, s20, Short', 'ream', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(60, 'P.04.a', 'PEN SIGN', 'gel or liquid ink, retractable, 0.7mm Black/ Blue, 12pcs/box', 'box', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(61, 'p.04.b', 'PEN SIGN', 'Hi-tecpoint V10Grip, 1.0, 12pcs/box, Black/Blue', 'box', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(62, 'P.04.c', 'PEN', 'ballpoint, retractable, 0.7mm, Black/Blue', 'box', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(63, 'P.04.d', 'PEN', 'Fine, Retractable, 0.5mm', 'pc', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(64, 'P.05.a', 'POST IT- Sticky Note', '\"Sign Here\", \"Please Sign\",', 'pack', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(65, 'P.06.a', 'PUSH PINS', '100pcs/box', 'box', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(66, 'R.01.a', 'RECORD BOOK', 'Logbook, 300 pages', 'pc', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(67, 'R.02.a', 'RULER', 'Steel, 12 inches', 'pc', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(68, 'R.03.a', 'RAGS', '', 'pc', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(69, 'S.01.a', 'STAPLER', '', 'pc', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(70, 'S.01.b', 'STAPLE WIRE', 'Standard, 5000 staples/box', 'box', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(71, 'S.01.c', 'STAPLE WIRE', 'Bostitch, 5000 staples/box', 'box', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(72, 'S.01.d', 'STAPLER REMOVER', '', 'pc', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(73, 'S.02.a', 'SCOURING PAD', 'Dishwashing sponge', 'pc', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(74, 'T.01.a', 'TAPE', 'clear, 1inch', 'roll', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(75, 'T.01.b', 'TAPE', 'Cloth, Duct tape', 'roll', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(76, 'T.01.c', 'TAPE', 'double sided, 1inch', 'roll', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(77, 'T.01.d', 'TAPE', 'Packing, 2\"', 'roll', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(78, 'T.01.e', 'TAPE', 'transparent, 2\"', 'roll', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(79, 'T.01.f', 'TAPE', 'transparent, 3\"', 'roll', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(80, 'T.02.a', 'TAPE', 'refill for Epson LW-K400 printer/label 12mm', 'pcs', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(81, 'T.03.a', 'TAPE DISPENSER', '', 'pc', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(82, 'T.04.a', 'TOILET BOWL BRUSH', 'round headed brush', 'pc', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(83, 'T.04.b', 'TOILET BOWL CLEANER', 'Liquid, 900ml', 'bottle', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(84, 'T.05.a', 'TISSUE BATHROOM', 'Green Tea, 180g, 10pcs/pack', 'pack', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(85, 'T.05.b', 'TISSUE FACIAL', 'Econo Box, 2ply, 200-250pulls', 'box', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(86, 'T.05.c', 'TOILET TISSUE PAPER', '2ply, 12\'s per pack, 1000 sheets per roll', 'pack', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(87, 'U.01.a', 'USB', 'Flash Drive, 64GB', 'pc', NULL, NULL, 0, 0.0000, 0, 0.0000, NULL, NULL),
(223, '123', '123', '123', '123', 1, NULL, 49, 10.0000, 10, 12.7037, 12.7037, 49),
(224, 'A1', 'asd', 'asd', 'asd', 1, NULL, 101, 10.0000, 100, 10.8911, 10.8911, 101),
(225, 'A12', 'qwe', 'qwe', 'qwe', 1, NULL, 101, 10.0000, 100, 10.8911, 10.8911, 101),
(227, 'qwe1', 'qwe1', 'qwe1', 'qwe1', 2, NULL, 5, 40.0000, 5, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `item_history`
--

CREATE TABLE `item_history` (
  `history_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `stock_number` varchar(255) DEFAULT NULL,
  `item_name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `unit` varchar(50) DEFAULT NULL,
  `reorder_point` int(11) DEFAULT NULL,
  `unit_cost` decimal(10,2) DEFAULT NULL,
  `quantity_on_hand` int(11) DEFAULT NULL,
  `quantity_change` int(11) DEFAULT NULL,
  `change_direction` varchar(20) DEFAULT NULL,
  `changed_at` datetime DEFAULT current_timestamp(),
  `change_type` varchar(50) DEFAULT 'update',
  `ris_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `item_history`
--

INSERT INTO `item_history` (`history_id`, `item_id`, `stock_number`, `item_name`, `description`, `unit`, `reorder_point`, `unit_cost`, `quantity_on_hand`, `quantity_change`, `change_direction`, `changed_at`, `change_type`, `ris_id`) VALUES
(1, 1, 'A.01.a', NULL, 'Tagila Lock', 'pc', 10, 12.00, 20, 10, '0', '2025-08-05 14:33:26', 'entry', NULL),
(2, 1, 'A.01.a', NULL, 'Tagila Lock', 'pc', 10, 12.00, 31, 11, '0', '2025-08-05 14:33:39', 'entry', NULL),
(4, 1, 'A.01.a', 'ARCHFILE FOLDER', 'Tagila Lock', 'pc', 10, 12.00, 30, 10, 'increase', '2025-08-05 14:59:53', 'entry', NULL),
(15, 1, 'A.01.a', 'ARCHFILE FOLDER', 'Tagila Lock', 'pc', 10, 12.00, 30, 0, 'no_change', '2025-08-05 16:39:25', 'cleared', NULL),
(92, 1, 'A.01.a', 'ARCHFILE FOLDER', 'Tagila Lock', 'pc', 11, 12.00, 30, 0, 'no_change', '2025-08-06 01:43:49', 'update', NULL),
(416, 2, 'A.02.a', 'AIR FRESHINER REFILL', 'Automatic Spray Refill(glade)', 'can', 0, 10.00, 10, 0, 'no_change', '2025-08-06 20:28:58', 'update', NULL),
(417, 2, 'A.02.a', 'AIR FRESHINER REFILL', 'Automatic Spray Refill(glade)', 'can', 0, 11.00, 11, 1, 'increase', '2025-08-06 20:29:28', 'update', NULL),
(418, 2, 'A.02.a', 'AIR FRESHINER REFILL', 'Automatic Spray Refill(glade)', 'can', 0, 11.00, 23, 12, 'increase', '2025-08-06 20:31:56', 'entry', NULL),
(419, 2, 'A.02.a', 'AIR FRESHINER REFILL', 'Automatic Spray Refill(glade)', 'can', 0, 11.55, 22, -1, 'decrease', '2025-08-06 20:39:14', 'cleared', NULL),
(420, 2, 'A.02.a', 'AIR FRESHINER REFILL', 'Automatic Spray Refill(glade)', 'can', 0, 10.00, 10, -12, 'decrease', '2025-08-06 20:39:23', 'update', NULL),
(460, 1, 'A.01.a', 'ARCHFILE FOLDER', 'Tagila Lock', 'pc', 11, 12.00, 0, -30, 'decrease', '2025-08-06 23:55:35', 'update', NULL),
(469, 1, 'A.01.a', 'ARCHFILE FOLDER', 'Tagila Lock', 'pc', 11, 12.00, 12, 12, 'increase', '2025-08-07 00:05:55', 'selective_update', NULL),
(470, 1, 'A.01.a', 'ARCHFILE FOLDER', 'Tagila Lock', 'pc', 11, 12.00, 22, 10, 'increase', '2025-08-07 00:06:26', 'entry', NULL),
(471, 1, 'A.01.a', 'ARCHFILE FOLDER', 'Tagila Lock', 'pc', 11, 11.09, 22, 0, 'no_change', '2025-08-07 00:06:33', 'cleared', NULL),
(472, 1, 'A.01.a', 'ARCHFILE FOLDER', 'Tagila Lock', 'pc', 11, 10.00, 10, -12, 'decrease', '2025-08-07 00:06:45', 'selective_update', NULL),
(490, 1, 'A.01.a', 'ARCHFILE FOLDER', 'Tagila Lock', 'pc', 11, 11.00, 11, 1, 'increase', '2025-08-07 00:38:56', 'selective_update', NULL),
(556, 1, 'A.01.a', 'ARCHFILE FOLDER', 'Tagila Lock', 'pc', 2, 11.00, 11, 0, 'no_change', '2025-08-10 20:32:11', 'update', NULL),
(571, 1, 'A.01.a', 'ARCHFILE FOLDER', 'Tagila Lock', 'pc', 3, 11.00, 11, 0, 'no_change', '2025-08-10 21:16:30', 'update', NULL),
(583, 1, 'A.01.a', 'ARCHFILE FOLDER', 'Tagila Lock', 'pc', 2, 11.00, 11, 0, 'no_change', '2025-08-10 21:55:34', 'update', NULL),
(598, 1, 'A.01.a', 'ARCHFILE FOLDER', 'Tagila Lock', 'pc', 2, 11.00, 8, 0, 'no_change', '2025-08-10 22:36:55', 'cleared', NULL),
(621, 223, '123', '123', '123', '123', 1, 10.00, 10, 10, 'increase', '2025-08-11 00:16:25', 'add', NULL),
(622, 223, '123', '123', '123', '123', 1, 10.00, 21, 11, 'increase', '2025-08-11 00:16:31', 'entry', NULL),
(624, 223, '123', '123', '123', '123', 1, 13.81, 16, 0, 'no_change', '2025-08-11 00:23:59', 'cleared', NULL),
(625, 223, '123', '123', '123', '123', 1, 10.00, 10, 10, 'increase', '2025-08-11 00:24:15', 'update', NULL),
(626, 223, '123', '123', '123', '123', 1, 10.00, 21, 11, 'increase', '2025-08-11 00:24:20', 'entry', NULL),
(627, 223, '123', '123', '123', '123', 1, 10.52, 21, 0, 'no_change', '2025-08-11 00:24:43', 'cleared', NULL),
(628, 223, '123', '123', '123', '123', 1, 10.00, 10, 10, 'increase', '2025-08-11 00:24:53', 'update', NULL),
(629, 223, '123', '123', '123', '123', 1, 10.00, 21, 11, 'increase', '2025-08-11 00:26:45', 'entry', NULL),
(630, 223, '123', '123', '123', '123', 1, 10.00, 16, -5, 'decrease', '2025-08-11 00:27:10', 'issued', 48),
(631, 223, '123', '123', '123', '123', 1, 10.00, 29, 13, 'increase', '2025-08-11 00:27:20', 'entry', NULL),
(632, 224, 'A', 'asd', 'asd', 'asd', 1, 10.00, 100, 100, 'increase', '2025-08-11 00:47:14', 'add', NULL),
(633, 224, 'A1', 'asd', 'asd', 'asd', 1, 10.00, 100, 0, 'no_change', '2025-08-11 00:47:39', 'update', NULL),
(634, 224, 'A1', 'asd', 'asd', 'asd', 1, 10.00, 101, 1, 'increase', '2025-08-11 00:48:12', 'entry', NULL),
(635, 225, 'A12', 'qwe', 'qwe', 'qwe', 1, 10.00, 100, 100, 'increase', '2025-08-11 00:49:07', 'add', NULL),
(636, 225, 'A12', 'qwe', 'qwe', 'qwe', 1, 10.00, 101, 1, 'increase', '2025-08-11 00:49:32', 'entry', NULL),
(638, 223, '123', '123', '123', '123', 1, 10.00, 49, 20, 'increase', '2025-08-11 00:58:29', 'entry', NULL),
(640, 227, 'qwe', 'qwe', 'qwe', 'qwe', 10, 12.00, 12, 12, 'increase', '2025-08-11 00:59:43', 'add', NULL),
(641, 227, 'qwe', 'qwe', 'qwe', 'qwe', 10, 12.00, 32, 20, 'increase', '2025-08-11 00:59:55', 'entry', NULL),
(642, 227, 'qwe', 'qwe', 'qwe', 'qwe', 10, 12.00, 22, -10, 'decrease', '2025-08-11 01:00:55', 'issued', 49),
(643, 227, 'qwe', 'qwe', 'qwe', 'qwe', 10, 17.00, 22, 0, 'no_change', '2025-08-11 01:01:26', 'cleared', NULL),
(644, 227, 'qwe', 'qwe', 'qwe', 'qwe', 10, 10.00, 10, 10, 'increase', '2025-08-11 01:01:39', 'update', NULL),
(645, 227, 'qwe', 'qwe', 'qwe', 'qwe', 10, 10.00, 22, 12, 'increase', '2025-08-11 01:01:55', 'entry', NULL),
(646, 227, 'qwe1', 'qwe1', 'qwe1', 'qwe1', 2, 40.00, 5, 5, 'increase', '2025-08-11 01:02:58', 'update', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `item_history_archive`
--

CREATE TABLE `item_history_archive` (
  `history_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `stock_number` varchar(255) DEFAULT NULL,
  `item_name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `unit` varchar(50) DEFAULT NULL,
  `reorder_point` int(11) DEFAULT NULL,
  `unit_cost` decimal(10,2) DEFAULT NULL,
  `quantity_on_hand` int(11) DEFAULT NULL,
  `quantity_change` int(11) DEFAULT NULL,
  `change_direction` varchar(20) DEFAULT NULL,
  `changed_at` datetime DEFAULT current_timestamp(),
  `change_type` varchar(50) DEFAULT 'update',
  `reference_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `property_cards`
--

CREATE TABLE `property_cards` (
  `pc_id` int(11) NOT NULL,
  `entity_name` varchar(255) NOT NULL,
  `fund_cluster` varchar(50) NOT NULL,
  `ppe_type` varchar(255) NOT NULL COMMENT 'Property, Plant and Equipment type',
  `description` text NOT NULL COMMENT 'Description of the PPE (brand, size, color, serial no., etc.)',
  `property_number` varchar(100) NOT NULL COMMENT 'Number assigned by Supply/Property Division',
  `transaction_date` date NOT NULL COMMENT 'Date of acquisition/issue/transfer/disposal',
  `reference_par_no` varchar(100) DEFAULT NULL COMMENT 'Reference document or PAR number',
  `receipt_qty` decimal(10,2) DEFAULT 0.00 COMMENT 'Quantity received',
  `issue_qty` decimal(10,2) DEFAULT 0.00 COMMENT 'Quantity issued/transferred/disposed',
  `office_officer` varchar(255) DEFAULT NULL COMMENT 'Receiving office/officer name',
  `amount` decimal(15,2) DEFAULT 0.00 COMMENT 'Amount of PPE',
  `remarks` text DEFAULT NULL COMMENT 'Important information or comments',
  `transaction_type` enum('receipt','issue','transfer','disposal') NOT NULL DEFAULT 'receipt',
  `created_by` int(11) DEFAULT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `property_cards`
--

INSERT INTO `property_cards` (`pc_id`, `entity_name`, `fund_cluster`, `ppe_type`, `description`, `property_number`, `transaction_date`, `reference_par_no`, `receipt_qty`, `issue_qty`, `office_officer`, `amount`, `remarks`, `transaction_type`, `created_by`, `date_created`, `last_updated`) VALUES
(1, 'Department of Education', '01', 'Office Equipment', 'Desktop Computer - Dell OptiPlex 3070, Intel Core i5, 8GB RAM, 256GB SSD, Serial: DL123456', 'PPE-2024-001', '2024-01-15', 'IAR-2024-001', 1.00, 0.00, NULL, 45000.00, 'Brand new unit for Admin Office', 'receipt', NULL, '2025-08-05 06:20:22', '2025-08-05 06:20:22'),
(2, 'Department of Education', '01', 'Office Equipment', 'Desktop Computer - Dell OptiPlex 3070, Intel Core i5, 8GB RAM, 256GB SSD, Serial: DL123456', 'PPE-2024-001', '2024-01-20', 'PAR-2024-001', 0.00, 1.00, 'Admin Office - John Doe', 45000.00, 'Issued to Admin Office', 'issue', NULL, '2025-08-05 06:20:22', '2025-08-05 06:20:22'),
(3, 'Department of Education', '01', 'Furniture and Fixtures', 'Office Chair - Ergonomic Swivel Chair, Black Leather, Model: EC-2024', 'PPE-2024-002', '2024-01-16', 'IAR-2024-002', 5.00, 0.00, NULL, 12500.00, 'Set of 5 office chairs', 'receipt', NULL, '2025-08-05 06:20:22', '2025-08-05 06:20:22'),
(4, 'Department of Education', '01', 'Furniture and Fixtures', 'Office Chair - Ergonomic Swivel Chair, Black Leather, Model: EC-2024', 'PPE-2024-002', '2024-01-22', 'PAR-2024-002', 0.00, 3.00, 'HR Department - Jane Smith', 7500.00, 'Issued 3 chairs to HR Dept', 'issue', NULL, '2025-08-05 06:20:22', '2025-08-05 06:20:22'),
(5, 'Department of Education', '02', 'IT Equipment', 'Printer - HP LaserJet Pro M404dn, Monochrome, Network Ready, Serial: HP789012', 'PPE-2024-003', '2024-01-18', 'IAR-2024-003', 2.00, 0.00, NULL, 24000.00, 'Network printers for offices', 'receipt', NULL, '2025-08-05 06:20:22', '2025-08-05 06:20:22'),
(6, 'Department of Education', '02', 'IT Equipment', 'Printer - HP LaserJet Pro M404dn, Monochrome, Network Ready, Serial: HP789012', 'PPE-2024-003', '2024-01-25', 'PAR-2024-003', 0.00, 1.00, 'Finance Office - Mike Johnson', 12000.00, 'Assigned to Finance Office', 'issue', NULL, '2025-08-05 06:20:22', '2025-08-05 06:20:22'),
(7, 'Department of Education', '01', 'Appliances', 'Air Conditioning Unit - Split Type 1.5HP, Inverter, Brand: Samsung, Model: AR12NVFXAWKNEU', 'PPE-2024-004', '2024-01-20', 'IAR-2024-004', 1.00, 0.00, NULL, 35000.00, 'For conference room installation', 'receipt', NULL, '2025-08-05 06:20:22', '2025-08-05 06:20:22'),
(8, 'Department of Education', '01', 'Office Equipment', 'Filing Cabinet - 4-Drawer Steel Cabinet, Gray Color, with Lock', 'PPE-2024-005', '2024-01-22', 'IAR-2024-005', 3.00, 0.00, NULL, 18000.00, 'Storage cabinets for documents', 'receipt', NULL, '2025-08-05 06:20:22', '2025-08-05 06:20:22'),
(9, 'Department of Education', '01', 'Office Equipment', 'Filing Cabinet - 4-Drawer Steel Cabinet, Gray Color, with Lock', 'PPE-2024-005', '2024-01-28', 'PAR-2024-004', 0.00, 2.00, 'Records Office - Sarah Wilson', 12000.00, 'Transferred to Records Office', 'transfer', NULL, '2025-08-05 06:20:22', '2025-08-05 06:20:22'),
(10, 'Department of Education', '02', 'IT Equipment', 'Laptop Computer - Lenovo ThinkPad E14, Intel i7, 16GB RAM, 512GB SSD', 'PPE-2024-006', '2024-02-01', 'IAR-2024-006', 4.00, 0.00, NULL, 200000.00, 'Mobile workstations for staff', 'receipt', NULL, '2025-08-05 06:20:22', '2025-08-05 06:20:22'),
(11, 'Department of Education', '02', 'IT Equipment', 'Laptop Computer - Lenovo ThinkPad E14, Intel i7, 16GB RAM, 512GB SSD', 'PPE-2024-006', '2024-02-05', 'PAR-2024-005', 0.00, 2.00, 'IT Department - Alex Brown', 100000.00, 'Issued to IT staff for field work', 'issue', NULL, '2025-08-05 06:20:22', '2025-08-05 06:20:22');

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
(48, '123', '123', 'ORD', 'TESDA CAR', '123', '2025/08/0001', '2025-08-11', '23', '123', '123', '23', '123', '2025-08-10 16:27:10'),
(49, 'qwe', 'qwe', 'ORD', 'TESDA CAR', 'qwe', '2025/08/0002', '2025-08-11', 'qweqw', 'qwe', 'qwe', 'qwe', 'qwe', '2025-08-10 17:00:55');

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
  `remarks` text DEFAULT NULL,
  `unit_cost_at_issue` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ris_items`
--

INSERT INTO `ris_items` (`item_id`, `ris_id`, `stock_number`, `stock_available`, `issued_quantity`, `remarks`, `unit_cost_at_issue`) VALUES
(0, 48, '123', 'Yes', 5, '1231', 10.52),
(0, 49, 'qwe', 'Yes', 10, 'qweqw', 17.00);

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

-- --------------------------------------------------------

--
-- Stand-in structure for view `vw_property_card_summary`
-- (See below for the actual view)
--
CREATE TABLE `vw_property_card_summary` (
`entity_name` varchar(255)
,`fund_cluster` varchar(50)
,`ppe_type` varchar(255)
,`property_number` varchar(100)
,`description` text
,`total_received` decimal(32,2)
,`total_issued` decimal(32,2)
,`current_balance` decimal(33,2)
,`total_amount` decimal(37,2)
,`last_transaction_date` date
);

-- --------------------------------------------------------

--
-- Structure for view `vw_property_card_summary`
--
DROP TABLE IF EXISTS `vw_property_card_summary`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_property_card_summary`  AS SELECT `pc`.`entity_name` AS `entity_name`, `pc`.`fund_cluster` AS `fund_cluster`, `pc`.`ppe_type` AS `ppe_type`, `pc`.`property_number` AS `property_number`, `pc`.`description` AS `description`, sum(case when `pc`.`transaction_type` = 'receipt' then `pc`.`receipt_qty` else 0 end) AS `total_received`, sum(case when `pc`.`transaction_type` in ('issue','transfer','disposal') then `pc`.`issue_qty` else 0 end) AS `total_issued`, sum(case when `pc`.`transaction_type` = 'receipt' then `pc`.`receipt_qty` else 0 end) - sum(case when `pc`.`transaction_type` in ('issue','transfer','disposal') then `pc`.`issue_qty` else 0 end) AS `current_balance`, sum(`pc`.`amount`) AS `total_amount`, max(`pc`.`transaction_date`) AS `last_transaction_date` FROM `property_cards` AS `pc` GROUP BY `pc`.`entity_name`, `pc`.`fund_cluster`, `pc`.`ppe_type`, `pc`.`property_number`, `pc`.`description` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `inventory_entries`
--
ALTER TABLE `inventory_entries`
  ADD PRIMARY KEY (`entry_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `item_history`
--
ALTER TABLE `item_history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `fk_item_history_ris` (`ris_id`);

--
-- Indexes for table `item_history_archive`
--
ALTER TABLE `item_history_archive`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `property_cards`
--
ALTER TABLE `property_cards`
  ADD PRIMARY KEY (`pc_id`),
  ADD KEY `idx_entity_fund` (`entity_name`,`fund_cluster`),
  ADD KEY `idx_property_number` (`property_number`),
  ADD KEY `idx_ppe_type` (`ppe_type`),
  ADD KEY `idx_transaction_date` (`transaction_date`),
  ADD KEY `idx_pc_compound` (`entity_name`,`fund_cluster`,`ppe_type`,`transaction_date`);

--
-- Indexes for table `ris`
--
ALTER TABLE `ris`
  ADD PRIMARY KEY (`ris_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `inventory_entries`
--
ALTER TABLE `inventory_entries`
  MODIFY `entry_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=316;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=228;

--
-- AUTO_INCREMENT for table `item_history`
--
ALTER TABLE `item_history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=647;

--
-- AUTO_INCREMENT for table `item_history_archive`
--
ALTER TABLE `item_history_archive`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `property_cards`
--
ALTER TABLE `property_cards`
  MODIFY `pc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `ris`
--
ALTER TABLE `ris`
  MODIFY `ris_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `inventory_entries`
--
ALTER TABLE `inventory_entries`
  ADD CONSTRAINT `inventory_entries_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`) ON DELETE CASCADE;

--
-- Constraints for table `item_history`
--
ALTER TABLE `item_history`
  ADD CONSTRAINT `fk_item_history_ris` FOREIGN KEY (`ris_id`) REFERENCES `ris` (`ris_id`),
  ADD CONSTRAINT `item_history_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
