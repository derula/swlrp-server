ALTER TABLE `characters`
    CHANGE `id` `id` INT(11) NOT NULL,
    CHANGE `nick` `nick` VARCHAR(255) NOT NULL AFTER `first`,
    DROP INDEX nick;
