<?php

namespace Incertitude\SWLRP;

use Incertitude\SWLRP\Exceptions\NotLoggedIn;
use Incertitude\SWLRP\Models\Account;
use Incertitude\SWLRP\Forwardable;

class Session {
    /** @var Account */
    private $model;
    public function __construct(Account $model, bool $useSecureCookies) {
        $this->model = $model;
        ini_set('session.cookie_httponly', true);
        ini_set('session.cookie_secure', $useSecureCookies);
        session_start();
    }
    public function getCharacterId(): int {
        return $_SESSION['characterId'] ?? 0;
    }
    public function isLoggedIn(int $characterId = null): bool {
        if (!isset($_SESSION['characterId'])) {
            return false;
        }
        if (isset($characterId)) {
            return $_SESSION['characterId'] === $characterId;
        }
        return true;
    }
    public function assertLoggedIn() {
        if (!$this->isLoggedIn()) {
            throw new NotLoggedIn();
        }
    }
    public function checkPassword(int $characterId, string $password): bool {
        $data = $this->model->getLoginData($characterId);
        return password_verify($password, $data['password_hash'] ?? '');
    }
    public function login(int $characterId, string $password): bool {
        if (!$this->checkPassword($characterId, $password)) {
            return false;
        }
        $_SESSION['characterId'] = $characterId;
        return true;
    }
}
