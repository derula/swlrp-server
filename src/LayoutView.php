<?php

namespace Incertitude\SWLRP;

use Incertitude\SWLRP\Application;

abstract class LayoutView extends View {
    /** @var string */
    private $root, $mceApiKey;
    public function __construct(array $data, Application $application) {
        parent::__construct($data, $application);
        $this->root = $application->getRoot();
    }
    public function fileExists(string $fileName): bool {
        return file_exists($this->root . DIRECTORY_SEPARATOR . $fileName);
    }
    public function assetExists(string $fileName): bool {
        return $this->fileExists(join(DIRECTORY_SEPARATOR, ['public', 'assets', $fileName]));
    }
    public function render(): string {
        return $this->renderTemplate('layout', [
            'title' => $this->getTitle(),
            'content' => $this->getContent(),
            'dialogs' => $this->getDialogs(),
            'useCompatJs' => $this->assetExists('script.compat.js'),
        ]);
    }
    abstract protected function getTitle(): string;
    abstract protected function getContent(): string;
    protected function getDialogs(): string {
        return '';
    }
}
