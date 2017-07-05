<?php

namespace Incertitude\SWLRP\Exceptions;

abstract class RedirectError extends HttpError {
    const ERROR_CODE = 303;
    const LOCATION = '/';
    public function getLocation() {
        return static::LOCATION;
    }
}
