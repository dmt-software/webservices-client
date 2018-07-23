<?php

namespace DMT\WebservicesNl\Client\Exception\Server\Data;

use DMT\WebservicesNl\Client\Exception\Server\DataException;

class NotFoundException extends DataException
{
    const MESSAGE = 'The requested data isn\'t available (for example, the requested address does not exist).';
}
