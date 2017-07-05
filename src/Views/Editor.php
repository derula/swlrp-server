<?php

namespace Incertitude\SWLRP\Views;

class Editor extends Profile {
    protected function preRender() {
        $this->getModel()->saveName(
            $this->getRequestedName(),
            ucwords($this->getData(1)),
            ucwords($this->getData(2))
        );
    }
}
