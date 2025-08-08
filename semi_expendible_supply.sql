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