-- ===============================
-- CREATION BASE DE DONNEES
-- ===============================
CREATE DATABASE takalo;
USE takalo;

-- ===============================
-- TABLE USERS
-- ===============================
CREATE TABLE takalo_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user','admin') DEFAULT 'user',
    date_inscription DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- ===============================
-- TABLE CATEGORIES
-- ===============================
CREATE TABLE takalo_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL
);

-- ===============================
-- TABLE OBJETS
-- ===============================
CREATE TABLE takalo_objets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    id_categorie INT NOT NULL,
    titre VARCHAR(150) NOT NULL,
    description TEXT,
    prix_estimatif DECIMAL(10,2),
    date_publication DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES takalo_users(id) ON DELETE CASCADE,
    FOREIGN KEY (id_categorie) REFERENCES takalo_categories(id) ON DELETE CASCADE
);

-- ===============================
-- TABLE PHOTOS
-- ===============================
CREATE TABLE takalo_photos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_objet INT NOT NULL,
    chemin VARCHAR(255) NOT NULL,
    FOREIGN KEY (id_objet) REFERENCES takalo_objets(id) ON DELETE CASCADE
);

-- ===============================
-- TABLE ECHANGES
-- ===============================
CREATE TABLE takalo_echanges (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_objet_propose INT NOT NULL,
    id_objet_recu INT NOT NULL,
    date_proposition DATETIME DEFAULT CURRENT_TIMESTAMP,
    statut ENUM('en_attente','accepte','refuse') DEFAULT 'en_attente',
    date_reponse DATETIME,
    FOREIGN KEY (id_objet_propose) REFERENCES takalo_objets(id) ON DELETE CASCADE,
    FOREIGN KEY (id_objet_recu) REFERENCES takalo_objets(id) ON DELETE CASCADE
);

-- ===============================
-- TABLE HISTORIQUE OBJET
-- ===============================
CREATE TABLE takalo_historique_objet (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_objet INT NOT NULL,
    ancien_proprietaire INT NOT NULL,
    nouveau_proprietaire INT NOT NULL,
    date_echange DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_objet) REFERENCES takalo_objets(id) ON DELETE CASCADE,
    FOREIGN KEY (ancien_proprietaire) REFERENCES takalo_users(id),
    FOREIGN KEY (nouveau_proprietaire) REFERENCES takalo_users(id)
);
