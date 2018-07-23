<?php

namespace DMT\WebservicesNl\Client\Exception\Client;

use DMT\WebservicesNl\Client\Exception\ClientException;

/**
 * Class InputException
 *
 * @package DMT\WebservicesNl\Client
 */
class InputException extends ClientException
{
    const MESSAGE = 'An error occurred due to a problem with the client\'s input.';
}
