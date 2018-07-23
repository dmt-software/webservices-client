<?php

namespace DMT\WebservicesNl\Client\Exception\Client\Input;

use DMT\WebservicesNl\Client\Exception\Client\InputException;

/**
 * Class FormatIncorrectException
 *
 * @package DMT\WebservicesNl\Client
 */
class FormatIncorrectException extends InputException
{
    const MESSAGE = 'The input is invalid because one of the parameters contains '
        . 'a syntax error or is in an incorrect format.';
}
