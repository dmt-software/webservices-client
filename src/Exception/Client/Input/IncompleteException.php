<?php

namespace DMT\WebservicesNl\Client\Exception\Client\Input;

use DMT\WebservicesNl\Client\Exception\Client\InputException;

class IncompleteException extends InputException
{
    const MESSAGE = 'The input is invalid because one of the required parameters is missing or is incomplete.';
}
