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
        if (!isset($_SESSION['nick']) && isset($_COOKIE['autologin'])) {
            $nick = $this->model->getAutoLoginNick($_COOKIE['autologin']);
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
    public function checkPassword(string $nick, string $password): bool {
        $data = $this->model->getLoginData($nick);
        return password_verify($password, $data['password_hash'] ?? '');
    }
    public function login(string $nick, string $password, bool $autoLogin): bool {
        if (!$this->checkPassword($nick, $password)) {
            return false;
        }
        $_SESSION['nick'] = $nick;
        $sessionHash = '';
        if ($autoLogin) {
            $sessionHash = hash('sha512', "$nick.$password." . microtime());
            setcookie(
                'autologin', $sessionHash, (new \DateTime('+ 1 month'))->getTimestamp(),
                '/', '', $this->useSecureCookies, true
            );
        } else {
            setcookie('autologin', '', time() - 3600);
        }
        $this->model->setAutoLoginHash($nick, $sessionHash);
        return true;
    }
}
