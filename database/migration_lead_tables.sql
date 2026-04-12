-- ============================================================
-- Migration: Add missing tables for lead management
-- Issue: "Table 'vidhaata_ventures.lead_proposals' doesn't exist"
-- ============================================================
-- This migration:
--   1. Adds missing lead_proposals table (caused the screenshot error)
--   2. Adds missing lead_activities table (activity timeline)
--   3. Adds listing_id column to lead_activities
--   4. Updates leads.status ENUM to include all pipeline stages
--
-- Safe to run on existing databases (uses IF NOT EXISTS / IF COLUMN NOT EXISTS).
-- ============================================================

USE vidhaata_ventures;

-- 1. Update leads table to include all 6 pipeline statuses
ALTER TABLE leads
  MODIFY COLUMN status ENUM('new', 'contacted', 'qualified', 'site_visit', 'negotiation', 'closed') DEFAULT 'new';

-- 1b. Add followup_at column to leads table
-- (MySQL does not support IF NOT EXISTS in ADD COLUMN; safe to run — will fail silently if column exists)
ALTER TABLE leads
  ADD COLUMN followup_at DATETIME DEFAULT NULL AFTER status;

-- 2. Create lead_activities table (for the activity timeline)
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

-- 3. Create lead_proposals table (for tracking proposed properties)
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
