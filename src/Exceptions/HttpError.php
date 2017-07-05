<?php

namespace Incertitude\SWLRP\Exceptions;

abstract class HttpError extends \Exception {
    const ERROR_CODE = 500;
    public function getStatusCode() {
        return static::ERROR_CODE;
    }
}
