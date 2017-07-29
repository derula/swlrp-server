<?php

namespace Incertitude\SWLRP\Exceptions;

abstract class RedirectError extends HttpError {
    const ERROR_CODE = 303;
    const LOCATION = '/';
    /** @var string */
    public $suffix;
    public function getLocation() {
        return rtrim(static::LOCATION, '/') . '/' . ltrim($this->suffix, '/');
    }
    public function setSuffix(string $suffix) {
        $this->suffix = $suffix;
    }
}
