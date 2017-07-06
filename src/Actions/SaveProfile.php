<?php

namespace Incertitude\SWLRP\Actions;

use Incertitude\SWLRP\Action;

class SaveProfile extends Action {
    public function execute() {
        $name = $this->getSession()->getNickLoggedIn();
        $this->getModel()->saveProperties(
            $name,
            $this->getData()
        );
    }
}
