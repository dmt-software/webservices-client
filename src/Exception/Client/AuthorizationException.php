<?php

namespace DMT\WebservicesNl\Client\Exception\Client;

use DMT\WebservicesNl\Client\Exception\ClientException;

/**
 * Class AuthorizationException
 *
 * @package DMT\WebservicesNl\Client
 */
class AuthorizationException extends ClientException
{
    const MESSAGE = 'The client has been authenticated, but isn\'t allowed to use the requested functionality.';
}
