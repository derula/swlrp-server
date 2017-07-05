<?php

namespace Incertitude\SWLRP\Exceptions;

class NotLoggedIn extends RedirectError {
    const LOCATION = '/front';
}
