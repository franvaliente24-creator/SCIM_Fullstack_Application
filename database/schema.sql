-- SCIM Database Schema (MariaDB)
CREATE DATABASE IF NOT EXISTS scim_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE scim_db;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('Admin', 'Manager', 'WarehouseStaff', 'Auditor') DEFAULT 'WarehouseStaff',
    mfa_secret VARCHAR(255) DEFAULT NULL,
    is_active TINYINT(1) DEFAULT 1,
    last_login DATETIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS login_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    status VARCHAR(50),
    login_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS inventory_assets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    asset_tag VARCHAR(100) NOT NULL UNIQUE,
    serial_number VARCHAR(100) NOT NULL UNIQUE,
    asset_name VARCHAR(150) NOT NULL,
    category VARCHAR(100),
    status ENUM('Deployed', 'InWarehouse', 'InMaintenance', 'Disposed') DEFAULT 'InWarehouse',
    current_shelf VARCHAR(50) DEFAULT 'Shelf-A1',
    assigned_to VARCHAR(150) DEFAULT NULL,
    purchase_order_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS warehouse_zones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    zone_code VARCHAR(50) NOT NULL UNIQUE,
    shelf_name VARCHAR(50) NOT NULL,
    capacity INT NOT NULL,
    current_occupancy INT DEFAULT 0,
    status_color ENUM('Green', 'Yellow', 'Red') DEFAULT 'Green'
);

CREATE TABLE IF NOT EXISTS purchase_orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    po_number VARCHAR(50) NOT NULL UNIQUE,
    vendor_name VARCHAR(150) NOT NULL,
    total_amount DECIMAL(12,2) NOT NULL,
    status ENUM('Draft', 'PendingApproval', 'SentToVendor', 'Shipped', 'Received') DEFAULT 'Draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS audit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action TEXT,
    scan_source VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Seed Data
INSERT INTO users (username, email, password_hash, role) VALUES 
('admin', 'admin@hostforgeplatform.cloud', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin');

INSERT INTO warehouse_zones (zone_code, shelf_name, capacity, current_occupancy, status_color) VALUES 
('Z1-A', 'Shelf A1', 50, 10, 'Green'),
('Z1-B', 'Shelf B3', 50, 48, 'Red'),
('Z2-A', 'Shelf C1', 40, 25, 'Yellow');

INSERT INTO purchase_orders (po_number, vendor_name, total_amount, status) VALUES 
('PO-405', 'TechCorp Solutions', 125000.00, 'Received'),
('PO-406', 'Global IT Hardware', 84000.00, 'Shipped');
