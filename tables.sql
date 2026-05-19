/* Benutzer */
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

/* Kurstabellen */

/* Kategorien */
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

/* Beispielkategorien */
INSERT INTO categories (name, description) VALUES
('Programmierung', 'Kurse rund um Softwareentwicklung und Coding'),
('Webentwicklung', 'Frontend und Backend Web-Technologien'),
('Datenbanken', 'SQL, NoSQL und Datenmodellierung'),
('Business & Management', 'Projektmanagement und Unternehmensführung'),
('Design', 'UI/UX, Grafikdesign und kreative Tools'),
('Marketing', 'Online Marketing, SEO und Social Media'),
('IT & Netzwerke', 'Systemadministration und Netzwerktechnik'),
('Data Science', 'Datenanalyse, Machine Learning und KI');

/* Kurse */
CREATE TABLE courses (
    id INT AUTO_INCREMENT PRIMARY KEY,

    category_id INT NOT NULL,

    title VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    rating DECIMAL(2,1) DEFAULT 0.0,

    stock INT NOT NULL DEFAULT 0,

    lecturer_name VARCHAR(255),
    lecturer_contact TEXT,

    is_active TINYINT(1) DEFAULT 1,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_courses_category
        FOREIGN KEY (category_id) REFERENCES categories(id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);

/* Beispielkurse */
INSERT INTO courses 
(category_id, title, description, price, rating, stock, lecturer_name, lecturer_contact, is_active)
VALUES
(1, 'Java Grundlagen', 'Einsteigerkurs für Java Programmierung', 99.99, 4.5, 10, 'Max Mustermann', 'max@example.com', 1),
(1, 'Python für Anfänger', 'Lerne Python von Grund auf', 79.99, 4.7, 5, 'Anna Schmidt', 'anna@example.com', 1),
(2, 'HTML & CSS Masterclass', 'Erstelle moderne Webseiten', 59.99, 4.3, 0, 'Tom Weber', 'tom@example.com', 1),
(2, 'JavaScript Bootcamp', 'Interaktive Webseiten entwickeln', 89.99, 4.6, 8, 'Lisa Müller', 'lisa@example.com', 1),
(3, 'SQL Basics', 'Grundlagen von relationalen Datenbanken', 49.99, 4.2, 15, 'Peter Klein', 'peter@example.com', 1),
(3, 'Advanced SQL', 'Joins, Subqueries und Optimierung', 69.99, 4.8, 3, 'Julia Bauer', 'julia@example.com', 1),
(4, 'Projektmanagement', 'Agile & klassische Methoden', 119.99, 4.4, 7, 'Michael Huber', 'michael@example.com', 1),
(5, 'UI/UX Design Basics', 'Benutzerfreundliche Interfaces gestalten', 74.99, 4.5, 6, 'Sophie Lang', 'sophie@example.com', 1),
(6, 'SEO Grundlagen', 'Suchmaschinenoptimierung verstehen', 39.99, 4.1, 12, 'Daniel Koch', 'daniel@example.com', 1),
(8, 'Machine Learning Intro', 'Einführung in KI und ML', 129.99, 4.9, 4, 'Laura Fischer', 'laura@example.com', 1);

/* Kursbilder */
CREATE TABLE course_images (
    id INT AUTO_INCREMENT PRIMARY KEY,

    course_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    alt_text VARCHAR(255),
    sort_order INT NOT NULL DEFAULT 0,
    is_cover TINYINT(1) DEFAULT 0,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_course_images_course
        FOREIGN KEY (course_id) REFERENCES courses(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

/* Beispielbilder für Kurse */
INSERT INTO course_images (course_id, image_path, alt_text, sort_order, is_cover) VALUES
-- Java Grundlagen
(1, '/assets/course_images/java.png', 'Java Kurs', 1, 1),
-- Python
(2, '/assets/course_images/python.png', 'Python Kurs', 1, 1),
-- HTML CSS
(3, '/assets/course_images/html-css.png', 'HTML CSS Kurs', 1, 1),
-- JavaScript
(4, '/assets/course_images/javascript.png', 'JavaScript Kurs', 1, 1),
-- SQL Basics
(5, '/assets/course_images/sql.png', 'SQL Kurs', 1, 1),
-- Advanced SQL
(6, '/assets/course_images/sql-advanced.png', 'Advanced SQL', 1, 1),
-- Projektmanagement
(7, '/assets/course_images/project.png', 'Projektmanagement', 1, 1),
-- UI UX
(8, '/assets/course_images/design.png', 'Design Kurs', 1, 1),
-- SEO
(9, '/assets/course_images/seo.png', 'SEO Kurs', 1, 1),
-- Machine Learning
(10, '/assets/course_images/ml.png', 'Machine Learning', 1, 1);


/* Warenkorb */
CREATE TABLE cart_items (
    id INT AUTO_INCREMENT PRIMARY KEY,

    user_id INT NULL,
    guest_token VARCHAR(255) NULL,
    course_id INT NOT NULL,

    quantity INT DEFAULT 1,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    UNIQUE KEY unique_user_course (user_id, course_id),
    UNIQUE KEY unique_guest_course (guest_token, course_id),

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

/* Bestellungen */
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,

    user_id INT NOT NULL,

    billing_title ENUM('Herr','Frau') NOT NULL,
    billing_firstname VARCHAR(100) NOT NULL,
    billing_lastname VARCHAR(100) NOT NULL,
    billing_address VARCHAR(255) NOT NULL,
    billing_zipcode VARCHAR(20) NOT NULL,
    billing_city VARCHAR(100) NOT NULL,
    billing_email VARCHAR(255) NOT NULL,
    billing_payment_info TEXT,

    status VARCHAR(50) NOT NULL DEFAULT 'pending',
    total_price DECIMAL(10,2) NOT NULL DEFAULT 0.00,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_orders_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);

/* Bestellpositionen */
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,

    order_id INT NOT NULL,
    course_id INT NOT NULL,

    quantity INT NOT NULL DEFAULT 1,
    price DECIMAL(10,2) NOT NULL,

    course_for VARCHAR(255) NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_order_items_order
        FOREIGN KEY (order_id) REFERENCES orders(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT fk_order_items_course
        FOREIGN KEY (course_id) REFERENCES courses(id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);