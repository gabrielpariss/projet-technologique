DROP DATABASE IF EXISTS `Projet-Technologique`;
CREATE DATABASE `Projet-Technologique`;
USE `Projet-Technologique`;

-- 1. Administrateurs
CREATE TABLE Administrateurs (
    id_admin INT AUTO_INCREMENT PRIMARY KEY,
    nom_utilisateur VARCHAR(100) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL
);

-- 2. Genres et Types
CREATE TABLE Genre (
    id_genre INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(100) NOT NULL UNIQUE
);
CREATE TABLE TypeJeu (
    id_type INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(100) NOT NULL UNIQUE
);

-- 3. Jeux (avec extensions de Gabriel, toutes NULL-able)
CREATE TABLE Jeux (
    id_jeu INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    annee_sortie INT,
    id_genre INT,
    id_type INT,
    description_courte TEXT,
    description_longue TEXT,
    visuel_principal VARCHAR(255),
    lien_video_tuto VARCHAR(255)       DEFAULT NULL,
    lien_regles_pdf VARCHAR(255)       DEFAULT NULL,
    categorie VARCHAR(100)             DEFAULT NULL,
    id_nb_joueurs_min INT              DEFAULT NULL,
    id_nb_joueurs_max INT              DEFAULT NULL,
    id_duree_partie INT                DEFAULT NULL,
    id_age_minimum INT                 DEFAULT NULL,
    FOREIGN KEY (id_genre) REFERENCES Genre(id_genre) ON DELETE SET NULL,
    FOREIGN KEY (id_type)  REFERENCES TypeJeu(id_type) ON DELETE SET NULL
);

-- 4. Ressources associées aux jeux
CREATE TABLE Ressources (
    id_ressource INT AUTO_INCREMENT PRIMARY KEY,
    id_jeu INT NOT NULL,
    type VARCHAR(50),
    url VARCHAR(255),
    FOREIGN KEY (id_jeu) REFERENCES Jeux(id_jeu) ON DELETE CASCADE
);

-- 5. Événements et leur liaison aux jeux
CREATE TABLE Evenements (
    id_evenement INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(100) NOT NULL,
    date_evenement DATE NOT NULL,
    duree ENUM('demi-journée','journée','weekend') NOT NULL,
    capacite INT NOT NULL,
    description TEXT
);
CREATE TABLE Jeux_Evenement (
    id_jeu INT,
    id_evenement INT,
    PRIMARY KEY (id_jeu,id_evenement),
    FOREIGN KEY (id_jeu)       REFERENCES Jeux(id_jeu) ON DELETE CASCADE,
    FOREIGN KEY (id_evenement) REFERENCES Evenements(id_evenement) ON DELETE CASCADE
);

-- 6. Participants (avec mot_de_passe)
CREATE TABLE Participants (
    id_participant INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    prenom VARCHAR(100),
    email VARCHAR(150) NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    nb_accompagnants INT DEFAULT 0,
    UNIQUE(email)
);

-- 7. Statuts et Inscriptions
CREATE TABLE StatutInscription (
    id_statut INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL UNIQUE
);
CREATE TABLE Inscriptions (
    id_inscription INT AUTO_INCREMENT PRIMARY KEY,
    id_participant INT NOT NULL,
    id_evenement INT NOT NULL,
    id_statut INT NOT NULL,
    UNIQUE(id_participant,id_evenement),
    FOREIGN KEY (id_participant) REFERENCES Participants(id_participant) ON DELETE CASCADE,
    FOREIGN KEY (id_evenement)   REFERENCES Evenements(id_evenement)   ON DELETE CASCADE,
    FOREIGN KEY (id_statut)      REFERENCES StatutInscription(id_statut) ON DELETE RESTRICT
);

-- 8. Favoris de jeux par inscription
CREATE TABLE FavorisJeux (
    id_inscription INT,
    id_jeu INT,
    PRIMARY KEY (id_inscription,id_jeu),
    FOREIGN KEY (id_inscription) REFERENCES Inscriptions(id_inscription) ON DELETE CASCADE,
    FOREIGN KEY (id_jeu)         REFERENCES Jeux(id_jeu)               ON DELETE CASCADE
);

-- 9. Table “Jeux bientôt disponibles” de Gabriel
CREATE TABLE JeuxBientotDisponibles (
    id_bientot INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    date_prevue DATE,
    description TEXT,
    visuel VARCHAR(255)
);

-- ============
-- INSERTIONS
-- ============

-- Admin
INSERT INTO Administrateurs (nom_utilisateur, mot_de_passe)
VALUES ('admin','admin');

