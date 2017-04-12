CREATE TABLE IF NOT EXISTS `tirage` (
  `id`      INT(10) UNSIGNED    NOT NULL AUTO_INCREMENT,
  `jour`    DATE                NOT NULL,
  `boule1`  TINYINT(3) UNSIGNED NOT NULL,
  `boule2`  TINYINT(3) UNSIGNED NOT NULL,
  `boule3`  TINYINT(3) UNSIGNED NOT NULL,
  `boule4`  TINYINT(3) UNSIGNED NOT NULL,
  `boule5`  TINYINT(3) UNSIGNED NOT NULL,
  `etoile1` TINYINT(3) UNSIGNED NOT NULL,
  `etoile2` TINYINT(3) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tirage_uk` (`jour`)
);

CREATE TABLE IF NOT EXISTS `joker_plus` (
  `id`        INT(10) UNSIGNED    NOT NULL AUTO_INCREMENT,
  `tirage_id` INT(10) UNSIGNED    NOT NULL,
  `chiffre1`  TINYINT(3) UNSIGNED NOT NULL,
  `chiffre2`  TINYINT(3) UNSIGNED NOT NULL,
  `chiffre3`  TINYINT(3) UNSIGNED NOT NULL,
  `chiffre4`  TINYINT(3) UNSIGNED NOT NULL,
  `chiffre5`  TINYINT(3) UNSIGNED NOT NULL,
  `chiffre6`  TINYINT(3) UNSIGNED NOT NULL,
  `chiffre7`  TINYINT(3) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `joker_plus_uk` (`tirage_id`)
);

ALTER TABLE `joker_plus`
  ADD FOREIGN KEY (`tirage_id`)
REFERENCES `tirage` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

CREATE TABLE IF NOT EXISTS `my_million` (
  `id`        INT(10) UNSIGNED    NOT NULL AUTO_INCREMENT,
  `tirage_id` INT(11) UNSIGNED    NOT NULL,
  `lettre1`   VARCHAR(1)          NOT NULL,
  `lettre2`   VARCHAR(1)          NOT NULL,
  `chiffre1`  TINYINT(3) UNSIGNED NOT NULL,
  `chiffre2`  TINYINT(3) UNSIGNED NOT NULL,
  `chiffre3`  TINYINT(3) UNSIGNED NOT NULL,
  `chiffre4`  TINYINT(3) UNSIGNED NOT NULL,
  `chiffre5`  TINYINT(3) UNSIGNED NOT NULL,
  `chiffre6`  TINYINT(3) UNSIGNED NOT NULL,
  `chiffre7`  TINYINT(3) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `my_million_uk` (`tirage_id`)
);

ALTER TABLE `my_million`
  ADD FOREIGN KEY (`tirage_id`)
REFERENCES `tirage` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

CREATE TABLE IF NOT EXISTS `gagnant` (
  `id`             INT(10) UNSIGNED    NOT NULL AUTO_INCREMENT,
  `tirage_id`      INT(11) UNSIGNED    NOT NULL,
  `bons_numeros`   TINYINT(3) UNSIGNED NOT NULL,
  `bonnes_etoiles` TINYINT(3) UNSIGNED NOT NULL,
  `nombre`         MEDIUMINT(9)        NOT NULL,
  `gains`          INT(10) UNSIGNED    NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `gagnant_uk` (`tirage_id`, `bons_numeros`, `bonnes_etoiles`)
);

ALTER TABLE `gagnant`
  ADD FOREIGN KEY (`tirage_id`)
REFERENCES `tirage` (`id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;