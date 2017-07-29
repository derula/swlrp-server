<?php

namespace Incertitude\SWLRP\Models;

use Incertitude\SWLRP\Model;
use Incertitude\SWLRP\Exceptions\RegistrationFailed;

class Account extends Model {
    const Q_GET_LOGIN_DATA = <<<'QUERY'
SELECT `account_id`, `password_hash`, `session_hash`
FROM `accounts` AS `a`
INNER JOIN `characters` AS `c` ON `c`.`account_id` = `a`.`id`
WHERE `nick` = :nick
QUERY;
    const Q_SET_PASSWORD_HASH = <<<'QUERY'
UPDATE `accounts` AS `a`
INNER JOIN `characters` AS `c` ON `c`.`account_id` = `a`.`id`
SET `password_hash` = :hash
WHERE `nick` = :nick
QUERY;
    const Q_CREATE_ACCOUNT = <<<'QUERY'
INSERT INTO `accounts` (`password_hash`)
VALUES (:hash)
QUERY;
    const Q_SAVE_NAME = <<<'QUERY'
INSERT INTO `characters`(`account_id`, `nick`, `first`, `last`)
VALUES (:account_id, :nick, :first, :last)
ON DUPLICATE KEY UPDATE
     `account_id` = VALUES(`account_id`), `first` = VALUES(`first`), `last` = VALUES(`last`)
QUERY;
    public function getLoginData(string $nick): array {
        $statement = $this->getConnection()->prepare(self::Q_GET_LOGIN_DATA);
        $statement->execute([':nick' => $nick]);
        $result = $statement->fetch();
        $statement->closeCursor();
        return $result ?: [];
    }
    public function setPasswordHash(string $nick, string $sessionHash): bool {
        return $this->getConnection()->prepare(self::Q_SET_PASSWORD_HASH)
            ->execute([':nick' => $nick, ':hash' => $sessionHash]);
    }
    public function isRegistered(string $nick): bool {
        $data = $this->getLoginData($nick);
        return !empty($data);
    }
    public function createAccount(string $nick, string $passwordHash, string $first, string $last): int {
        $this->getConnection()->beginTransaction();
        try {
            $this->getConnection()->prepare(self::Q_CREATE_ACCOUNT)
                ->execute([':hash' => $passwordHash]);
            $accountId = $this->getConnection()->lastInsertId();
            $statement = $this->getConnection()->prepare(self::Q_SAVE_NAME);
            $result = $statement->execute([
                ':account_id' => $accountId, ':nick' => $nick, ':first' => $first, ':last' => $last
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
}