-- Genres
INSERT INTO Genre (libelle) VALUES 
('Stratégie'),
('Familial'),
('Coopératif'),
('Placement'),
('Cartes');

-- Types
INSERT INTO TypeJeu (libelle) VALUES
('Jeu de plateau'),
('Jeu de cartes'),
('Jeu coopératif'),
('Jeu de société');

-- Jeux existants (vos données, les colonnes étendues resteront à NULL)
INSERT INTO Jeux
  (nom,annee_sortie,id_genre,id_type,description_courte,description_longue,visuel_principal)
VALUES
('Les Aventuriers du Rail',2004,2,1,
 'Un jeu de train pour toute la famille.',
 'Reliez des villes en construisant des chemins de fer pour compléter vos objectifs.',
 'visuels/aventuriers_rail.jpg'),
('Dixit',2008,2,2,
 'Un jeu d interprétation d images unique.',
 'Chaque joueur décrit une image, les autres essaient de la retrouver parmi d autres.',
 'visuels/dixit.jpg'),
('Code Names',2015,5,4,
 'Deux équipes, des espions, des mots à deviner.',
 'Faites deviner les bons mots à votre équipe tout en évitant les pièges.',
 'visuels/codenames.jpg'),
('Pandemic',2008,3,3,
 'Unissez vos forces pour sauver le monde.',
 'Empêchez la propagation des maladies et trouvez les remèdes à temps.',
 'visuels/pandemic.jpg'),
('Azul',2017,4,1,
 'Un jeu de stratégie abstrait et esthétique.',
 'Placez des tuiles pour créer les plus beaux motifs tout en optimisant vos choix.',
 'visuels/azul.jpg'),
('Carcassonne',2000,4,1,
 'Construisez un paysage médiéval en tuiles.',
 'Posez vos partisans pour marquer des points sur routes, villes et champs.',
 'visuels/carcassonne.jpg'),
('7 Wonders',2010,1,1,
 'Développez votre civilisation à travers les âges.',
 'Construisez des merveilles, gérez vos ressources et dominez vos adversaires.',
 'visuels/7wonders.jpg');

-- Événements
INSERT INTO Evenements (titre,date_evenement,duree,capacite,description) VALUES
('Festival du Jeu 2025','2025-07-10','weekend',150,'Un week-end de découverte ludique.'),
('Soirée Jeux Famille','2025-06-15','journée',80,'Des jeux pour toute la famille.'),
('Nuit du Plateau','2025-08-20','demi-journée',40,'Jeux de stratégie en soirée.'),
('Tournoi Masters', '2025-09-05', 'journée', 100, 'Un tournoi compétitif pour les joueurs confirmés.'),
('Brunch Ludique',  '2025-10-12', 'demi-journée',  50, 'Un moment gourmand et convivial autour de jeux de société.');

-- Participants (avec mot_de_passe '0000')
INSERT INTO Participants (nom,prenom,email,nb_accompagnants,mot_de_passe) VALUES
('Durand','Marie','marie.durand@example.com',1,'0000'),
('Lemoine','Paul','paul.lemoine@example.com',0,'0000'),
('Nguyen','Julie','julie.nguyen@example.com',2,'0000');

-- Statuts
INSERT INTO StatutInscription (libelle) VALUES
('Confirmé'),
('En attente'),
('Annulé');

-- Inscriptions
INSERT INTO Inscriptions (id_participant,id_evenement,id_statut) VALUES
(1,1,1),
(2,2,2),
(3,3,1);

-- Liaisons Jeux ↔ Événements
INSERT INTO Jeux_Evenement (id_jeu,id_evenement) VALUES
(1,1),(2,1),(3,1),
(4,2),(5,2),
(6,3),(7,3);

-- Favoris
INSERT INTO FavorisJeux (id_inscription,id_jeu) VALUES
(1,1),(1,3),
(2,4),
(3,6),(3,7);

-- Jeux bientôt disponibles (exemples de Gabriel)
INSERT INTO JeuxBientotDisponibles (nom,date_prevue,description,visuel) VALUES
('Mystères de l''Égypte','2025-09-15',
 'Un jeu d''aventure palpitant dans l''Égypte ancienne.',
 'public/images/myster.jpg'),
('Galaxies Perdues','2025-11-10',
 'Stratégie et exploration dans un univers futuriste.',
 'public/images/galaxy.webp'),
('Château Enchanté','2025-12-05',
 'Un jeu de rôle magique pour toute la famille.',
 'public/images/chateau.jpg');

