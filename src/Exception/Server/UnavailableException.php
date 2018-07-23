<?php

namespace DMT\WebservicesNl\Client\Exception\Server;

use DMT\WebservicesNl\Client\Exception\ServerException;

/**
 * Class UnavailableException
 *
 * @package DMT\WebservicesNl\Client
 */
class UnavailableException extends ServerException
{
    const MESSAGE = 'An error occurred that causes the service to be unavailable.';
}
