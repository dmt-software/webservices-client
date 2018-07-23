<?php

namespace DMT\WebservicesNl\Client\Exception\Server;

use DMT\WebservicesNl\Client\Exception\ServerException;

/**
 * Class DataException
 *
 * @package DMT\WebservicesNl\Client
 */
class DataException extends ServerException
{
    const MESSAGE = 'An error occurred while retrieving requested data.';
}
