<?php

namespace Incertitude\SWLRP\Views;

use Incertitude\SWLRP\Application;
use Incertitude\SWLRP\Forwardable;

class Editor extends Profile implements Forwardable {
    public function isAccessible(int $characterId): bool {
        return $characterId === $this->getRequestedId();
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
    protected function getDialogs(): string {
        return $this->renderTemplate('editorDialogs', ['name' => $this->getProfile()['nick']]);
    }
    protected function decorate(array $prop, string $type) {
        $value = $prop['value'];
        unset($prop['value'], $prop['title']);
        $data = htmlspecialchars(json_encode(array_filter($prop)));
        return "<div class=\"editable _$type\" data-prop=\"$data\">$value</div>";
    }
}
