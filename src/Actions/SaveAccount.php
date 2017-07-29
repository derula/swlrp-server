<?php

namespace Incertitude\SWLRP\Actions;

use Incertitude\SWLRP\Action;

class SaveAccount extends Action {
    const MODEL_NAME = 'Account';
    public function execute() {
        $name = ucwords($this->getData(0));
        $accountId = $this->getModel()->getLoginData($name)['account_id'] ?? null;
        $password = $this->getData('password');
        if (!isset($accountId)) {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $accountId = $this->getModel()->createAccount(
                $name, $passwordHash, ucwords($this->getData(1)) ?: '', ucwords($this->getData(2)) ?: ''
            );
        }
        $this->getSession()->login($name, $password);
    }
}
