CREATE TABLE consultations (
    consultation_id INT PRIMARY KEY AUTO_INCREMENT,
    nom_patient VARCHAR(100) NOT NULL,
    date_heure DATETIME NOT NULL,
    motif TEXT NOT NULL,
    medecin_id INT,
    statut ENUM("en attente", "en cours", "terminé") DEFAULT "en attente",
    FOREIGN KEY (medecin_id) REFERENCES medecins(medecin_id) ON DELETE SET NULL
);