DROP DATABASE IF EXISTS `Projet-Technologique`;

CREATE DATABASE `Projet-Technologique`;

USE `Projet-Technologique`;

CREATE TABLE Administrateurs (
    id_admin INT AUTO_INCREMENT PRIMARY KEY,
    nom_utilisateur VARCHAR(100) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL
);

CREATE TABLE Genre (
    id_genre INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE TypeJeu (
    id_type INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE Jeux (
    id_jeu INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    annee_sortie INT,
    id_genre INT,
    id_type INT,
    description_courte TEXT,
    description_longue TEXT,
    visuel_principal VARCHAR(255),
    FOREIGN KEY (id_genre) REFERENCES Genre(id_genre) ON DELETE SET NULL,
    FOREIGN KEY (id_type) REFERENCES TypeJeu(id_type) ON DELETE SET NULL
);

CREATE TABLE Ressources (
    id_ressource INT AUTO_INCREMENT PRIMARY KEY,
    id_jeu INT NOT NULL,
    type VARCHAR(50),
    url VARCHAR(255),
    FOREIGN KEY (id_jeu) REFERENCES Jeux(id_jeu) ON DELETE CASCADE
);

CREATE TABLE Evenements (
    id_evenement INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(100) NOT NULL,
    date_evenement DATE NOT NULL,
    duree ENUM('demi-journée', 'journée', 'weekend') NOT NULL,
    capacite INT NOT NULL,
    description TEXT
);

CREATE TABLE Jeux_Evenement (
    id_jeu INT,
    id_evenement INT,
    PRIMARY KEY (id_jeu, id_evenement),
    FOREIGN KEY (id_jeu) REFERENCES Jeux(id_jeu) ON DELETE CASCADE,
    FOREIGN KEY (id_evenement) REFERENCES Evenements(id_evenement) ON DELETE CASCADE
);

CREATE TABLE Participants (
    id_participant INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    prenom VARCHAR(100),
    email VARCHAR(150) NOT NULL,
    nb_accompagnants INT DEFAULT 0,
    UNIQUE(email)
);

CREATE TABLE StatutInscription (
    id_statut INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE Inscriptions (
    id_inscription INT AUTO_INCREMENT PRIMARY KEY,
    id_participant INT NOT NULL,
    id_evenement INT NOT NULL,
    id_statut INT NOT NULL,
    UNIQUE(id_participant, id_evenement),
    FOREIGN KEY (id_participant) REFERENCES Participants(id_participant) ON DELETE CASCADE,
    FOREIGN KEY (id_evenement) REFERENCES Evenements(id_evenement) ON DELETE CASCADE,
    FOREIGN KEY (id_statut) REFERENCES StatutInscription(id_statut) ON DELETE RESTRICT
);

CREATE TABLE FavorisJeux (
    id_inscription INT,
    id_jeu INT,
    PRIMARY KEY (id_inscription, id_jeu),
    FOREIGN KEY (id_inscription) REFERENCES Inscriptions(id_inscription) ON DELETE CASCADE,
    FOREIGN KEY (id_jeu) REFERENCES Jeux(id_jeu) ON DELETE CASCADE
);