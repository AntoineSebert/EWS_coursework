-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  Dim 07 avr. 2019 à 09:21
-- Version du serveur :  5.7.24
-- Version de PHP :  7.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `ewsc`
--
CREATE DATABASE IF NOT EXISTS `ewsc` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `ewsc`;

DELIMITER $$
--
-- Procédures
--
DROP PROCEDURE IF EXISTS `add_feed`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `add_feed` (IN `user` VARCHAR(64), IN `feed` VARCHAR(255))  MODIFIES SQL DATA
INSERT INTO user (url) VALUES (feed)$$

DROP PROCEDURE IF EXISTS `get_user_feed`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `get_user_feed` (IN `email` VARCHAR(64) CHARSET utf8)  READS SQL DATA
SELECT * FROM publication
WHERE publication.feed = (
    SELECT subscriptions FROM user WHERE user.email = email
)$$

DROP PROCEDURE IF EXISTS `remove_feed`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `remove_feed` (IN `email` VARCHAR(64), IN `_feed` VARCHAR(255))  MODIFIES SQL DATA
DELETE FROM email WHERE feed = _feed$$

DROP PROCEDURE IF EXISTS `user_exists`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `user_exists` (IN `_email` VARCHAR(64), OUT `result` INT)  READS SQL DATA
SELECT email INTO result FROM user WHERE email = _email$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `feeds`
--

DROP TABLE IF EXISTS `feeds`;
CREATE TABLE IF NOT EXISTS `feeds` (
  `url` varchar(255) COLLATE utf8_bin NOT NULL,
  `type` enum('rss','atom') COLLATE utf8_bin NOT NULL DEFAULT 'rss',
  `definition` text COLLATE utf8_bin NOT NULL COMMENT 'XML',
  PRIMARY KEY (`url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `publications_atom`
--

DROP TABLE IF EXISTS `publications_atom`;
CREATE TABLE IF NOT EXISTS `publications_atom` (
  `feed_url` varchar(255) COLLATE utf8_bin NOT NULL,
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_bin NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `author_s` text COLLATE utf8_bin,
  `link` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `summary` text COLLATE utf8_bin,
  `category_ies` text COLLATE utf8_bin,
  `contributor_s` text COLLATE utf8_bin NOT NULL,
  `published` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `rights` text COLLATE utf8_bin,
  `source` text COLLATE utf8_bin,
  PRIMARY KEY (`feed_url`,`id`) USING BTREE,
  UNIQUE KEY `id` (`id`),
  KEY `feed` (`feed_url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `publications_rss`
--

DROP TABLE IF EXISTS `publications_rss`;
CREATE TABLE IF NOT EXISTS `publications_rss` (
  `feed_url` varchar(255) COLLATE utf8_bin NOT NULL,
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` tinytext COLLATE utf8_bin NOT NULL,
  `link` varchar(255) COLLATE utf8_bin NOT NULL,
  `description` text COLLATE utf8_bin NOT NULL,
  `language` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `copyright` text COLLATE utf8_bin,
  `managingEditor` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `webMaster` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `pubDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `lastBuildDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `category` text COLLATE utf8_bin,
  `generator` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `docs` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `cloud` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `ttl` int(11) DEFAULT NULL,
  `image` longblob,
  `rating` int(11) DEFAULT NULL,
  `textInput` text COLLATE utf8_bin,
  `skipHours` int(11) DEFAULT NULL,
  `skipDays` int(11) DEFAULT NULL,
  PRIMARY KEY (`feed_url`,`id`) USING BTREE,
  UNIQUE KEY `id` (`id`),
  KEY `feed` (`feed_url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Structure de la table `subscriptions`
--

DROP TABLE IF EXISTS `subscriptions`;
CREATE TABLE IF NOT EXISTS `subscriptions` (
  `user_email` varchar(64) COLLATE utf8_bin NOT NULL,
  `feed_url` varchar(255) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`user_email`,`feed_url`),
  KEY `feed_url` (`feed_url`),
  KEY `user_email` (`user_email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déclencheurs `subscriptions`
--
DROP TRIGGER IF EXISTS `clean_feeds`;
DELIMITER $$
CREATE TRIGGER `clean_feeds` AFTER DELETE ON `subscriptions` FOR EACH ROW DELETE FROM feeds WHERE NOT EXISTS (SELECT feed_url FROM subscriptions WHERE feed_url = OLD.feed_url)
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `email` varchar(64) COLLATE utf8_bin NOT NULL,
  `hash` varchar(255) COLLATE utf8_bin NOT NULL,
  `last_connection` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`email`, `hash`, `last_connection`) VALUES
('test', 'zdcdvsfsfbg', '2019-04-06 09:55:18');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `publications_atom`
--
ALTER TABLE `publications_atom`
  ADD CONSTRAINT `publications_atom_ibfk_1` FOREIGN KEY (`feed_url`) REFERENCES `feeds` (`url`) ON DELETE CASCADE;

--
-- Contraintes pour la table `publications_rss`
--
ALTER TABLE `publications_rss`
  ADD CONSTRAINT `publications_rss_ibfk_1` FOREIGN KEY (`feed_url`) REFERENCES `feeds` (`url`) ON DELETE CASCADE;

--
-- Contraintes pour la table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `subscriptions_ibfk_1` FOREIGN KEY (`user_email`) REFERENCES `users` (`email`) ON DELETE CASCADE,
  ADD CONSTRAINT `subscriptions_ibfk_2` FOREIGN KEY (`feed_url`) REFERENCES `feeds` (`url`) ON DELETE CASCADE,
  ADD CONSTRAINT `subscriptions_ibfk_3` FOREIGN KEY (`user_email`) REFERENCES `users` (`email`) ON DELETE CASCADE,
  ADD CONSTRAINT `subscriptions_ibfk_4` FOREIGN KEY (`user_email`) REFERENCES `users` (`email`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
