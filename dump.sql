USE registration_db;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
id INT AUTO_INCREMENT PRIMARY KEY,
first_name VARCHAR(100) NOT NULL,
last_name VARCHAR(100) NOT NULL,
birthdate DATE NOT NULL,
report_subject TEXT NOT NULL,
country VARCHAR(100) NOT NULL,
phone VARCHAR(50) NOT NULL,
email VARCHAR(150) UNIQUE NOT NULL,
company TEXT,
position TEXT,
about_me TEXT,
photo_path VARCHAR(255) DEFAULT 'default.jpg',
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;