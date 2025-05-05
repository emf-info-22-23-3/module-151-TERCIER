-- Création de la base de données
CREATE DATABASE IF NOT EXISTS bd_ProjectVolcan;
USE bd_ProjectVolcan;
-- Création de la table des pays
CREATE TABLE IF NOT EXISTS t_pays (
    PK_pays INT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL
);
-- Création de la table des volcans
CREATE TABLE IF NOT EXISTS t_volcan (
    PK_volcan INT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL,
    altitude INT,
    longitude DECIMAL(9,6),
    latitude DECIMAL(9,6),
    FK_pays INT,
    FOREIGN KEY (FK_pays) REFERENCES t_pays(PK_pays) ON DELETE CASCADE
);
-- Table des administrateurs
CREATE TABLE IF NOT EXISTS t_admin (     
	PK_admin INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(30) NOT NULL,
    pass VARCHAR(64) NOT NULL 
);
-- Insertion de l'admin Pa$$w0rd
INSERT INTO t_admin(PK_admin, nom, pass) VALUES
(1, 'admin', '$2y$10$bHa2rxFsay0vxUgENCXCb.hddJuHv7wBYh8sgrkWCDy2dtZzDs2vm');
-- Insertion des pays
INSERT INTO t_pays (PK_pays, nom) VALUES
(1, 'Afghanistan'),
(2, 'Algeria'),
(3, 'Algeria-Niger'),
(4, 'Antarctica'),
(5, 'Argentina'),
(6, 'Armenia');
-- Insertion des volcans
INSERT INTO t_volcan (PK_volcan, nom, altitude, longitude, latitude, FK_pays) VALUES
(232060, 'Dacht-i-Navar Group', 3800, 67.920000, 33.950000, 1),
(232070, 'Vakak Group', 3190, 67.970000, 34.250000, 1),
(225004, 'Tahalra Volcanic Field', 1467, 5.000000, 22.670000, 2),
(225005, 'Atakor Volcanic Field', 2918, 5.830000, 23.330000, 2),
(225006, 'Manzaz Volcanic Field', 1672, 5.830000, 23.920000, 2),
(225003, 'In Ezzane Volcanic Field', 0, 10.830000, 23.000000, 3),
(390010, 'Buckle Island', 1239, 163.250000, -66.780000, 4),
(390011, 'Young Island', 1340, 162.470000, -66.420000, 4),
(390012, 'Sturge Island', 1167, 164.830000, -67.400000, 4),
(390013, 'Pleiades, The', 3040, 165.500000, -72.670000, 4),
(390014, 'Unnamed', 2987, 164.580000, -73.450000, 4),
(390015, 'Melbourne', 2732, 164.700000, -74.350000, 4),
(355150, 'Tuzgle', 5486, -66.480000, -24.050000, 5),
(355160, 'Aracar', 6095, -67.783000, -24.290000, 5),
(355161, 'Unnamed', 0, -68.270000, -25.100000, 5),
(355180, 'Antofagasta Volcanic Field', 3495, -67.400000, -26.120000, 5),
(355190, 'Condor, El', 6373, -68.361000, -26.632000, 5),
(355200, 'Peinado', 5741, -68.116000, -26.623000, 5),
(214060, 'Aragats', 4095, 44.200000, 40.530000, 6),
(214070, 'Ghegam Ridge', 3597, 44.750000, 40.275000, 6),
(214080, 'Dar-Alages', 1637, 45.551000, 39.704000, 6),
(214090, 'Porak', 3029, 45.740000, 40.028000, 6),
(214100, 'Tskhouk-Karckar', 3000, 46.020000, 39.730000, 6);