SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'offen',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) CHARACTER SET utf8mb4;

INSERT INTO tasks (title, description, status) VALUES
('Docker kennenlernen', 'Container und Docker Compose verstehen', 'erledigt'),
('PHP-Grundlagen lernen', 'Variablen, Schleifen und Funktionen', 'offen'),
('MySQL verstehen', 'SELECT, INSERT, UPDATE und DELETE üben', 'offen'),
('Erste Webanwendung erstellen', 'Aufgabenverwaltung mit PHP und MySQL bauen', 'offen');