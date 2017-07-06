<?php

namespace Incertitude\SWLRP\Actions;

use Incertitude\SWLRP\Application;
use Incertitude\SWLRP\Action;

class SaveAccount extends Action {
    const MODEL_NAME = 'Account';
    /** @var Session */
    private $session;
    public function __construct(array $data, Application $application) {
        parent::__construct($data, $application);
        $this->session = $application->getSession();
    }
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
        $this->session->login($name, $password, (bool)$this->getData('autologin'));
    }
}
