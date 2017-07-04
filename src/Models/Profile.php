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
WHERE `nick` = :nick AND COALESCE(`p1`.`deleted`, `p2`.`deleted`, 0) = 0
QUERY;
    const Q_DELETE_PROPS = 'UPDATE `properties` SET `deleted` = 1';
    const Q_UPDATE_PROP = <<<'QUERY'
INSERT INTO `properties`(`name`, `type`, `deleted`)
VALUES (:name, :type, 0)
ON DUPLICATE KEY UPDATE `type` = VALUES(`type`), `deleted` = 0
QUERY;
    public function load(string $name): ?array {
        $statement = $this->getConnection()->prepare(self::Q_LOAD);
        if ($statement->execute([':nick' => $name])) {
            return $statement->fetchAll();
        }
        return null;
    }
    public function save(string $name, array $data): bool {
        $this->getConnection()->beginTransaction();
        try {
            // Implementation
            $this->getConnection()->commit();
        } catch(\Throwable $t) {
            $this->getConnection()->rollback();
            throw $t;
        }
    }
    public function refreshProperties() {
        $this->getConnection()->beginTransaction();
        try {
            $this->getConnection()->prepare(self::Q_DELETE_PROPS)->execute();
            $statement = $this->getConnection()->prepare(self::Q_UPDATE_PROP);
            foreach ($this->getConfig('*', 'properties') as $prop) {
                $statement->execute([':name' => $prop['name'], ':type' => 'property']);
            }
            foreach ($this->getConfig('*', 'texts') as $prop) {
                $statement->execute([':name' => $prop['name'], ':type' => 'text']);
            }
            $this->getConnection()->commit();
        } catch(\Throwable $t) {
            $this->getConnection()->rollback();
            throw $t;
        }
    }
}
