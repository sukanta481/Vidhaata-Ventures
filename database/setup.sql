CREATE DATABASE IF NOT EXISTS vidhaata_ventures
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE vidhaata_ventures;

CREATE TABLE IF NOT EXISTS listings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  type ENUM('residential', 'commercial') NOT NULL,
  title VARCHAR(255) NOT NULL,
  description TEXT,
  price DECIMAL(12,2),
  location VARCHAR(255),
  bedrooms TINYINT DEFAULT NULL,
  area_sqft INT,
  image_filename VARCHAR(255),
  is_featured TINYINT(1) DEFAULT 0,
  status ENUM('active', 'sold', 'inactive') DEFAULT 'active',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS leads (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  phone VARCHAR(20),
  email VARCHAR(255),
  message TEXT,
  source_page VARCHAR(100),
  status ENUM('new', 'contacted', 'closed') DEFAULT 'new',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
