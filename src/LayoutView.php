<?php

namespace Incertitude\SWLRP;

use Incertitude\SWLRP\Application;

abstract class LayoutView extends View {
    /** @var string */
    private $mceApiKey;
    public function __construct(array $data, Application $application) {
        parent::__construct($data, $application);
        $this->mceApiKey = $application->getConfig()->get('TinyMCE', 'apiKey');
    }
    public function render(): string {
        return $this->renderTemplate('layout', [
            'title' => $this->getTitle(),
            'content' => $this->getContent(),
            'mceApiKey' => $this->mceApiKey,
        ]);
    }
    abstract protected function getTitle(): string;
    abstract protected function getContent(): string;
}
