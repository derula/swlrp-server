<?php

namespace Incertitude\SWLRP\Exceptions;

class IsLoggedIn extends RedirectError {
    const LOCATION = '/edit';
}
