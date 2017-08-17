<?php

namespace Incertitude\SWLRP\Actions;

use Incertitude\SWLRP\Action;

class ShowHomepage extends Action {
    public function execute() {
        header('Location: /home');
    }
}
