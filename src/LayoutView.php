<?php

namespace Incertitude\SWLRP;

abstract class LayoutView extends View {
    public function render(): string {
        return $this->renderTemplate('layout', [
            'title' => $this->getTitle(),
            'content' => $this->getContent()
        ]);
    }
    abstract protected function getTitle(): string;
    abstract protected function getContent(): string;
}
