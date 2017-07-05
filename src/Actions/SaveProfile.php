<?php

namespace Incertitude\SWLRP\Actions;

use Incertitude\SWLRP\Action;

class SaveProfile extends Action {
    public function execute() {
        $name = ucwords($this->getData(0));
        $this->getModel()->saveProperties(
            $name,
            $this->getData()
        );
    }
}
