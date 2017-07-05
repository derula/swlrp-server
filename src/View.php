<?php

namespace Incertitude\SWLRP;

abstract class View extends IOComponent {
    /** @var array */
    private $tmp;
    public function render(): string {
        return $this->renderTemplate('layout', [
            'title' => $this->getTitle(),
            'content' => $this->getContent()
        ]);
    }
    abstract protected function getTitle(): string;
    abstract protected function getContent(): string;
    protected function renderTemplate(string $name, array $tplVars=[]): string {
        $this->tmp = [$name, $tplVars];
        unset($name, $tplVars);
        extract($this->tmp[1]);
        ob_start();
        try {
            include dirname(__DIR__) . "/templates/{$this->tmp[0]}.php";
        } finally {
            $result = ob_get_contents();
            ob_end_clean();
            return $result;
        }
    }
}
