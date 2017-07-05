<?php

namespace Incertitude\SWLRP\Views;

use Incertitude\SWLRP\LayoutView;
use Incertitude\SWLRP\Exceptions\ProfileNotFound;

abstract class Profile extends LayoutView {
    const PROP_DEFAULTS = [
        'name' => '',
        'autocomplete' => false,
        'constraint' => 'none',
    ];
    /** @var array */
    private $profile;
    protected function getTitle(): string {
        return $this->getProfile()['name'];
    }
    protected function getContent(): string {
        return $this->renderTemplate('profile', $this->getProfile());
    }
    protected function getRequestedName(): string {
        return ucwords($this->getData(0));
    }
    protected function getProfile(): array {
        if (!isset($this->profile)) {
            $this->profile = ['editMode' => false];
            $data = $this->getModel()->load($this->getRequestedName());
            if (!empty($data)) {
                $this->loadProfileData($data);
            }
        }
        if (empty($this->profile)) {
            throw new ProfileNotFound();
        }
        return $this->profile;
    }
    protected function loadProfileData(array $data) {
        $this->profile['name'] = $data['name'];
        $this->profile['structure'] = $this->getModel()->getMetadata();
        foreach ($this->profile['structure'] as &$profilePage) {
            foreach (['properties', 'texts'] as $key) {
                foreach ($profilePage[$key] as &$prop) {
                    $prop += self::PROP_DEFAULTS;
                    $prop += ['title' => ucwords($prop['name'])];
                    $prop['value'] = $data['properties'][$prop['name']] ?? '';
                }
            }
        }
    }
    protected function decorate(array $prop, string $type) {
        return $prop['value'];
    }
}
