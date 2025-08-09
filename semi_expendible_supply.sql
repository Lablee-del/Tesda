-- =============================================
-- ICS DATABASE SETUP SCRIPT
-- Run this in your MySQL database (tesda_inventory)
-- =============================================

-- Create ICS header table (matching RIS structure)
CREATE TABLE IF NOT EXISTS ics (
    ics_id INT(11) NOT NULL AUTO_INCREMENT,
    ics_no VARCHAR(50) NOT NULL,
    entity_name VARCHAR(255) NOT NULL,
    fund_cluster VARCHAR(100) NOT NULL,
    date_issued DATE NOT NULL,
    received_by VARCHAR(255) NOT NULL,
    received_by_position VARCHAR(255) NOT NULL,
    received_from VARCHAR(255) NOT NULL,
    received_from_position VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (ics_id),
    UNIQUE KEY unique_ics_no (ics_no)
);

-- Create ICS items table (matching RIS items structure)
CREATE TABLE IF NOT EXISTS ics_items (
    ics_item_id INT(11) NOT NULL AUTO_INCREMENT,
    ics_id INT(11) NOT NULL,
    stock_number VARCHAR(50) NOT NULL,
    quantity DECIMAL(10,2) NOT NULL,
    unit VARCHAR(50) NOT NULL,
    unit_cost DECIMAL(10,2) NOT NULL,
    total_cost DECIMAL(10,2) NOT NULL,
    description TEXT NOT NULL,
    inventory_item_no VARCHAR(100),
    estimated_useful_life VARCHAR(100),
    serial_number VARCHAR(100),
    PRIMARY KEY (ics_item_id),
    FOREIGN KEY (ics_id) REFERENCES ics(ics_id) ON DELETE CASCADE,
    INDEX idx_stock_number (stock_number)
);

-- Insert sample data (optional - for testing)
INSERT INTO ics (ics_no, entity_name, fund_cluster, date_issued, received_by, received_by_position, received_from, received_from_position) VALUES
('ICS-2025/01/0001', 'TESDA Regional Office', 'General Fund', '2025-01-15', 'Juan Dela Cruz', 'Training Specialist', 'Maria Santos', 'Property Custodian');

-- Note: You may need to add a 'category' column to your existing 'items' table to distinguish semi-expendable items
ALTER TABLE items ADD COLUMN category VARCHAR(100) DEFAULT 'Consumable';
UPDATE items SET category = 'Semi-Expendable' WHERE item_name LIKE '%computer%' OR item_name LIKE '%printer%' OR item_name LIKE '%chair%' OR item_name LIKE '%table%' OR item_name LIKE '%equipment%';
    
    
    CREATE TABLE `ics` (
    ics_id INT(11) NOT NULL AUTO_INCREMENT,
    ics_no VARCHAR(50) NOT NULL,
    entity_name VARCHAR(255) NOT NULL,
    fund_cluster VARCHAR(100) NOT NULL,
    date_issued DATE NOT NULL,
    received_by VARCHAR(255) NOT NULL,
    received_by_position VARCHAR(255) NOT NULL,
    received_from VARCHAR(255) NOT NULL,
    received_from_position VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (ics_id),
    UNIQUE KEY unique_ics_no (ics_no)
);

-- Create ICS items table
CREATE TABLE IF NOT EXISTS ics_items (
    ics_item_id INT(11) NOT NULL AUTO_INCREMENT,
    ics_id INT(11) NOT NULL,
    quantity DECIMAL(10,2) NOT NULL,
    unit VARCHAR(50) NOT NULL,
    unit_cost DECIMAL(10,2) NOT NULL,
    total_cost DECIMAL(10,2) NOT NULL,
    description TEXT NOT NULL,
    inventory_item_no VARCHAR(100),
    estimated_useful_life VARCHAR(100),
    serial_number VARCHAR(100),
    PRIMARY KEY (ics_item_id),
    FOREIGN KEY (ics_id) REFERENCES ics(ics_id) ON DELETE CASCADE
);

-- Insert sample data (optional)
INSERT INTO ics (ics_no, entity_name, fund_cluster, date_issued, received_by, received_by_position, received_from, received_from_position) VALUES
('ICS-2025-001', 'TESDA Regional Office', 'General Fund', '2025-01-15', 'Juan Dela Cruz', 'Training Specialist', 'Maria Santos', 'Property Custodian'),
('ICS-2025-002', 'TESDA Regional Office', 'General Fund', '2025-01-20', 'Ana Rodriguez', 'Admin Officer', 'Maria Santos', 'Property Custodian');

INSERT INTO ics_items (ics_id, quantity, unit, unit_cost, total_cost, description, inventory_item_no, estimated_useful_life, serial_number) VALUES
(1, 2, 'pcs', 15000.00, 30000.00, 'Desktop Computer - Dell Optiplex 3080', 'IT-001', '5 years', 'DT2025001'),
(1, 1, 'pcs', 8000.00, 8000.00, 'Laser Printer - HP LaserJet Pro', 'IT-002', '3 years', 'PR2025001'),
(2, 5, 'pcs', 2500.00, 12500.00, 'Office Chair - Executive Type', 'FUR-001', '10 years', NULL),
(2, 1, 'set', 5000.00, 5000.00, 'Conference Table Set', 'FUR-002', '15 years', NULL);

