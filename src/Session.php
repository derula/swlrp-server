<?php

namespace Incertitude\SWLRP;

use Incertitude\SWLRP\Exceptions\NotLoggedIn;
use Incertitude\SWLRP\Models\Account;

class Session {
    /** @var Account */
    private $model;
    public function __construct(Account $model) {
        $this->model = $model;
        session_start();
        if (!isset($_SESSION['nick']) && isset($_COOKIES['autologin'])) {
            $nick = $this->model->getAutoLoginNick($_COOKIES['autologin']);
            if (!empty($nick)) {
                $_SESSION['nick'] = $nick;
            }
        }
    }
    public function getNickLoggedIn(): string {
        return $_SESSION['nick'] ?? '';
    }
    public function isLoggedIn() {
        return isset($_SESSION['nick']);
    }
    public function assertLoggedIn() {
        if (!$this->isLoggedIn()) {
            throw new NotLoggedIn();
        }
    }
    public function login(string $nick, string $password, bool $autoLogin): bool {
        $data = $this->model->getLoginData($nick);
        if (password_verify($password, $data['password_hash'] ?? '')) {
            $_SESSION['nick'] = $nick;
            if ($autoLogin) {
                $sessionHash = crypt("$nick.$password." . time());
                setcookie('autologin', $sessionHash);
            }
            return true;
        }
        return false;
    }
}
