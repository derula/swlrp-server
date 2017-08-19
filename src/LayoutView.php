<?php

namespace Incertitude\SWLRP;

use Incertitude\SWLRP\Application;

abstract class LayoutView extends View {
    /** @var string */
    private $root, $assetsPath;
    public function __construct(array $data, Application $application) {
        parent::__construct($data, $application);
        $this->root = $application->getRoot();
        $this->assetsPath = $assetPath = join(DIRECTORY_SEPARATOR, [$this->root, 'public', 'assets']);
    }
    public function assetUrl(string $fileName): string {
        $filePath = $this->assetsPath . DIRECTORY_SEPARATOR . $fileName;
        if ('script.js' === $fileName) {
            $compatName = 'script.compat.js';
            $compatPath = $this->assetsPath . DIRECTORY_SEPARATOR . $compatName;
            if (file_exists($compatPath)) {
                $fileName = $compatName;
                $filePath = $compatPath;
            }
        }
        $mtime = file_exists($filePath) ? filemtime($filePath) : 0;
        return '/assets/' . $fileName . '#' . $mtime;
    }
    public function render(): string {
        return $this->renderTemplate('layout', [
            'title' => $this->getTitle(),
            'content' => $this->getContent(),
            'dialogs' => $this->getDialogs(),
        ]);
    }
    abstract protected function getTitle(): string;
    abstract protected function getContent(): string;
    protected function getDialogs(): string {
        return '';
    }
}
