<?php

namespace Incertitu\SWLRP;

abstract class View extends IOComponent {
    abstract public function render(): string;
}
