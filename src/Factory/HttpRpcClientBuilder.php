<?php

namespace DMT\WebservicesNl\Client\Factory;

use DMT\WebservicesNl\Client\Command\Handler\Locator\CommandHandlerResolver;
use DMT\WebservicesNl\Client\Serializer\EventSubscriber\RequestMethodEventSubscriber;
use DMT\WebservicesNl\Client\Serializer\EventSubscriber\UserCredentialsEventSubscriber;
use DMT\WebservicesNl\Client\Serializer\Handler\GenericDateHandler;
use DMT\WebservicesNl\Client\Serializer\HttpGetSerializationVisitor;
use GuzzleHttp\Client as HttpClient;
use JMS\Serializer\EventDispatcher\EventDispatcher;
use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\XmlDeserializationVisitor;
use Metadata\Cache\FileCache;

/**
 * Class HttpRpcClientBuilder
 *
 * @package DMT\WebservicesNl\Client
 */
class HttpRpcClientBuilder extends AbstractClientBuilder
{
    /**
     * @var UserCredentialsEventSubscriber
     */
    protected $authentication;

    /**
     * Set the authentication.
     *
     * @param array $credentials
     *
     * @return AbstractClientBuilder
     * @throws \InvalidArgumentException
     */
    public function setAuthentication(array $credentials): AbstractClientBuilder
    {
        if (!array_key_exists('username', $credentials) || !array_key_exists('password', $credentials)) {
            throw new \InvalidArgumentException('No credentials given.');
        }

        $this->authentication = new UserCredentialsEventSubscriber($credentials['username'], $credentials['password']);

        return $this;
    }

    /**
     * Get a configured command resolver for the requested endpoint.
     *
     * @return CommandHandlerResolver
     */
    protected function getCommandResolver(): CommandHandlerResolver
    {
        $httpClient = new HttpClient(
            [
                'base_uri' => $this->endpoint,
                'http_errors' => false,
                'headers' => [
                    'Content-Type' => 'text/xml; charset=utf-8',
                ]
            ]
        );

        $serializer = SerializerBuilder::create()
            ->configureListeners(
                function (EventDispatcher $dispatcher) {
                    $dispatcher->addSubscriber($this->authentication);
                    $dispatcher->addSubscriber(new RequestMethodEventSubscriber());
                }
            )
            ->configureHandlers(
                function (HandlerRegistry $registry) {
                    $registry->registerSubscribingHandler(new GenericDateHandler());
                }
            )
            ->setSerializationVisitor('get', new HttpGetSerializationVisitor($this->namingStrategy))
            ->setDeserializationVisitor('simplexml', new XmlDeserializationVisitor($this->namingStrategy))
            ->setMetadataCache(new FileCache(dirname(__DIR__, 2) . '/cache/http/'))
            ->build();

        return new CommandHandlerResolver($httpClient, $serializer, $this->serializerFormat);
    }
}
