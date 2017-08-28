<?php

namespace Incertitude\SWLRP\Views;

use Incertitude\SWLRP\LayoutView;
use Incertitude\SWLRP\Application;
use Incertitude\SWLRP\Exceptions\ProfileNotFound;

abstract class Profile extends LayoutView {
    const PROP_DEFAULTS = [
        'name' => '',
        'autocomplete' => false,
        'constraint' => null,
    ];
    const EDIT_MODE_DISABLED = 'disabled';
    const EDIT_MODE_REQUESTED = 'requested';
    const EDIT_MODE_ENABLED = 'enabled';
    /** @var array */
    private $profile;
    /** @var string */
    private $editMode = self::EDIT_MODE_DISABLED;
    /** @var int */
    private $requestedId;
    public function __construct(array $data, Application $application) {
        parent::__construct($data, $application);
        $this->requestedId = $this->getIntData(0, $application->getSession()->getCharacterId());
    }
    public function getRequestString(): string {
        $id = $this->requestedId;
        if (!empty($id)) {
            return $id . '?' . http_build_query($this->getNameData());
        }
        return '';
    }
    public function setEditMode(string $editMode): self {
        $this->editMode = $editMode;
        return $this;
    }
    protected function getEditMode(): string {
        return $this->editMode;
    }
    protected function getTitle(): string {
        return $this->getProfile()['name'];
    }
    protected function getContent(): string {
        return $this->renderTemplate('profile', $this->getProfile());
    }
    protected function getDialogs(): string {
        return $this->renderTemplate('dialogs/profile');
    }
    protected function getRequestedId(): int {
        return $this->requestedId;
    }
    protected function getProfile(): array {
        if (!isset($this->profile)) {
            $this->profile = [];
            $data = $this->getModel()->load($this->getRequestedId());
            if (!empty($data)) {
                $this->loadProfileData($data);
            }
        }
        if (empty($this->profile)) {
            throw new ProfileNotFound();
        }
        return $this->profile;
    }
    private function loadProfileData(array $data) {
        $this->profile['nick'] = htmlspecialchars($data['nick']);
        $this->profile['name'] = htmlspecialchars($data['name']);
        $this->profile['portrait'] = htmlspecialchars($data['portrait']);
        foreach($this->iterateMetaData($this->profile['structure']) as $key => &$prop) {
            $prop += self::PROP_DEFAULTS;
            $value = $data['properties'][$prop['name']] ?? '';
            if ('properties' === $key) {
                $value = htmlspecialchars($value);
            }
            $prop['value'] = $value;
        }
    }
    protected function decorate(array $prop, string $type) {
        return $prop['value'];
    }
}
