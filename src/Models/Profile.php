<?php

namespace Incertitude\SWLRP\Models;

use Incertitude\SWLRP\Model;

class Profile extends Model {
    const Q_GET_CHARACTER = <<<'QUERY'
SELECT
    `id`,
    CONCAT(`first`, ' "', `nick`, '" ', `last`) AS `name`
FROM `characters`
WHERE `nick` = :nick
QUERY;
    const Q_GET_IDS = <<<'QUERY'
SELECT
    `c`.`id` AS `character_id`,
    `p`.`id` AS `property_id`,
    `name`
FROM `properties` AS `p`
    LEFT JOIN `characters` AS `c` ON `c`.`nick` = :nick
WHERE `deleted` = 0
QUERY;
    const Q_LOAD_PROPERTIES = <<<'QUERY'
SELECT `name` AS `key`, `value`
FROM `character_properties` LEFT JOIN `properties` AS `p` ON `property_id` = `p`.`id`
WHERE `character_id` = :character_id AND `deleted` = 0
UNION
SELECT `name` AS `key`, `text` AS `value`
FROM `character_texts` LEFT JOIN `properties` AS `p` ON `property_id` = `p`.`id`
WHERE `character_id` = :character_id AND `deleted` = 0
QUERY;
    const Q_SUGGEST_PROPERTY_VALUES = <<<'QUERY'
SELECT `value` FROM `character_properties` AS `cp`
LEFT JOIN `properties` AS `p` ON `cp`.`property_id` = `p`.`id`
WHERE `name` = :name AND `value` LIKE :term
QUERY;
    const Q_SAVE_PROPERTY = <<<'QUERY'
INSERT INTO `character_properties`(`character_id`, `property_id`, `value`)
VALUES (:character_id, :property_id, :value)
ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)
QUERY;
    const Q_SAVE_TEXT = <<<'QUERY'
INSERT INTO `character_texts`(`character_id`, `property_id`, `text`)
VALUES (:character_id, :property_id, :value)
ON DUPLICATE KEY UPDATE `text` = VALUES(`text`)
QUERY;
    const Q_DELETE_PROPS = 'UPDATE `properties` SET `deleted` = 1';
    const Q_UPDATE_PROP = <<<'QUERY'
INSERT INTO `properties`(`name`, `type`, `deleted`)
VALUES (:name, :type, 0)
ON DUPLICATE KEY UPDATE `type` = VALUES(`type`), `deleted` = 0
QUERY;
    public function load(string $name): array {
        $statement = $this->getConnection()->prepare(self::Q_GET_CHARACTER);
        $statement->execute([':nick' => $name]);
        $row = $statement->fetch();
        $statement->closeCursor();
        if (!isset($row['name'])) {
            return [];
        }
        $profile = ['name' => $row['name']];
        $statement = $this->getConnection()->prepare(self::Q_LOAD_PROPERTIES);
        $statement->execute([':character_id' => $row['id'] ?? 0]);
        while ($row = $statement->fetch()) {
            $profile['properties'][$row['key']] = $row['value'];
        }
        return $profile;
    }
    public function saveProperties(string $name, array $data): bool {
        $this->getConnection()->beginTransaction();
        try {
            $statement = $this->getConnection()->prepare(self::Q_GET_IDS);
            $ids = [];
            foreach ($statement->execute([':nick' => $name]) ? (array)$statement->fetchAll() : [] as $prop) {
                $ids[$prop['name']] = $prop;
            }
            $this->batchSave($this->getConfig('*', 'properties'), $ids, self::Q_SAVE_PROPERTY, $data);
            $this->batchSave($this->getConfig('*', 'texts'), $ids, self::Q_SAVE_TEXT, $data);
            $this->getConnection()->commit();
        } catch(\Throwable $t) {
            $this->getConnection()->rollback();
            throw $t;
        }
        return true;
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
    public function suggestPropertyValues(string $name, string $term): array {
        $statement = $this->getConnection()->prepare(self::Q_SUGGEST_PROPERTY_VALUES);
        $term = strtr($term, ['%' => '\%', '_' => '\_']) . '%';
        if ($statement->execute([':name' => $name, ':term' => $term])) {
            return $statement->fetchAll();
        }
        return [];
    }
    private function batchSave($fields, $ids, $query, $data) {
        $statement = $this->getConnection()->prepare($query);
        foreach ($fields as $prop) {
            if (empty($data[$prop['name']]) || empty($ids[$prop['name']])) {
                continue;
            }
            $statement->execute([
                ':character_id' => $ids[$prop['name']]['character_id'],
                ':property_id' => $ids[$prop['name']]['property_id'],
                ':value' => $data[$prop['name']]
            ]);
        }
    }
}
