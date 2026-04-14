-- ============================================================
-- Dynamic Listing Fields Migration
-- Adds columns for Residential, Commercial, and Land/Plot types
-- ============================================================

-- For local development:
USE vidhaata_ventures;
-- For live server, change to: USE u286257250_vidhaataV;

-- Residential: Society/Project specific fields
ALTER TABLE listings ADD COLUMN IF NOT EXISTS project_name VARCHAR(255) DEFAULT NULL AFTER society_name;
ALTER TABLE listings ADD COLUMN IF NOT EXISTS total_floors INT DEFAULT NULL AFTER floor_number;

-- Residential: Building type (society, standalone, house)
ALTER TABLE listings ADD COLUMN IF NOT EXISTS building_type VARCHAR(50) DEFAULT NULL AFTER sub_type;

-- Residential: House/Villa specific
ALTER TABLE listings ADD COLUMN IF NOT EXISTS plot_area_sqft DECIMAL(12,2) DEFAULT NULL AFTER carpet_area;
ALTER TABLE listings ADD COLUMN IF NOT EXISTS private_garden TINYINT(1) DEFAULT 0 AFTER plot_area_sqft;
ALTER TABLE listings ADD COLUMN IF NOT EXISTS terrace TINYINT(1) DEFAULT 0 AFTER private_garden;

-- Residential: Rent specific
ALTER TABLE listings ADD COLUMN IF NOT EXISTS monthly_rent DECIMAL(12,2) DEFAULT NULL AFTER price;
ALTER TABLE listings ADD COLUMN IF NOT EXISTS security_deposit DECIMAL(12,2) DEFAULT NULL AFTER monthly_rent;
ALTER TABLE listings ADD COLUMN IF NOT EXISTS tenant_preference VARCHAR(50) DEFAULT NULL AFTER security_deposit;

-- Commercial: Specific fields
ALTER TABLE listings ADD COLUMN IF NOT EXISTS washrooms_type VARCHAR(50) DEFAULT NULL AFTER furnishing;
ALTER TABLE listings ADD COLUMN IF NOT EXISTS central_ac TINYINT(1) DEFAULT 0 AFTER washrooms_type;
ALTER TABLE listings ADD COLUMN IF NOT EXISTS dg_power_backup TINYINT(1) DEFAULT 0 AFTER central_ac;
ALTER TABLE listings ADD COLUMN IF NOT EXISTS cafeteria_pantry TINYINT(1) DEFAULT 0 AFTER dg_power_backup;
ALTER TABLE listings ADD COLUMN IF NOT EXISTS visitor_parking TINYINT(1) DEFAULT 0 AFTER cafeteria_pantry;

-- Commercial: Pre-leased
ALTER TABLE listings ADD COLUMN IF NOT EXISTS pre_leased TINYINT(1) DEFAULT 0 AFTER is_negotiable;
ALTER TABLE listings ADD COLUMN IF NOT EXISTS current_tenant VARCHAR(255) DEFAULT NULL AFTER pre_leased;
ALTER TABLE listings ADD COLUMN IF NOT EXISTS monthly_rent_received DECIMAL(12,2) DEFAULT NULL AFTER current_tenant;
ALTER TABLE listings ADD COLUMN IF NOT EXISTS lease_expiry_date DATE DEFAULT NULL AFTER monthly_rent_received;

-- Commercial: Lock-in period
ALTER TABLE listings ADD COLUMN IF NOT EXISTS lockin_period_months INT DEFAULT NULL AFTER lease_expiry_date;
ALTER TABLE listings ADD COLUMN IF NOT EXISTS revenue_share_model TINYINT(1) DEFAULT 0 AFTER lockin_period_months;

-- Commercial: Furnishing type (bare shell, warm shell, fully furnished)
ALTER TABLE listings MODIFY COLUMN furnishing VARCHAR(50) DEFAULT NULL COMMENT 'For Residential: fully/semi/unfurnished. For Commercial: bare_shell/warm_shell/fully_furnished';

-- Land/Plot: Specific fields
ALTER TABLE listings ADD COLUMN IF NOT EXISTS land_type VARCHAR(50) DEFAULT NULL AFTER sub_type COMMENT 'residential_plot, commercial_plot, agricultural';
ALTER TABLE listings ADD COLUMN IF NOT EXISTS area_unit VARCHAR(20) DEFAULT 'sqft' AFTER area_sqft COMMENT 'sqft, sqyd, sqm, acres';
ALTER TABLE listings ADD COLUMN IF NOT EXISTS plot_length DECIMAL(10,2) DEFAULT NULL AFTER plot_area_sqft;
ALTER TABLE listings ADD COLUMN IF NOT EXISTS plot_width DECIMAL(10,2) DEFAULT NULL AFTER plot_length;
ALTER TABLE listings ADD COLUMN IF NOT EXISTS road_width_ft INT DEFAULT NULL AFTER road_width;
ALTER TABLE listings ADD COLUMN IF NOT EXISTS boundary_wall TINYINT(1) DEFAULT 0 AFTER road_width_ft;
ALTER TABLE listings ADD COLUMN IF NOT EXISTS corner_plot TINYINT(1) DEFAULT 0 AFTER boundary_wall;
ALTER TABLE listings ADD COLUMN IF NOT EXISTS na_approved TINYINT(1) DEFAULT 0 AFTER corner_plot;
ALTER TABLE listings ADD COLUMN IF NOT EXISTS na_type VARCHAR(50) DEFAULT NULL AFTER na_approved COMMENT 'na_residential, na_commercial, etc';

-- Residential: Society amenities (stored as JSON)
ALTER TABLE listings ADD COLUMN IF NOT EXISTS society_amenities JSON DEFAULT NULL AFTER amenities;
ALTER TABLE listings ADD COLUMN IF NOT EXISTS water_source VARCHAR(50) DEFAULT NULL AFTER society_amenities COMMENT 'municipal, borewell, both';

-- Listings: Area unit for residential
ALTER TABLE listings ADD COLUMN IF NOT EXISTS area_unit_residential VARCHAR(20) DEFAULT 'sqft' AFTER area_sqft COMMENT 'sqft or sqyd';