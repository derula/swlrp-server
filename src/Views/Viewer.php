<?php

namespace Incertitude\SWLRP\Views;

use Incertitude\SWLRP\Application;
use Incertitude\SWLRP\Exceptions\ProfileNotFound;

class Viewer extends Profile {
    public function __construct(array $data, Application $application) {
        parent::__construct($data, $application);
        $this->setRequestedId($this->getData(0));
    }
    protected function getTitle(): string {
        try {
            return parent::getTitle();
        } catch (ProfileNotFound $ex) {
            return $this->getFallbackName($ex) . ' isn\'t using SWLRP!';
        }
    }
    protected function getContent(): string {
        try {
            return parent::getContent();
        } catch (ProfileNotFound $ex) {
            return $this->renderTemplate('noprofile', ['name' => $this->getFallbackName($ex)]);
        }
    }
    private function getFallbackName(\Exception $ex): string {
        $name = $this->getData('nick');
        if (empty($name)) {
            throw $ex;
        }
        return $name;
    }
}
