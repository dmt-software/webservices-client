<?php

namespace DMT\WebservicesNl\Client\Exception\Client;

use DMT\WebservicesNl\Client\Exception\ClientException;

/**
 * Class PaymentException
 *
 * @package DMT\WebservicesNl\Client
 */
class PaymentException extends ClientException
{
    const MESSAGE = 'The request can\'t be processed, because the user (or its account) '
        . 'doesn\'t have sufficient balance/credits.';
}
