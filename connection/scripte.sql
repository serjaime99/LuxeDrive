CREATE DATABASE IF NOT EXISTS LuxeDrive
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE LuxeDrive;

CREATE TABLE role (
    role_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE user (
    user_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    prenom VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role_id INT NOT NULL,
    FOREIGN KEY (role_id) REFERENCES role(role_id)
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE categorie (
    Categorie_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    description VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE vehicule (
    vehicule_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    modele VARCHAR(255) NOT NULL,
    marque VARCHAR(255) NOT NULL,
    prix FLOAT NOT NULL,
    status ENUM('active', 'Maintenance', 'Reserved'),
    vehicule_image VARCHAR(225),
    Categorie_id INT NOT NULL,
    FOREIGN KEY (Categorie_id) REFERENCES categorie(Categorie_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE reservation (
    reservation_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    status ENUM('waiting', 'accepte', 'refuse'),
    user_id INT NOT NULL,
    vehicule_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES user(user_id),
    FOREIGN KEY (vehicule_id) REFERENCES vehicule(vehicule_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE TABLE avis (
    avis_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    commentaire VARCHAR(225),
    date_creation DATE,
    user_id INT NOT NULL,
    deleted_at DATETIME NULL,
    FOREIGN KEY (user_id) REFERENCES user(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE VIEW ListeVehicules AS
SELECT
    v.vehicule_id,
    v.modele,
    v.marque,
    v.prix,
    v.status,
    v.vehicule_image,
    c.nom AS categorie_nom
FROM
    vehicule v
JOIN
    categorie c ON v.Categorie_id = c.Categorie_id;

DELIMITER //
CREATE PROCEDURE AjouterReservation(
    IN p_date_debut DATE,
    IN p_date_fin DATE,
    IN p_user_id INT,
    IN p_vehicule_id INT
)
BEGIN
    INSERT INTO reservation (date_debut, date_fin, status, user_id, vehicule_id)
    VALUES (p_date_debut, p_date_fin, 'waiting', p_user_id, p_vehicule_id);
END //
DELIMITER ;

-- Translated Sample Data
INSERT INTO role (title) VALUES ('Admin'), ('Client');

INSERT INTO categorie (nom, description) VALUES 
('Luxury', 'High-end premium vehicles'),
('Sport', 'High-performance sports cars'),
('SUV', 'Luxury crossover and SUV models'),
('Electric', 'Sustainable luxury performance');

INSERT INTO user (nom, prenom, email, password, role_id) VALUES 
('Admin', 'User', 'admin@luxedrive.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1),
('Client', 'User', 'client@luxedrive.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 2);

INSERT INTO vehicule (modele, marque, prix, status, vehicule_image, Categorie_id) VALUES 
('SF90 Stradale', 'Ferrari', 1200, 'active', '', 2),
('911 Turbo S', 'Porsche', 950, 'active', '', 2),
('G63 AMG', 'Mercedes', 800, 'active', '', 3),
('A8 L', 'Audi', 600, 'active', '', 1),
('Taycan Turbo', 'Porsche', 900, 'active', '', 4),
('Huracan Evo', 'Lamborghini', 1100, 'active', '', 2);
