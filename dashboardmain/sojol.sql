CREATE DATABASE IF NOT EXISTS sojol;
USE sojol;

CREATE TABLE users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    mobile VARCHAR(15) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('superadmin', 'admin') DEFAULT 'admin',
    status ENUM('pending', 'active') DEFAULT 'pending',
    profile_image VARCHAR(255) DEFAULT 'https://via.placeholder.com/32',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Super Admin: Email: super@admin.com | Pass: admin123
INSERT INTO users (name, email, mobile, password, role, status) 
VALUES ('Super Admin', 'super@admin.com', '01700000000', 'admin123', 'superadmin', 'active');