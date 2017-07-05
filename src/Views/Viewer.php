<?php

namespace Incertitude\SWLRP\Views;

class Viewer extends Profile {
    protected function getContent(): string {
        return $this->renderTemplate('profile', $this->getProfile());
    }
}
