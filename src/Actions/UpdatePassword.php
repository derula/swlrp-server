<?php

namespace Incertitude\SWLRP\Actions;

use Incertitude\SWLRP\Action;
use Incertitude\SWLRP\Exceptions\PwChangeFailed;
use Incertitude\SWLRP\Models\Account;

/**
 * @method Account getModel()
 */
class UpdatePassword extends Action {
    const MODEL_NAME = 'Account';
    public function execute() {
        $id = $this->getSession()->getCharacterId();
        if (!$this->getSession()->checkPassword($id, $this->getData('password') ?: '')) {
            http_response_code(401);
            exit;
        }
        $password = $this->getData('pwnew') ?: '';
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $this->getModel()->setPasswordHash($id, $passwordHash);
    }
}
