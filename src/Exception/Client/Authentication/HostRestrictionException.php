<?php

namespace DMT\WebservicesNl\Client\Exception\Client\Authentication;

use DMT\WebservicesNl\Client\Exception\Client\AuthorizationException;

/**
 * Class HostRestrictionException
 *
 * @package DMT\WebservicesNl\Client
 */
class HostRestrictionException extends AuthorizationException
{
    const MESSAGE = 'Authentication failed due to restrictions on hosts and/or ip addresses.';
}
