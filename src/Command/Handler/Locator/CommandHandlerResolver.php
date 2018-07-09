<?php

namespace DMT\WebservicesNl\Client\Command\Handler\Locator;

use DMT\WebservicesNl\Client\ClientHandler;
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
        $handlerClass = preg_replace(
            '~^(DMT\\\WebservicesNl\\\)([^\\\]+)(\\\.*)?(\\\.*)$~',
            "$1$2\\\\$2Handler",
            $command
        );

        if (!class_exists($handlerClass)) {
            die($handlerClass);
        }

        return new $handlerClass($this->httpClient, $this->serializer, $this->format);
    }
}
