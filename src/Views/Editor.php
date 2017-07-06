<?php

namespace Incertitude\SWLRP\Views;

use Incertitude\SWLRP\Application;

class Editor extends Profile {
    public function __construct(array $data, Application $application) {
        parent::__construct($data, $application);
        $this->setRequestedName($application->getSession()->getNickLoggedIn());
    }
    protected function getProfile(): array {
        return ['editMode' => true] + parent::getProfile();
    }
    protected function decorate(array $prop, string $type) {
        $value = $prop['value'];
        unset($prop['value'], $prop['title']);
        $data = htmlspecialchars(json_encode(array_filter($prop)));
        return "<span class=\"editable $type\" data-prop=\"$data\">$value</span>";
    }
}
