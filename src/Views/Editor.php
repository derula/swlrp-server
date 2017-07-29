<?php

namespace Incertitude\SWLRP\Views;

use Incertitude\SWLRP\Application;
use Incertitude\SWLRP\Forwardable;

class Editor extends Profile implements Forwardable {
    public function __construct(array $data, Application $application) {
        parent::__construct($data, $application);
        $this->setRequestedId($application->getSession()->getCharacterId());
    }
    public function getRequestString(): string {
        $id = $this->getData(0);
        if (!empty($id)) {
            return $id . '?' . http_build_query($this->getNameData());
        }
        return '';
    }
    protected function getProfile(): array {
        return ['editMode' => true] + parent::getProfile();
    }
    protected function decorate(array $prop, string $type) {
        $value = $prop['value'];
        unset($prop['value'], $prop['title']);
        $data = htmlspecialchars(json_encode(array_filter($prop)));
        return "<div class=\"editable $type\" data-prop=\"$data\">$value</div>";
    }
}
