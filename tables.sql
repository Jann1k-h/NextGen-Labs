CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,

    title ENUM('Herr', 'Frau') NOT NULL,
    firstname VARCHAR(100) NOT NULL,
    lastname VARCHAR(100) NOT NULL,
    username VARCHAR(100) UNIQUE NOT NULL,
    address VARCHAR(255) NOT NULL,
    zipcode VARCHAR(20) NOT NULL,
    city VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    payment_info TEXT,

    remember_token VARCHAR(255),
    remember_token_expires DATETIME,

    is_admin TINYINT(1) DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);