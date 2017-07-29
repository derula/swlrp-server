<?php

namespace Incertitude\SWLRP\Views;

use Incertitude\SWLRP\Application;
use Incertitude\SWLRP\LayoutView;
use Incertitude\SWLRP\Session;
use Incertitude\SWLRP\Exceptions\IsLoggedIn;
use Incertitude\SWLRP\Models\Account;

/**
 * @method Account getModel()
 */
class Front extends LayoutView {
    const MODEL_NAME = 'Account';
    const STATE_NOT_LOGGED_IN = 0;
    const STATE_LOGIN = 1;
    const STATE_REGISTER = 2;
    const STATES = [
        self::STATE_NOT_LOGGED_IN => ['Not logged in', 'notLoggedIn'],
        self::STATE_LOGIN => ['Log in', 'login'],
        self::STATE_REGISTER => ['Choose password', 'register'],
    ];
    /** @var Session */
    private $session;
    /** @var string */
    private $state;
    public function __construct(array $data, Application $application) {
        parent::__construct($data, $application);
        $this->session = $application->getSession();
        $characterId = $this->getData(0) ?: $this->session->getCharacterId();
        if (empty($characterId)) {
            $this->state = self::STATE_NOT_LOGGED_IN;
        } elseif (!$this->session->isLoggedIn($this->getData(0))) {
            $this->state = $this->getModel()->isRegistered($characterId) ? self::STATE_LOGIN : self::STATE_REGISTER;
        } else {
            throw new IsLoggedIn();
        }
    }
    protected function getTitle(): string {
        return self::STATES[$this->state][0];
    }
    protected function getContent(): string {
        return $this->renderTemplate(self::STATES[$this->state][1], ['names' => $this->getNameData()]);
    }
}
