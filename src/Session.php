<?php

namespace Incertitude\SWLRP;

use Incertitude\SWLRP\Exceptions\NotLoggedIn;
use Incertitude\SWLRP\Models\Account;
use Incertitude\SWLRP\Forwardable;

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
    public function assertLoggedIn(IOComponent $component = null) {
        if (!$this->isLoggedIn()) {
            $ex = new NotLoggedIn();
            if ($component instanceof Forwardable) {
                $ex->setSuffix($component->getRequestString());
            }
            throw $ex;
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
