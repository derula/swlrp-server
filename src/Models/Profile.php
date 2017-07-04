<?php

namespace Incertitu\SWLRP\Views;
use Incertitu\SWLRP\Model;

class Profile extends Model {
    const Q_LOAD = <<<'QUERY'
SELECT
    CONCAT(`first`, ' "', `nick`, '" ', `last`) AS `name`,
    COALESCE(`p2`.`name`, `p1`.`name`) AS `key`,
    COALESCE(`text`, `value`) AS `value`
FROM `characters` AS `c`
    LEFT JOIN `character_properties` AS `cp` ON `c`.`id` = `cp`.`character_id`
    LEFT JOIN `properties` AS `p1` ON `cp`.`property_id` = `p1`.`id`
    LEFT JOIN `character_texts` AS `ct` ON `c`.`id` = `ct`.`character_id`
    LEFT JOIN `properties` AS `p2` ON `ct`.`property_id` = `p2`.`id`
WHERE `nick` = :nick;
QUERY;
    public function load(string $name): ?array {
        $statement = $this->getConnection()->prepare(self::Q_LOAD);
        if ($statement->execute([':nick' => $name])) {
            return $statement->fetchAll();
        }
        return null;
    }
    public function save(string $name) {
        // Implementation
    }
}
