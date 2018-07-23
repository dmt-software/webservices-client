<?php

namespace DMT\WebservicesNl\Client\Exception\Server\Unavailable;

use DMT\WebservicesNl\Client\Exception\Server\UnavailableException;

class InternalErrorException extends UnavailableException
{
    const MESSAGE = 'The service is unavailable due to an internal server error.';
}
