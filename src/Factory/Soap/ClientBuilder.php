<?php

namespace DMT\WebservicesNl\Client\Factory\Soap;

use DMT\Soap\Serializer\SoapDeserializationVisitor;
use DMT\Soap\Serializer\SoapHeaderEventSubscriber;
use DMT\Soap\Serializer\SoapHeaderInterface;
use DMT\Soap\Serializer\SoapSerializationVisitor;
use DMT\WebservicesNl\Client\Command\Handler\Locator\CommandHandlerResolver;
use DMT\WebservicesNl\Client\Factory\AbstractClientBuilder;
use DMT\WebservicesNl\Client\Soap\Authorization\HeaderAuthenticate;
use DMT\WebservicesNl\Client\Soap\Authorization\HeaderLogin;
use GuzzleHttp\Client as HttpClient;
use JMS\Serializer\EventDispatcher\EventDispatcher;
use JMS\Serializer\SerializerBuilder;
use Metadata\Cache\FileCache;

/**
 * Class ClientBuilder
 *
 * @package DMT\WebservicesNl\Client
 */
class ClientBuilder extends AbstractClientBuilder
{
    /**
     * @var SoapHeaderInterface
     */
    protected $authorization;

    /**
     * @var string
     */
    protected $endpoint = 'https://ws1.webservices.nl/soap_doclit/';

    /**
     * @var string
     */
    protected $serializerFormat = 'soap';

    /**
     * @param array $credentials
     *
     * @return ClientBuilder
     */
    public function setAuthentication(array $credentials): AbstractClientBuilder
    {
        if (array_key_exists('session_id', $credentials)) {
            $this->authorization = new HeaderAuthenticate($credentials['session_id']);
        } elseif (array_key_exists('username', $credentials) && array_key_exists('password', $credentials)) {
            $this->authorization = new HeaderLogin($credentials['username'], $credentials['password']);
        }

        return $this;
    }

    /**
     * @todo Add Request middleware to add SOAPAction
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
                    //'SOAPAction' => ''
                ]
            ]
        );

        $serializer = SerializerBuilder::create()
            ->configureListeners(
                function (EventDispatcher $dispatcher) {
                    $dispatcher->addSubscriber(new SoapHeaderEventSubscriber($this->authorization));
                }
            )
            ->setSerializationVisitor('soap', new SoapSerializationVisitor($this->namingStrategy))
            ->setDeserializationVisitor('soap', new SoapDeserializationVisitor($this->namingStrategy))
            ->setMetadataCache(new FileCache(dirname(__DIR__, 3) . '/cache/soap/'))
            ->build();

        return new CommandHandlerResolver($httpClient, $serializer, 'soap');
    }
}
