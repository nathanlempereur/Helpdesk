-- Créer la base de données
CREATE DATABASE IF NOT EXISTS SupportTickets;
USE SupportTickets;

-- Table des utilisateurs
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    prenom VARCHAR(255) NOT NULL,
    mail VARCHAR(255) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    pass VARCHAR(255) NOT NULL
);

-- Insertion utilisateurs par défaut
INSERT INTO users (nom, prenom, mail, username, pass) VALUES 
('Lempereur', 'Nathan', 'nlempereur24@lgmarras.org', 'admin', MD5('btsinfo')), 
('Durand', 'Toto', 'toto.durand62@gmail.com', 'toto62', MD5('btsinfo'));

-- Table des admin
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    noma VARCHAR(255) NOT NULL,
    prenoma VARCHAR(255) NOT NULL,
    maila VARCHAR(255) NOT NULL,
    usernamea VARCHAR(50) NOT NULL UNIQUE,
    passa VARCHAR(255) NOT NULL
);
INSERT INTO admins (noma, prenoma, maila, usernamea, passa) VALUES 
('Lempereur', 'Nathan', 'nlempereur24@lgmarras.org', 'AdministrateurLempereur$23012006', MD5('btsinfo')),
('Balens', 'Matthis', 'mbalens24@lgmarras.org', 'AdministrateurBalens$01042005', MD5('btsinfo')),
('Bogala', 'Jan', 'jbogala24@lgmarras.org', 'AdministrateurBogala$12102006', MD5('btsinfo'));


-- Table des types de demande
CREATE TABLE IF NOT EXISTS type_demande (
    id_demande INT AUTO_INCREMENT PRIMARY KEY ,
    typed VARCHAR(50)
);
INSERT INTO type_demande (id_demande, typed) VALUES 
(1, 'Problème logiciel'),
(2, 'Problème matériel'),
(3, 'Problème réseau'),
(4, 'Autre');

-- Table des priorités
CREATE TABLE IF NOT EXISTS priorite(
    id_priorite INT PRIMARY KEY,
    priorited VARCHAR(50)
);
INSERT INTO priorite (id_priorite, priorited) VALUES 
(1, 'basse'),
(2, 'moyenne'),
(3, 'élevée'),
(4, 'critique'),
(5, 'indéfini');

-- Table des systèmes
CREATE TABLE IF NOT EXISTS systeme(
    id_systeme INT AUTO_INCREMENT PRIMARY KEY,
    systemd VARCHAR(50)
);
INSERT INTO systeme (id_systeme, systemd) VALUES 
(1, 'Windows'),
(2, 'Linux'),
(3, 'Mac'),
(4, 'iOS'),
(5, 'Android');

-- Table des services
CREATE TABLE IF NOT EXISTS services(
    id_service INT AUTO_INCREMENT PRIMARY KEY,
    serviced VARCHAR(255)
);
INSERT INTO services (id_service, serviced) VALUES 
(1, 'Administration'),
(2, 'Informatique'),
(3, 'Ressources Humaines'),
(4, 'Service Client'),
(5, 'Comptabilité'),
(6, 'Communication'),
(7, 'Entretien'),
(8, 'Logistique'),
(9, 'Production'),
(10, 'Maintenance'),
(11, 'Sécurité');

-- Table des statuts
CREATE TABLE IF NOT EXISTS statuts(
    id_statut INT PRIMARY KEY,
    statut VARCHAR(50)
);
INSERT INTO statuts (id_statut, statut) VALUES 
(1, 'ouvert'),
(2, 'en cours'),
(3, 'fermé');

-- Table des tickets
CREATE TABLE IF NOT EXISTS tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    prenom VARCHAR(255) NOT NULL,
    id_demande INT NOT NULL,
    titre VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    id_priorite INT NOT NULL DEFAULT 5,
    id_systeme INT NOT NULL,
    id_service INT NOT NULL,
    id_statut INT NOT NULL DEFAULT 1,
    date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_demande) REFERENCES type_demande(id_demande),
    FOREIGN KEY (id_priorite) REFERENCES priorite(id_priorite),
    FOREIGN KEY (id_systeme) REFERENCES systeme(id_systeme),
    FOREIGN KEY (id_service) REFERENCES services(id_service),
    FOREIGN KEY (id_statut) REFERENCES statuts(id_statut)
);
