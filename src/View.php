<?php

namespace Incertitude\SWLRP;

abstract class View extends IOComponent {
    public function render(): string {
        return $this->renderTemplate('layout', [
            'title' => $this->getTitle(),
            'content' => $this->getContent()
        ]);
    }
    abstract protected function getTitle(): string;
    abstract protected function getContent(): string;
    protected function renderTemplate(string $name, array $tplVars=[]): string {
        extract($tplVars);
        ob_start();
        try {
            include dirname(__DIR__) . "/templates/$name.php";
        } finally {
            $result = ob_get_contents();
            ob_end_clean();
            return $result;
        }
    }
}
