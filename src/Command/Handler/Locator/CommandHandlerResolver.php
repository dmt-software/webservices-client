<?php

namespace DMT\WebservicesNl\Client\Command\Handler\Locator;

use DMT\WebservicesNl\Client\ClientHandler;
use DMT\WebservicesNl\Client\Exception\Server\UnavailableException;
use DMT\WebservicesNl\Client\Exception\UnknownRequestException;
use GuzzleHttp\Client;
use JMS\Serializer\Serializer;

/**
 * Class CommandHandlerResolver
 *
 * @package DMT\WebservicesNl\Client
 */
class CommandHandlerResolver
{
    /**
     * @var Client
     */
    protected $httpClient;

    /**
     * @var Serializer
     */
    protected $serializer;

    /**
     * @var string
     */
    protected $format;

    /**
     * CommandHandlerResolver constructor.
     *
     * @param Client $httpClient
     * @param Serializer $serializer
     * @param string $format
     */
    public function __construct(Client $httpClient, Serializer $serializer, string $format)
    {
        $this->httpClient = $httpClient;
        $this->serializer = $serializer;
        $this->format = $format;
    }

    public function __invoke(string $command): ClientHandler
    {
        $handlerClass = null;
        if (strpos($command, 'DMT\\WebservicesNl\\') === 0) {
            $handlerClass = preg_replace(
                '~^(DMT\\\WebservicesNl\\\)([^\\\]+)(\\\.*)?(\\\.*)$~',
                "$1$2\\\\$2Handler",
                $command
            );
        }

        if (!$handlerClass || !class_exists($handlerClass)) {
            throw new UnknownRequestException('Could not process ' . $command);
        }

        return new $handlerClass($this->httpClient, $this->serializer, $this->format);
    }
}
