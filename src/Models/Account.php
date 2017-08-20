<?php

namespace Incertitude\SWLRP\Models;

use Incertitude\SWLRP\Model;
use Incertitude\SWLRP\Exceptions\RegistrationFailed;
use RandomLib\Factory as RNGFactory;
use RandomLib\Generator as RNG;

class Account extends Model {
    const Q_GET_LOGIN_DATA = <<<'QUERY'
SELECT `account_id`, `password_hash`, `session_hash`
FROM `accounts` AS `a`
INNER JOIN `characters` AS `c` ON `c`.`account_id` = `a`.`id`
WHERE `c`.`id` = :characterId
QUERY;
    const Q_SET_PASSWORD_HASH = <<<'QUERY'
UPDATE `accounts` AS `a`
INNER JOIN `characters` AS `c` ON `c`.`account_id` = `a`.`id`
SET `password_hash` = :hash
WHERE `c`.`id` = :characterId
QUERY;
    const Q_GET_CHARACTER_ID = <<<'QUERY'
SELECT `id` FROM `characters`
WHERE `first` = :first AND `nick` = :nick AND `last` = :last
QUERY;
    const Q_CREATE_ACCOUNT = <<<'QUERY'
INSERT INTO `accounts` (`password_hash`)
VALUES (:hash)
QUERY;
    const Q_SAVE_NAME = <<<'QUERY'
INSERT INTO `characters`(`id`, `account_id`, `first`, `nick`, `last`)
VALUES (:characterId, :account_id, :first, :nick, :last)
ON DUPLICATE KEY UPDATE
    `account_id` = VALUES(`account_id`),
    `first` = IF(VALUES(`first`) = '', `first`, VALUES(`first`)),
    `nick`  = IF(VALUES(`nick`)  = '', `nick`,  VALUES(`nick`)),
    `last`  = IF(VALUES(`last`)  = '', `last`,  VALUES(`last`))
QUERY;
    public function getLoginData(int $characterId): array {
        $statement = $this->getConnection()->prepare(self::Q_GET_LOGIN_DATA);
        $statement->execute([':characterId' => $characterId]);
        $result = $statement->fetch();
        $statement->closeCursor();
        return $result ?: [];
    }
    public function setPasswordHash(int $characterId, string $sessionHash): bool {
        return $this->getConnection()->prepare(self::Q_SET_PASSWORD_HASH)
            ->execute([':characterId' => $characterId, ':hash' => $sessionHash]);
    }
    public function resetPassword(string $first, string $nick, string $last): string {
        $statement = $this->getConnection()->prepare(self::Q_GET_CHARACTER_ID);
        if ($statement->execute([':first' => $first, ':nick' => $nick, ':last' => $last])) {
            $characterId = $statement->fetchColumn();
        }
        if (empty($characterId)) {
            throw new \InvalidArgumentException('Character not found.');
        }
        $password = (new RNGFactory())->getHighStrengthGenerator()->generateString(16, RNG::EASY_TO_READ);
        $this->setPasswordHash($characterId, password_hash($password, PASSWORD_DEFAULT));
        return $password;
    }
    public function isRegistered(int $characterId): bool {
        $data = $this->getLoginData($characterId);
        return !empty($data);
    }
    public function createAccount(string $passwordHash, int $characterId, string $first, string $nick, string $last): int {
        $this->getConnection()->beginTransaction();
        try {
            $this->getConnection()->prepare(self::Q_CREATE_ACCOUNT)
                ->execute([':hash' => $passwordHash]);
            $accountId = $this->getConnection()->lastInsertId();
            $statement = $this->getConnection()->prepare(self::Q_SAVE_NAME);
            $result = $statement->execute([
                ':characterId' => $characterId, ':account_id' => $accountId,
                ':first' => $first, ':nick' => $nick, ':last' => $last
            ]);
            if (!$accountId || !$result) {
                throw new RegistrationFailed();
            }
            $this->getConnection()->commit();
        } catch (\Throwable $ex) {
            $this->getConnection()->rollback();
            throw $ex;
        }
        return $result;
    }
    public function setCharacterName(int $accountId, int $characterId, string $first, string $nick, string $last): bool {
        $statement = $this->getConnection()->prepare(self::Q_SAVE_NAME);
        return $statement->execute([
            ':characterId' => $characterId, ':account_id' => $accountId,
            ':first' => $first, ':nick' => $nick, ':last' => $last
        ]);
    }
}
