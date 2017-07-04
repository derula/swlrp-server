<?php

namespace Incertitude\SWLRP\Views;
use Incertitude\SWLRP\View;

class Error extends View {
    /** @var int */
    private $code = null;
    public function setCode(int $code): self {
        http_response_code($code);
        $this->code = $code;
        return $this;
    }
    protected function getTitle(): string {
        return 'Error';
    }
    protected function getContent(): string {
        return $this->renderTemplate('error' . $this->code);
    }
}
