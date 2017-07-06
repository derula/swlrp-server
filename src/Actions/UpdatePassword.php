<?php

namespace Incertitude\SWLRP\Actions;

use Incertitude\SWLRP\Action;
use Incertitude\SWLRP\Exceptions\PwChangeFailed;

class UpdatePassword extends Action {
    const MODEL_NAME = 'Account';
    public function execute() {
        $name = $this->getSession()->getNickLoggedIn();
        if (!$this->getSession()->checkPassword($name, $this->getData('password') ?: '')) {
            http_response_code(401);
            exit;
        }
        $password = $this->getData('pwnew') ?: '';
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $this->getModel()->setPasswordHash($name, $passwordHash);
    }
}
