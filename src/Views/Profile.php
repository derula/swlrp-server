<?php

namespace Incertitude\SWLRP\Views;

use Incertitude\SWLRP\View;
use Incertitude\SWLRP\Exceptions\ProfileNotFound;

abstract class Profile extends View {
    /** @var array */
    private $profile;
    protected function getTitle(): string {
        return $this->getProfile()['name'];
    }
    protected function getContent(): string {
        return $this->renderTemplate('profile', $this->getProfile());
    }
    protected function getProfile(): array {
        if (!isset($this->profile)) {
            $this->profile = $this->getModel()->load($this->getRequestedName());
        }
        if (empty($this->profile)) {
            throw new ProfileNotFound();
        }
        return $this->profile;
    }
    protected function getRequestedName(): string {
        return ucfirst($this->getData(0));
    }
}
