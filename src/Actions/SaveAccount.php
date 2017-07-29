<?php

namespace Incertitude\SWLRP\Actions;

use Incertitude\SWLRP\Action;
use Incertitude\SWLRP\Models\Account;

/**
 * @method Account getModel()
 */
class SaveAccount extends Action {
    const MODEL_NAME = 'Account';
    public function execute() {
        $characterId = (int)$this->getData(0);
        $names = array_values($this->getNameData());
        $accountId = $this->getModel()->getLoginData($characterId)['account_id'] ?? null;
        $password = $this->getData('password');
        if (!isset($accountId)) {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $accountId = $this->getModel()->createAccount($passwordHash, $characterId, ...$names);
        }
        $this->getSession()->login($characterId, $password);
    }
}
