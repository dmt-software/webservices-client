<?php

namespace DMT\WebservicesNl\Client\Exception\Server\Data;

use DMT\WebservicesNl\Client\Exception\Server\DataException;

/**
 * Class PageNotFoundException
 *
 * @package DMT\WebservicesNl\Client
 */
class PageNotFoundException extends DataException
{
    const MESSAGE = 'The requested result page doesn\'t exist.';
}
