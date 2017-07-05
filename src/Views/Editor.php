<?php

namespace Incertitude\SWLRP\Views;

class Editor extends Profile {
    protected function getContent(): string {
        return $this->renderTemplate('profile', $this->getProfile());
    }
}
