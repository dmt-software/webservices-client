<?php

namespace DMT\WebservicesNl\Client\Exception\Client\Input;

use DMT\WebservicesNl\Client\Exception\Client\InputException;

class InvalidException extends InputException
{
    const MESSAGE = 'The input is invalid because one of the parameters contains an invalid or disallowed value.';
}
