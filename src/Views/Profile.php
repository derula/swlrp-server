<?php

namespace Incertitude\SWLRP\Views;

use Incertitude\SWLRP\View;

abstract class Profile extends View {
    /** @var array */
    private $profile;
    protected function getTitle(): string {
        return $this->getProfile()['name'] ?? '';
    }
    protected function getProfile(): array {
        if (!isset($this->profile)) {
            $this->profile = $this->getModel()->load($this->getData(0));
        }
        return $this->profile;
    }
}
