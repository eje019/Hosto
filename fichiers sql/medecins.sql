CREATE TABLE medecins (
    medecin_id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50) NOT NULL,
    prenoms VARCHAR(50) NOT NULL,
    specialite VARCHAR(100),
    telephone VARCHAR(20),
    disponible BOOLEAN DEFAULT TRUE
);