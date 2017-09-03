<?php

namespace Incertitude\SWLRP;

interface Forwardable {
    public function isAccessible(): bool;
    public function forward();
}
