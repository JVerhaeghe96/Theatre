-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le :  mar. 08 mai 2018 à 09:20
-- Version du serveur :  10.1.26-MariaDB
-- Version de PHP :  7.1.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `theatre_archive`
--

drop database if exists theatre_archive;
create database theatre_archive;
use theatre_archive;


DELIMITER $$
--
-- Procédures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `getReservations` (`p_date` DATE, `p_heure` TIME)  BEGIN
	DECLARE currentNbSieges, currentResId int;
    DECLARE currentNom, currentPrenom varchar(20);
    DECLARE chaise varchar(1500);
	DECLARE done int;
	DECLARE nom, prenom varchar(20);
    DECLARE rId, nbSieges int;
    DECLARE cId varchar(1500);
	DECLARE selReservations CURSOR FOR SELECT s.nom, s.prenom, r.id as resId, r.nbSieges, c.id as cId
                    from spectateurs as s 
                    INNER JOIN reservations as r ON s.id=r.SpecId
                    INNER JOIN chaise as c ON c.ResId=r.id
                    WHERE date=p_date AND  heure=p_heure
                    ORDER BY s.nom, r.id, c.tri;
	declare continue handler for not found set done=1;
    
    create TEMPORARY table tableReservations
    (
        nom varchar(20),
        prenom varchar(20),
        rId int,
        nbSieges int,
        cId varchar(1500)
    );

    set done = 0;
    
    OPEN selReservations;
    
    FETCH NEXT FROM selReservations
    INTO nom, prenom, rId, nbSieges, cId;main_loop: LOOP
    	if done = 1
        then
        	leave main_loop;
        end if;
        
        set currentResId = rId;
        set currentNom = nom;
        set currentPrenom = prenom;
        set currentNbSieges = nbSieges;
        set chaise = cId;
        
        chaise_loop: LOOP
            FETCH NEXT FROM selReservations
            INTO nom, prenom, rId, nbSieges, cId;
            
            if done = 1 OR rId != currentResId
            then
                leave chaise_loop;
            end if;
            
            set chaise = concat(chaise, ", ", cId);
        
        end loop chaise_loop;
        
        insert into tableReservations(nom, prenom, rId, nbSieges, cId) VALUES(
            currentNom, currentPrenom, currentResId, currentNbSieges, chaise);
    
    end loop main_loop;
    
    CLOSE selReservations;
    
    select * from tableReservations;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `administration`
--

