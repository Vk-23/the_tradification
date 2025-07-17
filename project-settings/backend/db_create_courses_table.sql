-- SQL to create courses table
CREATE TABLE courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    thumbnail VARCHAR(255) DEFAULT NULL,
    short_description TEXT DEFAULT NULL,
    long_description TEXT DEFAULT NULL,
    lectures INT DEFAULT NULL,
    level VARCHAR(50) DEFAULT NULL,
    price DECIMAL(10,2) DEFAULT NULL,
    banner VARCHAR(255) DEFAULT NULL,
    banner_active TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES course_categories(id) ON DELETE CASCADE
);
