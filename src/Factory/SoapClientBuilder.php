<?php

namespace DMT\WebservicesNl\Client\Factory;

use DMT\Soap\Serializer\SoapDateHandler;
use DMT\Soap\Serializer\SoapDeserializationVisitor;
use DMT\Soap\Serializer\SoapHeaderEventSubscriber;
use DMT\Soap\Serializer\SoapHeaderInterface;
use DMT\Soap\Serializer\SoapSerializationVisitor;
use DMT\WebservicesNl\Client\Command\Handler\Locator\CommandHandlerResolver;
use DMT\WebservicesNl\Client\Soap\Authorization\HeaderAuthenticate;
use DMT\WebservicesNl\Client\Soap\Authorization\HeaderLogin;
use GuzzleHttp\Client as HttpClient;
use JMS\Serializer\EventDispatcher\EventDispatcher;
use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\SerializerBuilder;
use Metadata\Cache\FileCache;

/**
 * Class ClientBuilder
 *
 * @package DMT\WebservicesNl\Client
 */
class SoapClientBuilder extends AbstractClientBuilder
{
    /**
     * @var SoapHeaderInterface
     */
    protected $authentication;

    /**
     * @param array $credentials
     *
     * @return SoapClientBuilder
     * @throws \InvalidArgumentException
     */
    public function setAuthentication(array $credentials): AbstractClientBuilder
    {
        if (array_key_exists('session_id', $credentials)) {
            $this->authentication = new HeaderAuthenticate($credentials['session_id']);
        } elseif (array_key_exists('username', $credentials) && array_key_exists('password', $credentials)) {
            $this->authentication = new HeaderLogin($credentials['username'], $credentials['password']);
        } else {
            throw new \InvalidArgumentException('No credentials given.');
        }

        return $this;
    }

    /**
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
                    $dispatcher->addSubscriber(new SoapHeaderEventSubscriber($this->authentication));
                }
            )
            ->configureHandlers(
                function(HandlerRegistry $registry) {
                    $registry->registerSubscribingHandler(new SoapDateHandler());
                }
            )
            ->setSerializationVisitor('soap', new SoapSerializationVisitor($this->namingStrategy))
            ->setDeserializationVisitor('soap', new SoapDeserializationVisitor($this->namingStrategy))
            ->setMetadataCache(new FileCache(dirname(__DIR__, 2) . '/cache/soap/'))
            ->build();

        return new CommandHandlerResolver($httpClient, $serializer, $this->serializerFormat);
    }
}
