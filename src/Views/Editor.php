<?php

namespace Incertitude\SWLRP\Views;

use Incertitude\SWLRP\Application;
use Incertitude\SWLRP\Forwardable;
use Incertitude\SWLRP\Exceptions\NotLoggedIn;
use Incertitude\SWLRP\Exceptions\ProfileNotFound;

class Editor extends Profile implements Forwardable {
    public function __construct(array $data, Application $application) {
        parent::__construct($data, $application);
        $editMode = self::EDIT_MODE_ENABLED;
        if (!$application->getSession()->isLoggedIn($this->getRequestedId())) {
            $editMode = self::EDIT_MODE_REQUESTED;
        }
        $this->setEditMode($editMode);
    }
    public function isAccessible(): bool {
        try {
            parent::getProfile();
        } catch (ProfileNotFound $ex) {
            return false;
        }
        return true;
    }
    public function forward() {
        throw (new NotLoggedIn())->setSuffix($this->getRequestString());
    }
    protected function getDialogs(): string {
        return parent::getDialogs() . $this->renderTemplate(
            'dialogs/editor', ['name' => $this->getProfile()['nick']]
        );
    }
    protected function decorate(array $prop, string $type): string {
        $attributes = ['data-prop' => json_encode(array_filter($prop))];
        return $this->doDecorate($prop, "editable $type", $attributes);
    }
}
