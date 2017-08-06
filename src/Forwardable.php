<?php

namespace Incertitude\SWLRP;

interface Forwardable {
    public function isAccessible(int $characterId): bool;
    public function getRequestString(): string;
}
