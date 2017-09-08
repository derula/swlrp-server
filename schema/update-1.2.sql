ALTER TABLE `accounts` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
UPDATE `accounts` SET `password_hash` = CONVERT(CAST(CONVERT(`password_hash` USING latin1) AS BINARY) USING utf8mb4);
UPDATE `accounts` SET `session_hash` = CONVERT(CAST(CONVERT(`session_hash` USING latin1) AS BINARY) USING utf8mb4) WHERE `session_hash` IS NOT NULL;

ALTER TABLE `characters` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
UPDATE `characters` SET
     `first` = CONVERT(CAST(CONVERT(`first` USING latin1) AS BINARY) USING utf8mb4),
     `nick` = CONVERT(CAST(CONVERT(`nick` USING latin1) AS BINARY) USING utf8mb4),
     `last` = CONVERT(CAST(CONVERT(`last` USING latin1) AS BINARY) USING utf8mb4);
UPDATE `characters` SET `portrait` = CONVERT(CAST(CONVERT(`portrait` USING latin1) AS BINARY) USING utf8mb4) WHERE `portrait` IS NOT NULL;

ALTER TABLE `character_properties` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
UPDATE `character_properties` SET `value` = CONVERT(CAST(CONVERT(`value` USING latin1) AS BINARY) USING utf8mb4);

ALTER TABLE `character_texts` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
UPDATE `character_texts` SET `text` = CONVERT(CAST(CONVERT(`text` USING latin1) AS BINARY) USING utf8mb4);
