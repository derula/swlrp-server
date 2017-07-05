<?php

namespace Incertitude\SWLRP\Models;

use Incertitude\SWLRP\Model;

class Account extends Model {
    const Q_GET_LOGIN_DATA = <<<'QUERY'
SELECT `password_hash`, `session_hash`
FROM `accounts` AS `a`
INNER JOIN `characters` AS `c` ON `c`.`account_id` = `a`.`id`
WHERE `nick` = :nick
QUERY;
    const Q_GET_AUTOLOGIN_NICK = <<<'QUERY'
SELECT `nick`
FROM `characters` AS `c`
INNER JOIN `accounts` AS `a` ON `c`.`account_id` = `a`.`id`
WHERE `session_hash` = :hash
QUERY;
    const Q_CREATE_ACCOUNT = <<<'QUERY'
INSERT INTO `accounts` (`password_hash`)
VALUES (:pw_hash)
QUERY;
    const Q_SAVE_NAME = <<<'QUERY'
INSERT INTO `characters`(`nick`, `first`, `last`)
VALUES (:nick, :first, :last)
ON DUPLICATE KEY UPDATE `first` = VALUES(`first`), `last` = VALUES(`last`)
QUERY;
    public function getLoginData(string $nick): array {
        $statement = $this->getConnection()->prepare(self::Q_GET_LOGIN_DATA);
        $statement->execute([':nick' => $nick]);
        $result = $statement->fetch();
        $statement->closeCursor();
        return $result ?: [];
    }
    public function getAutoLoginNick(string $sessionHash): string {
        $statement = $this->getConnection()->prepare(self::Q_GET_AUTOLOGIN_NICK);
        $statement->execute([':hash' => $sessionHash]);
        $result = $statement->fetch()['nick'] ?? '';
        $statement->closeCursor();
        return $result;
    }
    public function createAccount(string $passwordHash): int {
        $this->getConnection()->prepare(self::Q_CREATE_ACCOUNT)
            ->execute([':pw_hash' => $passwordHash, ':s_hash' => $sessionHash]);
        return $this->getConnection()->lastInsertId();
    }
    public function saveName(int $accountId, string $name, string $first, string $last): bool {
        $statement = $this->getConnection()->prepare(self::Q_SAVE_NAME);
        return $statement->execute([':nick' => $name, ':first' => $first, ':last' => $last]);
    }
}
