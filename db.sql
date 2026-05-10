CREATE DATABASE IF NOT EXISTS socialnet;
USE socialnet;

CREATE TABLE IF NOT EXISTS account (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    fullname VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    description TEXT,
    status VARCHAR(20) DEFAULT 'offline',
    security_answer VARCHAR(255) DEFAULT 'my_secret'
);

INSERT INTO account (username, fullname, password, description) 
VALUES ('admin_test', 'Administrator', '$2y$10$89.P.A62J6P3J6.B6.F6.O6.J6.P.A62J6P3J6.B6.F6.O6.J6.', 'Sample account of Admin');

CREATE TABLE friends (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,     -- Người gửi lời mời
    friend_id INT NOT NULL,   -- Người nhận lời mời
    status ENUM('pending', 'accepted') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

