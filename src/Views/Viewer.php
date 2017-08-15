<?php

namespace Incertitude\SWLRP\Views;

use Incertitude\SWLRP\Exceptions\ProfileNotFound;

class Viewer extends Profile {
    protected function getTitle(): string {
        try {
            return parent::getTitle();
        } catch (ProfileNotFound $ex) {
            return $this->getFallbackName($ex) . ' isn\'t using the SWLRP Roleplay Profile Add-On!';
        }
    }
    protected function getContent(): string {
        try {
            return parent::getContent();
        } catch (ProfileNotFound $ex) {
            $url = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . '/';
            return $this->renderTemplate('noprofile', [
                'name' => $this->getFallbackName($ex),
                'serverUrl' => htmlspecialchars($url),
            ]);
        }
    }
    private function getFallbackName(\Exception $ex): string {
        $name = $this->getData('nick');
        if (empty($name)) {
            throw $ex;
        }
        return htmlspecialchars($name);
    }
}
