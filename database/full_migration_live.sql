-- ============================================================
-- Full Database Migration for Live Server (Hostinger)
-- Database: u286257250_vidhaataV
-- Run this ENTIRE file on the live server to set up all tables
-- ============================================================

CREATE DATABASE IF NOT EXISTS u286257250_vidhaataV
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE u286257250_vidhaataV;

-- ─── 1. listings table ───────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS listings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  type ENUM('residential', 'commercial', 'plot', 'residential_plot', 'commercial_plot') DEFAULT 'residential',
  sub_type VARCHAR(50) DEFAULT NULL,
  listing_purpose ENUM('sale', 'rent', 'pg') DEFAULT 'sale',
  transaction VARCHAR(50) DEFAULT NULL,
  title VARCHAR(255) NOT NULL,
  society_name VARCHAR(255) DEFAULT NULL,
  society_scale VARCHAR(50) DEFAULT NULL,
  road_width INT DEFAULT NULL,
  description TEXT,
  price DECIMAL(12,2) DEFAULT NULL,
  price_per_sqft DECIMAL(12,2) DEFAULT NULL,
  is_negotiable TINYINT(1) DEFAULT 0,
  parking VARCHAR(50) DEFAULT NULL,
  facing VARCHAR(50) DEFAULT NULL,
  furnishing VARCHAR(50) DEFAULT NULL,
  amenities TEXT DEFAULT NULL,
  location VARCHAR(255) DEFAULT NULL,
  city VARCHAR(100) DEFAULT NULL,
  state VARCHAR(100) DEFAULT NULL,
  pincode VARCHAR(20) DEFAULT NULL,
  landmark VARCHAR(255) DEFAULT NULL,
  bedrooms TINYINT DEFAULT NULL,
  bathrooms TINYINT DEFAULT NULL,
  balconies TINYINT DEFAULT NULL,
  floor_number INT DEFAULT NULL,
  total_floors INT DEFAULT NULL,
  area_sqft INT DEFAULT NULL,
  carpet_area INT DEFAULT NULL,
  possession_status VARCHAR(50) DEFAULT NULL,
  is_rera TINYINT(1) DEFAULT 0,
  rera_id VARCHAR(50) DEFAULT NULL,
  image_filename VARCHAR(255) DEFAULT NULL,
  video_url VARCHAR(255) DEFAULT NULL,
  is_featured TINYINT(1) DEFAULT 0,
  status ENUM('active', 'sold', 'inactive', 'deleted') DEFAULT 'active',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── 2. leads table ──────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS leads (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  phone VARCHAR(20) DEFAULT NULL,
  email VARCHAR(255) DEFAULT NULL,
  message TEXT DEFAULT NULL,
  source_page VARCHAR(100) DEFAULT NULL,
  status ENUM('new', 'contacted', 'qualified', 'site_visit', 'negotiation', 'closed') DEFAULT 'new',
  followup_at DATETIME DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  whatsapp_available TINYINT(1) DEFAULT 0,
  lead_type ENUM('buyer', 'seller', 'tenant') DEFAULT 'buyer',
  budget_min INT DEFAULT NULL,
  budget_max INT DEFAULT NULL,
  preferred_locations VARCHAR(500) DEFAULT NULL,
  configuration VARCHAR(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── 3. lead_activities table ────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS lead_activities (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  lead_id INT UNSIGNED NOT NULL,
  action_type VARCHAR(50) NOT NULL DEFAULT 'status_change',
  from_status VARCHAR(50) DEFAULT NULL,
  to_status VARCHAR(50) DEFAULT NULL,
  notes TEXT DEFAULT NULL,
  listing_id INT DEFAULT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (lead_id) REFERENCES leads(id) ON DELETE CASCADE,
  FOREIGN KEY (listing_id) REFERENCES listings(id) ON DELETE SET NULL,
  INDEX idx_lead_id (lead_id),
  INDEX idx_listing_id (listing_id),
  INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── 4. lead_proposals table ─────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS lead_proposals (
  id INT AUTO_INCREMENT PRIMARY KEY,
  lead_id INT NOT NULL,
  listing_id INT NOT NULL,
  notes TEXT DEFAULT NULL,
  proposed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (lead_id) REFERENCES leads(id) ON DELETE CASCADE,
  FOREIGN KEY (listing_id) REFERENCES listings(id) ON DELETE CASCADE,
  UNIQUE KEY unique_lead_listing (lead_id, listing_id),
  INDEX idx_lead_id (lead_id),
  INDEX idx_proposed_at (proposed_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── 5. users table (for admin auth) ─────────────────────────────────────────
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin', 'agent') DEFAULT 'admin',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── 6. Insert default admin user (password: changeme) ───────────────────────
INSERT INTO users (name, email, password, role) VALUES
  ('Admin User', 'admin@vidhaata.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin')
  ON DUPLICATE KEY UPDATE name = name;

-- ─── 7. Insert sample listings (optional — remove if you already have data) ──
-- Uncomment the lines below if you want sample data on the live server:
/*
INSERT INTO listings (title, type, sub_type, listing_purpose, description, price, location, city, bedrooms, bathrooms, area_sqft, status) VALUES
  ('Siddha Sky', 'residential', 'apartment', 'sale', 'Luxury 3BHK with panoramic city views', 32000000, 'EM Bypass', 'Kolkata', 3, 3, 2100, 'active'),
  ('Serena', 'residential', 'apartment', 'sale', 'Premium 3BHK in Newtown', 13000000, 'Newtown', 'Kolkata', 3, 2, 1755, 'active');
*/