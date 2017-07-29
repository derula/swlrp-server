<?php

namespace Incertitude\SWLRP;

interface Forwardable {
    public function getRequestString(): string;
}
