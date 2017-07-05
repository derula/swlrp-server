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
    protected function getProfile(): array {
        return parent::getProfile() + ['editMode' => true];
    }
    protected function decorate(array $prop, string $type) {
        $data = htmlspecialchars(json_encode($prop));
        return "<span class=\"editable $type\" data-prop=\"$data\">$prop[value]</span>";
    }
}
