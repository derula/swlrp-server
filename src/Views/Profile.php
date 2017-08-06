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
    /** @var array */
    private $profile;
    /** @var int */
    private $requestedId;
    public function __construct(array $data, Application $application) {
        parent::__construct($data, $application);
        $this->requestedId = (int)$this->getData(0) ?: $application->getSession()->getCharacterId();
    }
    protected function getTitle(): string {
        return $this->getProfile()['name'];
    }
    protected function getContent(): string {
        return $this->renderTemplate('profile', $this->getProfile());
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
        $this->profile['editMode'] = false;
    }
    protected function decorate(array $prop, string $type) {
        return $prop['value'];
    }
}
