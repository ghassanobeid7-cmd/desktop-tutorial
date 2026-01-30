-- SQL script to create the appointments database and table

CREATE DATABASE IF NOT EXISTS appointments_db;

USE appointments_db;

CREATE TABLE IF NOT EXISTS appointments (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    service VARCHAR(50) NOT NULL,
    date DATE NOT NULL,
    time TIME NOT NULL,
    UNIQUE KEY unique_appointment (service, date, time)
);
