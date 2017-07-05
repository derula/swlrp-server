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
        return $_SESSION['nick'];
    }
    public function isLoggedIn() {
        return isset($_SESSION['nick']);
    }
    public function assertLoggedIn() {
        if (!$this->isLoggedIn($nick)) {
            throw new NotLoggedIn();
        }
    }
    public function register(string $nick, string $first, string $last, string $password, bool $autoLogin): bool {
        $data = $this->model->getLoginData($nick);
        if (!empty($data)) {
            return false;
        }
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $accountId = $this->model->createAccount($passwordHash);
        if ($this->login($nick, $password, $autoLogin)) {
            return $this->model->saveName($accountId, $name, $first, $last);
        }
        return false;
    }
    public function login(string $nick, string $password, bool $autoLogin): bool {
        $data = $this->model->getLoginData($nick);
        if (password_verify($password, $data['password_hash'] ?? '')) {
            $_SESSION['nick'] = $nick;
            $sessionHash = crypt("$first.$nick.$last.$password." . time());
            if ($autologin) {
                setcookie($sessionHash);
            }
            return true;
        }
        return false;
    }
}
