-- ============================================================
-- Migration: Add missing columns to listings table
-- The listing form expects many more fields than the original schema
-- ============================================================

USE vidhaata_ventures;

-- Add missing columns (all use IF NOT EXISTS pattern via ALTER IGNORE or check)
ALTER TABLE listings
  ADD COLUMN IF NOT EXISTS listing_purpose ENUM('sale', 'rent', 'pg') DEFAULT 'sale' AFTER type,
  ADD COLUMN IF NOT EXISTS sub_type VARCHAR(50) DEFAULT NULL AFTER type,
  ADD COLUMN IF NOT EXISTS possession_status ENUM('ready_to_move', 'under_construction') DEFAULT 'ready_to_move' AFTER status,
  ADD COLUMN IF NOT EXISTS furnishing ENUM('fully_furnished', 'semi_furnished', 'unfurnished') DEFAULT 'unfurnished' AFTER status,
  ADD COLUMN IF NOT EXISTS parking ENUM('open', 'covered', 'none') DEFAULT 'open' AFTER status,
  ADD COLUMN IF NOT EXISTS amenities TEXT DEFAULT NULL AFTER status,
  ADD COLUMN IF NOT EXISTS rera_id VARCHAR(100) DEFAULT NULL AFTER status,
  ADD COLUMN IF NOT EXISTS is_rera TINYINT(1) DEFAULT 0 AFTER status,
  ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER created_at;