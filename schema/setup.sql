CREATE TABLE `accounts` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `email` VARCHAR(40) DEFAULT NULL,
    `password_hash` VARCHAR(255) NOT NULL,
    `session_hash` VARCHAR(255) DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE (`email`)
) ENGINE=InnoDB;

CREATE TABLE `characters` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `account_id` int(11) NOT NULL,
    `nick` VARCHAR(40) NOT NULL,
    `first` VARCHAR(255) NOT NULL,
    `last` VARCHAR(255) NOT NULL,
    `first_write` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE (`nick`)
) ENGINE=InnoDB;

CREATE TABLE `properties` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(20) NOT NULL,
    `type` ENUM('property', 'text') NOT NULL,
    `deleted` BIT(1) NOT NULL DEFAULT b'0',
    PRIMARY KEY (`id`),
    UNIQUE (`name`)
) ENGINE=InnoDB;

CREATE TABLE `character_properties` (
    `character_id` INT(11) NOT NULL,
    `property_id` INT(11) NOT NULL,
    `value` VARCHAR(40) NOT NULL,
    PRIMARY KEY (`character_id`, `property_id`),
    KEY `value` (`value`)
) ENGINE=InnoDB;

CREATE TABLE `character_texts` (
    `character_id` INT(11) NOT NULL,
    `property_id` INT(11) NOT NULL,
    `text` TEXT NOT NULL,
    PRIMARY KEY (`character_id`, `property_id`)
) ENGINE=InnoDB;
