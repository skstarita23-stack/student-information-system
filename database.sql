CREATE DATABASE IF NOT EXISTS student_information_db;
USE student_information_db;

CREATE TABLE students (
    student_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    gender VARCHAR(20) NOT NULL,
    date_of_birth DATE NOT NULL,
    course VARCHAR(100) NOT NULL,
    year_level INT NOT NULL,
    email VARCHAR(100) NOT NULL,
    contact_number VARCHAR(30) NOT NULL
);

INSERT INTO students (first_name, last_name, gender, date_of_birth, course, year_level, email, contact_number) VALUES
('Maria',  'Santos',    'Female', '2004-03-15', 'BS Information Technology', 2, 'maria.santos@example.com',  '09171234567'),
('Juan',   'Dela Cruz', 'Male',   '2003-07-22', 'BS Computer Science',       3, 'juan.delacruz@example.com', '09181234567'),
('Angela', 'Reyes',     'Female', '2005-01-10', 'BS Education',              1, 'angela.reyes@example.com',  '09191234567'),
('Mark',   'Garcia',    'Male',   '2002-11-05', 'BS Business Administration',4, 'mark.garcia@example.com',   '09201234567'),
('Sam',    'Lopez',     'Other',  '2004-09-30', 'BS Nursing',                2, 'sam.lopez@example.com',     '09211234567');

CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL
);

INSERT INTO users (username, password, full_name)
VALUES ('admin', '$2y$10$uKvYeL2CxWE2TkxRHfNpju8hcjRFXovE2iwLSdgOpMmox.gd0y2n2', 'Administrator')
ON DUPLICATE KEY UPDATE password = VALUES(password), full_name = VALUES(full_name);
