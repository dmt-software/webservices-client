<?php

namespace DMT\WebservicesNl\Client\Exception\Server\Unavailable;

use DMT\WebservicesNl\Client\Exception\Server\UnavailableException;

class TemporaryException extends UnavailableException
{
    const MESSAGE = 'The service is unavailable due to a temporary technical problem.';
}
