CREATE TABLE IF NOT EXISTS webinar_testimonials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    webinar_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    message TEXT DEFAULT NULL,
    rating INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (webinar_id) REFERENCES webinars(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
