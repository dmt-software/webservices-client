<?php

namespace DMT\WebservicesNl\Client\Exception\Client;

use DMT\WebservicesNl\Client\Exception\ClientException;

/**
 * Class AuthenticationException
 *
 * @package DMT\WebservicesNl\Client
 */
class AuthenticationException extends ClientException
{
    const MESSAGE = 'Authentication of the client has failed, the client is not logged in.';
}
