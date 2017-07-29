<?php

namespace Incertitude\SWLRP\Actions;

use Incertitude\SWLRP\Action;
use Incertitude\SWLRP\Models\Profile;

/**
 * @method Profile getModel()
 */
class SaveProfile extends Action {
    public function execute() {
        $id = $this->getSession()->getCharacterId();
        $this->getModel()->saveProperties(
            $id,
            $this->getData()
        );
    }
}
