  -- phpMyAdmin SQL Dump
  -- version 5.2.1
  -- https://www.phpmyadmin.net/
  --
  -- Host: 127.0.0.1
  -- Generation Time: Jul 22, 2025 at 07:38 AM
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
    `unit_cost` decimal(10,2) NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `is_active` tinyint(1) DEFAULT 1
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

  --
  -- Dumping data for table `inventory_entries`
  --

  INSERT INTO `inventory_entries` (`entry_id`, `item_id`, `quantity`, `unit_cost`, `created_at`, `is_active`) VALUES
  (82, 118, 10, 10.00, '2025-07-22 05:34:31', 1),
  (83, 1, 10, 10.00, '2025-07-22 05:34:59', 1);

  -- --------------------------------------------------------

  --
  -- Table structure for table `items`
  --

  CREATE TABLE `items` (
    `item_id` int(11) NOT NULL,
    `stock_number` varchar(50) NOT NULL,
    `description` varchar(255) NOT NULL,
    `unit` varchar(50) NOT NULL,
    `reorder_point` int(11) DEFAULT NULL,
    `parent_item_id` int(11) DEFAULT NULL,
    `quantity_on_hand` int(11) DEFAULT 0,
    `unit_cost` decimal(10,2) DEFAULT 0.00,
    `initial_quantity` int(11) DEFAULT 0,
    `average_unit_cost` decimal(10,2) DEFAULT 0.00
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

  --
  -- Dumping data for table `items`
  --

  INSERT INTO `items` (`item_id`, `stock_number`, `description`, `unit`, `reorder_point`, `parent_item_id`, `quantity_on_hand`, `unit_cost`, `initial_quantity`, `average_unit_cost`) VALUES
  (1, 'A.01.a', 'ARCHFILE FOLDER, Tagila Lock', 'pc', 10, NULL, 20, 12.00, 10, 11.00),
  (2, 'A.02.a', 'AIR FRESHINER REFILL, Automatic Spray Refill(glade)', 'can', 0, NULL, 1, 1.00, 10, 0.00),
  (3, 'A.03.a', 'ALCOHOL, 70% ethy/isopropyl, with moisturizer, gallon', 'gallon', NULL, NULL, 0, 0.00, 0, 0.00),
  (4, 'A.03.b', 'ALCOHOL, 70% ethyl/isopropyl, 500ml', 'bottle', NULL, NULL, 0, 0.00, 0, 0.00),
  (5, 'B.01.a', 'BATTERY, dry cell, AA, 4pcs/pack, 1.5V, heavy duty', 'pack', NULL, NULL, 0, 0.00, 0, 0.00),
  (6, 'B.01.b', 'BATTERY, dry cell, AAA, 4pcs/pack, 1.5V, heavy duty', 'pack', NULL, NULL, 0, 0.00, 0, 0.00),
  (7, 'B.01.c', 'BATTERY, dry cell, 9V1', 'pc', NULL, NULL, 0, 0.00, 0, 0.00),
  (8, 'B.01.d', 'BATTERY, Li-on for thermo scanner', 'pc', NULL, NULL, 0, 0.00, 0, 0.00),
  (9, 'B.02.a', 'BLEACH, Zonrox', 'gallon', NULL, NULL, 0, 0.00, 0, 0.00),
  (10, 'C.01.a', 'CALCULATOR', 'pc', NULL, NULL, 0, 0.00, 0, 0.00),
  (11, 'C.02.a', 'CERTIFICATE HOLDER, A4', 'pc', NULL, NULL, 0, 0.00, 0, 0.00),
  (12, 'C.03.a', 'CLIP, backfold, large, 41mm, 12pcs/box', 'box', NULL, NULL, 0, 0.00, 0, 0.00),
  (13, 'C.03.b', 'CLIP, backfold, medium, 25mm, 12pcs/box', 'box', NULL, NULL, 0, 0.00, 0, 0.00),
  (14, 'C.03.c', 'CLIP, backfold, small, 19mm, 12pcs/box', 'box', NULL, NULL, 0, 0.00, 0, 0.00),
  (15, 'C.03.d', 'CLIP, backfold, extra small, 15mm, 12pcs/box', 'box', NULL, NULL, 0, 0.00, 0, 0.00),
  (16, 'C.04.a', 'CORRECTION TAPE, film based', 'pc', NULL, NULL, 0, 0.00, 0, 0.00),
  (17, 'C.05.a', 'CUTTER PAPER, blade/knife', 'pc', NULL, NULL, 0, 0.00, 0, 0.00),
  (18, 'C.06.a', 'CLING WRAP, 12inches x 300meters', 'roll', NULL, NULL, 0, 0.00, 0, 0.00),
  (19, 'D.01.a', 'DISHWASHING LIQUID, 500ml', 'bottle', NULL, NULL, 0, 0.00, 0, 0.00),
  (20, 'D.02.a', 'DISINFECTANT SPRAY, aerosol type', 'can', NULL, NULL, 0, 0.00, 0, 0.00),
  (21, 'D.03.a', 'DRAWER LOCK, set with key', 'set', NULL, NULL, 0, 0.00, 0, 0.00),
  (22, 'E.01.a', 'ENVELOPE EXPANDABLE , brown, long', 'pc', NULL, NULL, 0, 0.00, 0, 0.00),
  (23, 'F.01.a', 'FASTENER, plastic', 'box', NULL, NULL, 0, 0.00, 0, 0.00),
  (24, 'F.02.a', 'FOLDER, Tag Board, White, 100pcs/pack, Long', 'pack', NULL, NULL, 0, 0.00, 0, 0.00),
  (25, 'F.02.b', 'FOLDER EXPANDING, Long, pressboard 100pcs/pack, white & blue', 'pack', NULL, NULL, 0, 0.00, 0, 0.00),
  (26, 'F.03.a', 'FABRIC CONDITIONER, Softener', 'gallon', NULL, NULL, 0, 0.00, 0, 0.00),
  (27, 'G.01.a', 'GLUE STICK, all purpose, 22 grams,', 'pc', NULL, NULL, 0, 0.00, 0, 0.00),
  (28, 'G.02.a', 'GLASS CLEANER, with Spray cap 500ml', 'bottle', NULL, NULL, 0, 0.00, 0, 0.00),
  (29, 'H.01.a', 'HANDSOAP, Liquid, 500ml', 'btl', NULL, NULL, 0, 0.00, 0, 0.00),
  (30, 'I.01.a', 'INDEX TAB', 'box', NULL, NULL, 0, 0.00, 0, 0.00),
  (31, 'I.02.a', 'INK, Canon, GI 790, Magenta', 'bottle', NULL, NULL, 0, 0.00, 0, 0.00),
  (32, 'I.02.b', 'INK, Canon, GI 790, Yellow', 'bottle', NULL, NULL, 0, 0.00, 0, 0.00),
  (33, 'I.02.c', 'INK, Canon, GI 790, Black', 'bottle', NULL, NULL, 0, 0.00, 0, 0.00),
  (34, 'I.02.d', 'INK, Canon, GI 790, Cyan', 'bottle', NULL, NULL, 0, 0.00, 0, 0.00),
  (35, 'I.03.a', 'INK HP, 682, black', 'cart', NULL, NULL, 0, 0.00, 0, 0.00),
  (36, 'I.03.b', 'INK HP, 682, colored', 'cart', NULL, NULL, 0, 0.00, 0, 0.00),
  (37, 'I.04.a', 'INK, Canon, 810 Black', 'cart', NULL, NULL, 0, 0.00, 0, 0.00),
  (38, 'I.04.b', 'INK, Canon, 811 Colored', 'cart', NULL, NULL, 0, 0.00, 0, 0.00),
  (39, 'I.05.a', 'INK, Epson 003, Black', 'bottle', NULL, NULL, 0, 0.00, 0, 0.00),
  (40, 'I.05.b', 'INK, Epson 003, Cyan', 'bottle', NULL, NULL, 0, 0.00, 0, 0.00),
  (41, 'I.05.c', 'INK, Epson 003, Magenta', 'bottle', NULL, NULL, 0, 0.00, 0, 0.00),
  (42, 'I.05.d', 'INK, Epson 003, Yellow', 'bottle', NULL, NULL, 0, 0.00, 0, 0.00),
  (43, 'I.06.a', 'INSECTICIDE, Aerosol type, waterbased, 600ml/can', 'can', NULL, NULL, 0, 0.00, 0, 0.00),
  (44, 'K.01.a', 'KITCHEN TOWEL, Paper Towel, roll, 2ply', 'roll', NULL, NULL, 0, 0.00, 0, 0.00),
  (45, 'L.01.a', 'LED BULB', 'pc', NULL, NULL, 0, 0.00, 0, 0.00),
  (46, 'N.01.a', 'NOTARIAL SEAL', 'pack', NULL, NULL, 0, 0.00, 0, 0.00),
  (47, 'N.02.a', 'NOTE PAD, stick on, 2\"x3\"', 'pc', NULL, NULL, 0, 0.00, 0, 0.00),
  (48, 'N.02.b', 'NOTE PAD, stick on, 3\"x3\"', 'pc', NULL, NULL, 0, 0.00, 0, 0.00),
  (49, 'N.02.c', 'NOTE PAD, stick on, 4\"x3\"', 'pc', NULL, NULL, 0, 0.00, 0, 0.00),
  (50, 'N.02.d', 'NOTE PAD, stick on, d3-4 (4\'s -1\"x3\")', 'pc', NULL, NULL, 0, 0.00, 0, 0.00),
  (51, 'P.01.a', 'PAPER, Board, A4, white, 180gsm, 100sheets/pack', 'pack', NULL, NULL, 0, 0.00, 0, 0.00),
  (52, 'P.01.b', 'PAPER, Board, A4, white, 200gsm, 100sheets/pack', 'pack', NULL, NULL, 0, 0.00, 0, 0.00),
  (53, 'P.01.c', 'PAPER, Board, Morocco, A4, 200gsm, 100sheets/pack', 'pack', NULL, NULL, 0, 0.00, 0, 0.00),
  (54, 'P.02.a', 'PAPER CLIP, 50mm, jumbo, vinyl coated', 'box', NULL, NULL, 0, 0.00, 0, 0.00),
  (55, 'P.02.b', 'PAPER CLIP, 33mm, vinyl coated', 'box', NULL, NULL, 0, 0.00, 0, 0.00),
  (56, 'P.03.a', 'PAPER, Multicopy, PPC, s20, 8.5\" x 13\"', 'ream', NULL, NULL, 0, 0.00, 0, 0.00),
  (57, 'P.03.b', 'PAPER, Multicopy, PPC, s20, 8.5\" x 14\"', 'ream', NULL, NULL, 0, 0.00, 0, 0.00),
  (58, 'P.03.c', 'PAPER, Multicopy, PPC, s20, A4', 'ream', NULL, NULL, 0, 0.00, 0, 0.00),
  (59, 'P.03.d', 'PAPER, Multicopy, PPC, s20, Short', 'ream', NULL, NULL, 0, 0.00, 0, 0.00),
  (60, 'P.04.a', 'PEN SIGN, gel or liquid ink, retractable, 0.7mm Black/ Blue, 12pcs/box', 'box', NULL, NULL, 0, 0.00, 0, 0.00),
  (61, 'p.04.b', 'PEN SIGN, Hi-tecpoint V10Grip, 1.0, 12pcs/box, Black/Blue', 'box', NULL, NULL, 0, 0.00, 0, 0.00),
  (62, 'P.04.c', 'PEN, ballpoint, retractable, 0.7mm, Black/Blue', 'box', NULL, NULL, 0, 0.00, 0, 0.00),
  (63, 'P.04.d', 'PEN, Fine, Retractable, 0.5mm', 'pc', NULL, NULL, 0, 0.00, 0, 0.00),
  (64, 'P.05.a', 'POST IT- Sticky Note, \"Sign Here\", \"Please Sign\",', 'pack', NULL, NULL, 0, 0.00, 0, 0.00),
  (65, 'P.06.a', 'PUSH PINS, 100pcs/box', 'box', NULL, NULL, 0, 0.00, 0, 0.00),
  (66, 'R.01.a', 'RECORD BOOK, Logbook, 300 pages', 'pc', NULL, NULL, 0, 0.00, 0, 0.00),
  (67, 'R.02.a', 'RULER, Steel, 12 inches', 'pc', NULL, NULL, 0, 0.00, 0, 0.00),
  (68, 'R.03.a', 'RAGS', 'pc', NULL, NULL, 0, 0.00, 0, 0.00),
  (69, 'S.01.a', 'STAPLER', 'pc', NULL, NULL, 0, 0.00, 0, 0.00),
  (70, 'S.01.b', 'STAPLE WIRE, Standard, 5000 staples/box', 'box', NULL, NULL, 0, 0.00, 0, 0.00),
  (71, 'S.01.c', 'STAPLE WIRE, Bostitch, 5000 staples/box', 'box', NULL, NULL, 0, 0.00, 0, 0.00),
  (72, 'S.01.d', 'STAPLER REMOVER', 'pc', NULL, NULL, 0, 0.00, 0, 0.00),
  (73, 'S.02.a', 'SCOURING PAD, Dishwashing sponge', 'pc', NULL, NULL, 0, 0.00, 0, 0.00),
  (74, 'T.01.a', 'TAPE, clear, 1inch', 'roll', NULL, NULL, 0, 0.00, 0, 0.00),
  (75, 'T.01.b', 'TAPE, Cloth, Duct tape', 'roll', NULL, NULL, 0, 0.00, 0, 0.00),
  (76, 'T.01.c', 'TAPE, double sided, 1inch', 'roll', NULL, NULL, 0, 0.00, 0, 0.00),
  (77, 'T.01.d', 'TAPE, Packing, 2\"', 'roll', NULL, NULL, 0, 0.00, 0, 0.00),
  (78, 'T.01.e', 'TAPE, transparent, 2\"', 'roll', NULL, NULL, 0, 0.00, 0, 0.00),
  (79, 'T.01.f', 'TAPE, transparent, 3\"', 'roll', NULL, NULL, 0, 0.00, 0, 0.00),
  (80, 'T.02.a', 'TAPE, refill for Epson LW-K400 printer/label 12mm', 'pcs', NULL, NULL, 0, 0.00, 0, 0.00),
  (81, 'T.03.a', 'TAPE DISPENSER', 'pc', NULL, NULL, 0, 0.00, 0, 0.00),
  (82, 'T.04.a', 'TOILET BOWL BRUSH, round headed brush', 'pc', NULL, NULL, 0, 0.00, 0, 0.00),
  (83, 'T.04.b', 'TOILET BOWL CLEANER, Liquid, 900ml', 'bottle', NULL, NULL, 0, 0.00, 0, 0.00),
  (84, 'T.05.a', 'TISSUE BATHROOM, Green Tea, 180g, 10pcs/pack', 'pack', NULL, NULL, 0, 0.00, 0, 0.00),
  (85, 'T.05.b', 'TISSUE FACIAL, Econo Box, 2ply, 200-250pulls', 'box', NULL, NULL, 0, 0.00, 0, 0.00),
  (86, 'T.05.c', 'TOILET TISSUE PAPER, 2ply, 12\'s per pack, 1000 sheets per roll', 'pack', NULL, NULL, 0, 0.00, 0, 0.00),
  (87, 'U.01.a', 'USB, Flash Drive, 64GB', 'pc', NULL, NULL, 0, 0.00, 0, 0.00),
  (118, 'qwe', 'qwe', 'q', 10, NULL, 29, 5.00, 19, 6.72);

  -- --------------------------------------------------------

  -- Altered Item database ------------------------------

  ALTER TABLE items
    ADD COLUMN item_name VARCHAR(255) DEFAULT NULL AFTER description,
    ADD COLUMN item_description TEXT DEFAULT NULL AFTER item_name;


  SELECT 
      item_id,
      description,
      TRIM(SUBSTRING_INDEX(description, ',', 1)) AS preview_item_name,
      TRIM(
        CASE
          WHEN LOCATE(',', description) > 0 
          THEN SUBSTRING(description, LOCATE(',', description) + 1)
          ELSE ''
        END
      ) AS preview_item_description
  FROM items
  LIMIT 10;

  UPDATE items
  SET
    item_name = TRIM(SUBSTRING_INDEX(description, ',', 1)),
    item_description = TRIM(
      CASE
        WHEN LOCATE(',', description) > 0
          THEN LTRIM(RTRIM(SUBSTRING(description, LOCATE(',', description) + 1)))
        ELSE ''
      END
    );

    ALTER TABLE items
    DROP COLUMN description;

  ALTER Table items
  CHANGE item_description description Text;



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
  (19, 'qwe', 'qwe', 'ORD', 'TESDA CAR', 'qwe', '2025/07/0001', '2025-07-22', 'qwe', 'qwe', 'qwe', 'qwe', 'qwe', '2025-07-22 05:17:47'),
  (21, 'qwe', 'qwe', 'ORD', 'TESDA CAR', 'qwe', '2025/07/0002', '2025-07-22', 'qwe', 'qwe', 'qwe', 'qwe', 'qwe', '2025-07-22 05:32:00');

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
  (0, 19, 'qwe', 'Yes', 4, 'qwe', 7.22),
  (0, 21, 'A.01.a', 'Yes', 5, 'qwe', 14.00);

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
    MODIFY `entry_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

  --
  -- AUTO_INCREMENT for table `items`
  --
  ALTER TABLE `items`
    MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

  --
  -- AUTO_INCREMENT for table `ris`
  --
  ALTER TABLE `ris`
    MODIFY `ris_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

  --
  -- Constraints for dumped tables
  --

  --
  -- Constraints for table `inventory_entries`
  --
  ALTER TABLE `inventory_entries`
    ADD CONSTRAINT `inventory_entries_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`) ON DELETE CASCADE;
  COMMIT;

  /*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
  /*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
  /*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

  CREATE TABLE item_history (
      history_id INT AUTO_INCREMENT PRIMARY KEY,
      item_id INT NOT NULL,
      stock_number VARCHAR(255),
      description TEXT,
      unit VARCHAR(50),
      reorder_point INT,
      unit_cost DECIMAL(10,2),
      quantity_on_hand INT,
      changed_at DATETIME DEFAULT CURRENT_TIMESTAMP,
      change_type VARCHAR(50) DEFAULT 'update',
      FOREIGN KEY (item_id) REFERENCES items(item_id) ON DELETE CASCADE
  );

  ALTER TABLE item_history ADD COLUMN quantity_change INT AFTER quantity_on_hand;

  ALTER TABLE item_history ADD COLUMN change_direction VARCHAR(20) AFTER quantity_change;

  ALTER TABLE item_history
  ADD COLUMN item_name VARCHAR(255) AFTER stock_number;

  Alter TABLE item_history
    Add COLUMN reference_id VARCHAR (255); 

  ALTER TABLE item_history 
  MODIFY COLUMN reference_id VARCHAR(255) NULL 
  AFTER change_type;

  CREATE TABLE item_history_archive LIKE item_history;

  ALTER TABLE item_history
  MODIFY COLUMN reference_id VARCHAR(255);

  ALTER TABLE item_history
  DROP COLUMN reference_id,
  ADD COLUMN ris_id INT NULL AFTER change_type,
  ADD CONSTRAINT fk_item_history_ris FOREIGN KEY (ris_id) REFERENCES ris(ris_id);
  
SELECT 
  ih.history_id,
  ih.item_id,
  ih.ris_id,
  r.ris_no,
  ih.quantity_change,
  ih.changed_at
FROM item_history ih
LEFT JOIN ris r ON ih.ris_id = r.ris_id
WHERE ih.item_id = 1
ORDER BY ih.changed_at DESC;


  