<?php

namespace DMT\WebservicesNl\Client\Exception;

/**
 * Class ServerException
 *
 * @package DMT\WebservicesNl\Client
 */
class ServerException extends \RuntimeException implements ExceptionInterface
{
    /**
     * @static string The default message for this exception
     */
    const MESSAGE = 'General error, caused by the server.';

    /**
     * ServerException constructor.
     *
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(string $message = "", int $code = 0, \Throwable $previous = null)
    {
        parent::__construct(strlen($message) ? $message : static::MESSAGE, $code, $previous);
    }
}
