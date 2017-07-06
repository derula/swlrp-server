<?php

namespace Incertitude\SWLRP;

abstract class Action extends IOComponent {
    /** @var Session */
    private $session;
    public function __construct(array $data, Application $application) {
        parent::__construct($data, $application);
        $this->session = $application->getSession();
    }
    protected function getSession(): Session {
        return $this->session;
    }
    abstract public function execute();
}