-- Property Cards table for semi-expendable supplies
  CREATE TABLE `property_cards` (
    `pc_id` int(11) NOT NULL AUTO_INCREMENT,
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
    `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`pc_id`),
    KEY `idx_entity_fund` (`entity_name`, `fund_cluster`),
    KEY `idx_property_number` (`property_number`),
    KEY `idx_ppe_type` (`ppe_type`),
    KEY `idx_transaction_date` (`transaction_date`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

  -- Sample data for testing
  INSERT INTO `property_cards` (`entity_name`, `fund_cluster`, `ppe_type`, `description`, `property_number`, `transaction_date`, `reference_par_no`, `receipt_qty`, `issue_qty`, `office_officer`, `amount`, `remarks`, `transaction_type`) VALUES
  ('Department of Education', '01', 'Office Equipment', 'Desktop Computer - Dell OptiPlex 3070, Intel Core i5, 8GB RAM, 256GB SSD, Serial: DL123456', 'PPE-2024-001', '2024-01-15', 'IAR-2024-001', 1.00, 0.00, NULL, 45000.00, 'Brand new unit for Admin Office', 'receipt'),
  ('Department of Education', '01', 'Office Equipment', 'Desktop Computer - Dell OptiPlex 3070, Intel Core i5, 8GB RAM, 256GB SSD, Serial: DL123456', 'PPE-2024-001', '2024-01-20', 'PAR-2024-001', 0.00, 1.00, 'Admin Office - John Doe', 45000.00, 'Issued to Admin Office', 'issue'),
  ('Department of Education', '01', 'Furniture and Fixtures', 'Office Chair - Ergonomic Swivel Chair, Black Leather, Model: EC-2024', 'PPE-2024-002', '2024-01-16', 'IAR-2024-002', 5.00, 0.00, NULL, 12500.00, 'Set of 5 office chairs', 'receipt'),
  ('Department of Education', '01', 'Furniture and Fixtures', 'Office Chair - Ergonomic Swivel Chair, Black Leather, Model: EC-2024', 'PPE-2024-002', '2024-01-22', 'PAR-2024-002', 0.00, 3.00, 'HR Department - Jane Smith', 7500.00, 'Issued 3 chairs to HR Dept', 'issue'),
  ('Department of Education', '02', 'IT Equipment', 'Printer - HP LaserJet Pro M404dn, Monochrome, Network Ready, Serial: HP789012', 'PPE-2024-003', '2024-01-18', 'IAR-2024-003', 2.00, 0.00, NULL, 24000.00, 'Network printers for offices', 'receipt'),
  ('Department of Education', '02', 'IT Equipment', 'Printer - HP LaserJet Pro M404dn, Monochrome, Network Ready, Serial: HP789012', 'PPE-2024-003', '2024-01-25', 'PAR-2024-003', 0.00, 1.00, 'Finance Office - Mike Johnson', 12000.00, 'Assigned to Finance Office', 'issue'),
  ('Department of Education', '01', 'Appliances', 'Air Conditioning Unit - Split Type 1.5HP, Inverter, Brand: Samsung, Model: AR12NVFXAWKNEU', 'PPE-2024-004', '2024-01-20', 'IAR-2024-004', 1.00, 0.00, NULL, 35000.00, 'For conference room installation', 'receipt'),
  ('Department of Education', '01', 'Office Equipment', 'Filing Cabinet - 4-Drawer Steel Cabinet, Gray Color, with Lock', 'PPE-2024-005', '2024-01-22', 'IAR-2024-005', 3.00, 0.00, NULL, 18000.00, 'Storage cabinets for documents', 'receipt'),
  ('Department of Education', '01', 'Office Equipment', 'Filing Cabinet - 4-Drawer Steel Cabinet, Gray Color, with Lock', 'PPE-2024-005', '2024-01-28', 'PAR-2024-004', 0.00, 2.00, 'Records Office - Sarah Wilson', 12000.00, 'Transferred to Records Office', 'transfer'),
  ('Department of Education', '02', 'IT Equipment', 'Laptop Computer - Lenovo ThinkPad E14, Intel i7, 16GB RAM, 512GB SSD', 'PPE-2024-006', '2024-02-01', 'IAR-2024-006', 4.00, 0.00, NULL, 200000.00, 'Mobile workstations for staff', 'receipt'),
  ('Department of Education', '02', 'IT Equipment', 'Laptop Computer - Lenovo ThinkPad E14, Intel i7, 16GB RAM, 512GB SSD', 'PPE-2024-006', '2024-02-05', 'PAR-2024-005', 0.00, 2.00, 'IT Department - Alex Brown', 100000.00, 'Issued to IT staff for field work', 'issue');

  -- Index for better performance
  CREATE INDEX idx_pc_compound ON property_cards (entity_name, fund_cluster, ppe_type, transaction_date);

  -- View for easier reporting (balance calculation)
  CREATE VIEW vw_property_card_summary AS
  SELECT 
      pc.entity_name,
      pc.fund_cluster,
      pc.ppe_type,
      pc.property_number,
      pc.description,
      SUM(CASE WHEN pc.transaction_type = 'receipt' THEN pc.receipt_qty ELSE 0 END) as total_received,
      SUM(CASE WHEN pc.transaction_type IN ('issue', 'transfer', 'disposal') THEN pc.issue_qty ELSE 0 END) as total_issued,
      (SUM(CASE WHEN pc.transaction_type = 'receipt' THEN pc.receipt_qty ELSE 0 END) - 
      SUM(CASE WHEN pc.transaction_type IN ('issue', 'transfer', 'disposal') THEN pc.issue_qty ELSE 0 END)) as current_balance,
      SUM(pc.amount) as total_amount,
      MAX(pc.transaction_date) as last_transaction_date
  FROM property_cards pc
  GROUP BY pc.entity_name, pc.fund_cluster, pc.ppe_type, pc.property_number, pc.description;