
CREATE DATABASE IF NOT EXISTS raselhos_sojol;
USE raselhos_sojol;


CREATE TABLE IF NOT EXISTS users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    mobile VARCHAR(15) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('superadmin', 'admin', 'employee') DEFAULT 'admin',
    status ENUM('pending', 'active') DEFAULT 'pending',
    profile_image VARCHAR(255) DEFAULT 'https://via.placeholder.com/32',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE IF NOT EXISTS `customers` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `admin_id` INT NOT NULL,          
  `customer_name` VARCHAR(255) NOT NULL,
  `mobile` VARCHAR(20) DEFAULT NULL,  
  `total_amount` DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
  `paid_amount` DECIMAL(10, 2) DEFAULT 0.00,
  `total_due` DECIMAL(10, 2) DEFAULT 0.00,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE IF NOT EXISTS `bill_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `customer_id` INT NOT NULL,         
  `customer_name` VARCHAR(255),      
  `item_name` VARCHAR(255) DEFAULT NULL, 
  `price` DECIMAL(10, 2) DEFAULT 0.00,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`customer_id`) REFERENCES `customers`(`id`) ON DELETE CASCADE
);



CREATE TABLE IF NOT EXISTS `notes` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `admin_id` INT NOT NULL,  
    `title` VARCHAR(255) DEFAULT NULL, 
    `content` TEXT NOT NULL, 
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`admin_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
);



INSERT INTO users (name, email, mobile, password, role, status) 
VALUES (
    'Super Admin', 
    'super@admin.com', 
    '01700000000', 
    '$2y$10$009prmSxAkDMpBZtRTf3L.Q0xJCIF/MdAMTZrXZ6h/bmdHfCZD73e',
    'superadmin', 
    'active'
);