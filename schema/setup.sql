CREATE TABLE `characters` (
    `id` INT(11) NOT NULL,
    `nick` VARCHAR(40) NOT NULL,
    `first` VARCHAR(255) NOT NULL,
    `last` VARCHAR(255) NOT NULL,
    `first_write` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE (`nick`)
) ENGINE=InnoDB;

CREATE TABLE `properties` (
    `id` INT(11) NOT NULL,
    `name` VARCHAR(20) NOT NULL,
    `type` ENUM('property', 'text') NOT NULL,
    PRIMARY KEY (`id`)
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
