<?php

namespace Incertitude\SWLRP;

abstract class View extends IOComponent {
    abstract public function render(): string;
}
