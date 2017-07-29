<?php

namespace Incertitude\SWLRP;

use Incertitude\SWLRP\Exceptions\NotLoggedIn;
use Incertitude\SWLRP\Models\Account;

class Session {
    /** @var Account */
    private $model;
    /** @var bool */
    private $useSecureCookies;
    public function __construct(Account $model, bool $useSecureCookies) {
        $this->model = $model;
        $this->useSecureCookies = $useSecureCookies;
        ini_set('session.cookie_httponly', true);
        ini_set('session.cookie_secure', $useSecureCookies);
        session_start();
    }
    public function getCharacterId(): int {
        return $_SESSION['characterId'] ?? 0;
    }
    public function isLoggedIn() {
        return isset($_SESSION['characterId']);
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
