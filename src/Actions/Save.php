<?php

namespace Incertitude\SWLRP\Actions;
use Incertitude\SWLRP\Action;

class Save extends Action {
    public function execute() {
        $name = ucwords($this->getData(0));
        $this->getModel()->saveName(
            $name,
            ucwords($this->getData(1)),
            ucwords($this->getData(2))
        );
        $this->getModel()->saveProperties(
            $name,
            $this->getData()
        );
    }
}
