-- The `bugme` database
CREATE DATABASE IF NOT EXISTS bugme;
USE bugme;

-- The `users` table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(255) NOT NULL,
    lastname VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- The `issues` table
CREATE TABLE IF NOT EXISTS issues (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    type VARCHAR(50) NOT NULL,
    priority VARCHAR(50) NOT NULL,
    status VARCHAR(50) DEFAULT 'Open',
    assigned_to INT NOT NULL,
    created_by INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
);

-- Insert an admin user with hashed password
INSERT INTO users (firstname, lastname, password, email, created_at)
VALUES (
    'Admin',
    'User',
    '$2y$10$C4yE9dveRYxG1Gz2dKIrYOMP1BD9mHJ2j0AQ9wQO3V/hBaJlZs/8W', -- Hash for 'password123'
    'admin@project2.com',
    NOW()
);

-- Sample data to `issues` for testing 
INSERT INTO issues (title, description, type, priority, status, assigned_to, created_by, created_at)
VALUES
    ('Bug in Login', 'Users are unable to log in using their credentials.', 'Bug', 'High', 'Open', 1, 1, NOW()),
    ('UI Update Request', 'Update the dashboard layout for better usability.', 'Feature Request', 'Medium', 'Closed', 1, 1, NOW());
