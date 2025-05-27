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
                      lien_video_tuto VARCHAR(255),
                      lien_regles_pdf VARCHAR(255),
                      categorie VARCHAR(100),
                      id_nb_joueurs_min INT,
                      id_nb_joueurs_max INT,
                      id_duree_partie INT,
                      id_age_minimum INT,
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

CREATE TABLE JeuxBientotDisponibles (
                                        id_bientot INT AUTO_INCREMENT PRIMARY KEY,
                                        nom VARCHAR(100) NOT NULL,
                                        date_prevue DATE,
                                        description TEXT,
                                        visuel VARCHAR(255)
);

INSERT INTO Genre (libelle) VALUES
                                ('Stratégie'),
                                ('Familial'),
                                ('Coopératif'),
                                ('Placement'),
                                ('Cartes');

INSERT INTO TypeJeu (libelle) VALUES
                                  ('Jeu de plateau'),
                                  ('Jeu de cartes'),
                                  ('Jeu coopératif'),
                                  ('Jeu de société');

INSERT INTO Jeux (nom, annee_sortie, id_genre, id_type, description_courte, description_longue, visuel_principal, lien_video_tuto, lien_regles_pdf, categorie, id_nb_joueurs_min, id_nb_joueurs_max, id_duree_partie, id_age_minimum) VALUES
                                                                                                                                                                                                                                          ('Les Aventuriers du Rail', 2004, 2, 1,
                                                                                                                                                                                                                                           'Un jeu de train pour toute la famille.',
                                                                                                                                                                                                                                           'Reliez des villes en construisant des chemins de fer pour compléter vos objectifs.',
                                                                                                                                                                                                                                           'public/images/aventurierdurail.webp',
                                                                                                                                                                                                                                           'https://youtu.be/QgX8dwbdiug?si=u3LcgnJv7QVb7yJO',
                                                                                                                                                                                                                                           'public/pdf/aventuriers-regles.pdf',
                                                                                                                                                                                                                                           'Familial',
                                                                                                                                                                                                                                           2, 5, 60, 8),

                                                                                                                                                                                                                                          ('Dixit', 2008, 2, 2,
                                                                                                                                                                                                                                           'Un jeu avec des images unique.',                                                                                                                                                                                                                                          'Chaque joueur décrit une image, les autres essaient de la retrouver parmi d\'autres.',
'public/images/dixit.jpg',
'https://www.youtube.com/watch?v=exemple2',
'public/pdf/dixit-regles.pdf',
'Créatif',
3, 8, 30, 8),

('Code Names', 2015, 5, 4,
'Deux équipes, des espions, des mots à deviner.',
'Faites deviner les bons mots à votre équipe tout en évitant les pièges.',
'public/images/th.webp',
'https://www.youtube.com/watch?v=exemple3',
'public/pdf/codenames-regles.pdf',
'Déduction',
2, 8, 15, 10),

('Pandemic', 2008, 3, 3,
'Unissez vos forces pour sauver le monde.',
'Empêchez la propagation des maladies et trouvez les remèdes à temps.',
'public/images/pandemic.webp',
'https://www.youtube.com/watch?v=exemple4',
'public/pdf/pandemic-regles.pdf',
'Coopératif',
2, 4, 45, 8),

('Azul', 2017, 4, 1,
'Un jeu de stratégie abstrait et esthétique.',
'Placez des tuiles pour créer les plus beaux motifs tout en optimisant vos choix.',
'public/images/OIP.webp',
'https://www.youtube.com/watch?v=exemple5',
'public/pdf/azul-regles.pdf',
'Abstrait',
2, 4, 30, 8),

('Carcassonne', 2000, 4, 1,
'Construisez un paysage médiéval en tuiles.',
'Posez vos partisans pour marquer des points sur routes, villes et champs.',
'public/images/carcassonne.jpg',
'https://www.youtube.com/watch?v=exemple6',
'public/pdf/carcassonne-regles.pdf',
'Placement',
2, 5, 35, 7),

('7 Wonders', 2010, 1, 1,
'Développez votre civilisation à travers les âges.',
'Construisez des merveilles, gérez vos ressources et dominez vos adversaires.',
'public/images/7-wonders.jpg',
'https://www.youtube.com/watch?v=exemple7',
'public/pdf/7wonders-regles.pdf',
'Civilisation',
2, 7, 30, 10);

INSERT INTO Evenements (titre, date_evenement, duree, capacite, description) VALUES
('Festival du Jeu 2025', '2025-07-10', 'weekend', 150, 'Un week-end de découverte ludique.'),
('Soirée Jeux Famille', '2025-06-15', 'journée', 80, 'Des jeux pour toute la famille.'),
('Nuit du Plateau', '2025-08-20', 'demi-journée', 40, 'Jeux de stratégie en soirée.');

INSERT INTO Participants (nom, prenom, email, nb_accompagnants) VALUES
('Durand', 'Marie', 'marie.durand@example.com', 1),
('Lemoine', 'Paul', 'paul.lemoine@example.com', 0),
('Nguyen', 'Julie', 'julie.nguyen@example.com', 2);

INSERT INTO StatutInscription (libelle) VALUES
('Confirmé'),
('En attente'),
('Annulé');

INSERT INTO Inscriptions (id_participant, id_evenement, id_statut) VALUES
(1, 1, 1),  -- Marie confirmée au Festival
(2, 2, 2),  -- Paul en attente pour Soirée Famille
(3, 3, 1);  -- Julie confirmée pour Nuit du Plateau

INSERT INTO Jeux_Evenement (id_jeu, id_evenement) VALUES
(1, 1), (2, 1), (3, 1), -- Festival : Aventuriers, Dixit, Code Names
(4, 2), (5, 2),         -- Soirée Famille : Pandemic, Azul
(6, 3), (7, 3);         -- Nuit Plateau : Carcassonne, 7 Wonders

INSERT INTO FavorisJeux (id_inscription, id_jeu) VALUES
(1, 1), (1, 3),     -- Marie aime Aventuriers et Code Names
(2, 4),            -- Paul aime Pandemic
(3, 6), (3, 7);    -- Julie aime Carcassonne et 7 Wonders

INSERT INTO JeuxBientotDisponibles (nom, date_prevue, description, visuel) VALUES
('Mystères de l\'Égypte', '2025-09-15', 'Un jeu d\'aventure palpitant dans l\'Égypte ancienne.', 'public/images/myster.jpg'),
                                                                                                                                                                                                                                          ('Galaxies Perdues', '2025-11-10', 'Stratégie et exploration dans un univers futuriste.', 'public/images/galaxy.webp'),
                                                                                                                                                                                                                                          ('Château Enchanté', '2025-12-05', 'Un jeu de rôle magique pour toute la famille.', 'public/images/chateau.jpg');