CREATE TABLE `administration` (
  `login` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL,
  `salle` varchar(20) NOT NULL DEFAULT 'Z',
  `ajouterSpectacle` char(1) NOT NULL DEFAULT 'Z',
  `ajouterPersonnel` char(1) NOT NULL DEFAULT 'Z',
  `ajouterRepresentation` char(1) NOT NULL DEFAULT 'Z',
  `exporterToutesBoites` char(1) NOT NULL DEFAULT 'Z',
  `modifierSpectateur` char(1) NOT NULL DEFAULT 'Z',
  `envoyerMail` char(1) NOT NULL DEFAULT 'Z',
  `exporterEtiquettes` char(1) NOT NULL DEFAULT 'Z',
  `reservations` char(1) NOT NULL DEFAULT 'Z',
  `ajouterSpectateur` char(1) NOT NULL DEFAULT 'Z',
  `rechercherReservation` char(1) NOT NULL DEFAULT 'Z',
  `rechercherSpectateur` char(1) NOT NULL DEFAULT 'Z',
  `consulterListe` char(1) NOT NULL DEFAULT 'Z',
  `extraction` char(1) NOT NULL DEFAULT 'Z'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `administration`
--

INSERT INTO `administration` (`login`, `password`, `salle`, `ajouterSpectacle`, `ajouterPersonnel`, `ajouterRepresentation`, `exporterToutesBoites`, `modifierSpectateur`, `envoyerMail`, `exporterEtiquettes`, `reservations`, `ajouterSpectateur`, `rechercherReservation`, `rechercherSpectateur`, `consulterListe`, `extraction`) VALUES
('admin', '*A4B6157319038724E3560894F7F932C8886EBFCF', 'M', 'M', 'M', 'M', 'M', 'M', 'M', 'M', 'M', 'M', 'M', 'M', 'M', 'M');

-- --------------------------------------------------------

--
-- Structure de la table `adresse`
--

CREATE TABLE `adresse` (
  `id` int(11) NOT NULL,
  `rue` char(150) NOT NULL,
  `numero` varchar(10) NOT NULL,
  `localite` char(50) NOT NULL,
  `codePostal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Structure de la table `chaise`
--

CREATE TABLE `chaise` (
  `tri` int(11) NOT NULL,
  `date` date NOT NULL,
  `heure` time NOT NULL,
  `id` char(3) NOT NULL,
  `etat` char(1) NOT NULL DEFAULT 'N',
  `ResId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `organiser`
--

CREATE TABLE `organiser` (
  `id` int(11) NOT NULL,
  `titre` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `personnages`
--

CREATE TABLE `personnages` (
  `titre` varchar(20) NOT NULL,
  `nom` varchar(20) NOT NULL,
  `id` int(11) NOT NULL,
  `prenom` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Structure de la table `personnel`
--

CREATE TABLE `personnel` (
  `id` int(11) NOT NULL,
  `nom` varchar(20) NOT NULL,
  `prenom` varchar(15) NOT NULL,
  `fonction` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Structure de la table `representation`
--

CREATE TABLE `representation` (
  `date` date NOT NULL,
  `heure` time NOT NULL,
  `titre` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Structure de la table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `nbSieges` int(11) NOT NULL,
  `remarque` varchar(150) NOT NULL,
  `SpecId` int(11) NOT NULL,
  `titre` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Structure de la table `spectacle`
--

CREATE TABLE `spectacle` (
  `titre` varchar(20) NOT NULL,
  `resume` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Structure de la table `spectateurs`
--

CREATE TABLE `spectateurs` (
  `id` int(11) NOT NULL,
  `nom` varchar(20) NOT NULL,
  `prenom` varchar(20) NOT NULL,
  `telFixe` bigint(20) DEFAULT NULL,
  `telMobile` bigint(20) DEFAULT NULL,
  `adresseMail` varchar(100) DEFAULT NULL,
  `commentaire` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Index pour la table `administration`
--
ALTER TABLE `administration`
  ADD PRIMARY KEY (`login`);

--
-- Index pour la table `adresse`
--
ALTER TABLE `adresse`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `chaise`
--
ALTER TABLE `chaise`
  ADD PRIMARY KEY (`date`,`heure`,`id`),
  ADD KEY `FKconcerner` (`ResId`),
  ADD KEY `IND_tri` (`tri`);

--
-- Index pour la table `organiser`
--
ALTER TABLE `organiser`
  ADD PRIMARY KEY (`id`,`titre`),
  ADD KEY `FKorg_PER` (`id`),
  ADD KEY `FKorg_SPE` (`titre`);

--
-- Index pour la table `personnages`
--
ALTER TABLE `personnages`
  ADD PRIMARY KEY (`titre`,`id`) USING BTREE,
  ADD UNIQUE KEY `FKjouer_par_ID` (`id`);

--
-- Index pour la table `personnel`
--
ALTER TABLE `personnel`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `representation`
--
ALTER TABLE `representation`
  ADD PRIMARY KEY (`date`,`heure`) USING BTREE,
  ADD KEY `FKpresenter` (`titre`);

--
-- Index pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FKreserver` (`SpecId`),
  ADD KEY `FKporter_sur` (`titre`);

--
-- Index pour la table `spectacle`
--
ALTER TABLE `spectacle`
  ADD PRIMARY KEY (`titre`);

--
-- Index pour la table `spectateurs`
--
ALTER TABLE `spectateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UC_spec_telFixe` (`telFixe`),
  ADD UNIQUE KEY `UC_spec_telMob` (`telMobile`),
  ADD UNIQUE KEY `UC_spec_mail` (`adresseMail`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `chaise`
--
ALTER TABLE `chaise`
  MODIFY `tri` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
--
-- AUTO_INCREMENT pour la table `personnel`
--
ALTER TABLE `personnel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
--
-- AUTO_INCREMENT pour la table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
--
-- AUTO_INCREMENT pour la table `spectateurs`
--
ALTER TABLE `spectateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `adresse`
--
ALTER TABLE `adresse`
  ADD CONSTRAINT `FKSPE_adr_FK` FOREIGN KEY (`id`) REFERENCES `spectateurs` (`id`);

--
-- Contraintes pour la table `chaise`
--
ALTER TABLE `chaise`
  ADD CONSTRAINT `FKconcerner` FOREIGN KEY (`ResId`) REFERENCES `reservations` (`id`),
  ADD CONSTRAINT `FKdisposer` FOREIGN KEY (`date`,`heure`) REFERENCES `representation` (`date`, `heure`);

--
-- Contraintes pour la table `organiser`
--
ALTER TABLE `organiser`
  ADD CONSTRAINT `FKorg_PER` FOREIGN KEY (`id`) REFERENCES `personnel` (`id`),
  ADD CONSTRAINT `FKorg_SPE` FOREIGN KEY (`titre`) REFERENCES `spectacle` (`titre`);

--
-- Contraintes pour la table `personnages`
--
ALTER TABLE `personnages`
  ADD CONSTRAINT `FKSPE_per` FOREIGN KEY (`titre`) REFERENCES `spectacle` (`titre`),
  ADD CONSTRAINT `FKjouer_par_FK` FOREIGN KEY (`id`) REFERENCES `personnel` (`id`);

--
-- Contraintes pour la table `representation`
--
ALTER TABLE `representation`
  ADD CONSTRAINT `FKpresenter` FOREIGN KEY (`titre`) REFERENCES `spectacle` (`titre`);

--
-- Contraintes pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `FKporter_sur` FOREIGN KEY (`titre`) REFERENCES `spectacle` (`titre`),
  ADD CONSTRAINT `FKreserver` FOREIGN KEY (`SpecId`) REFERENCES `spectateurs` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
