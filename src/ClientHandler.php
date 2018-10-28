<?php

namespace DMT\WebservicesNl\Client;

use DMT\WebservicesNl\Client\Request\LoginRequest;
use DMT\WebservicesNl\Client\Request\LogoutRequest;
use DMT\WebservicesNl\Client\Request\RequestInterface;
use DMT\WebservicesNl\Client\Response\LoginResponse;
use DMT\WebservicesNl\Client\Response\ResponseInterface;
use GuzzleHttp\Client;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;

/**
 * Class AbstractHandler
 *
 * @package DMT\WebservicesNl\Client
 */
class ClientHandler
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
    protected $serializerFormat;

    /**
     * @var string
     */
    protected $deserializerFormat;

    /**
     * DutchBusinessHandler constructor.
     *
     * @param Client $httpClient
     * @param Serializer $serializer
     * @param string $serializerFormat
     * @param string|null $deserializerFormat
     */
    public function __construct(
        Client $httpClient,
        Serializer $serializer,
        string $serializerFormat,
        string $deserializerFormat = null
    ) {
        $this->httpClient = $httpClient;
        $this->serializer = $serializer;
        $this->serializerFormat = $serializerFormat;
        $this->deserializerFormat = $deserializerFormat ?? $serializerFormat;
    }

    /**
     * @param LoginRequest|RequestInterface $request
     *
     * @return LoginResponse|ResponseInterface
     */
    public function login(LoginRequest $request): LoginResponse
    {
        return $this->process($request, LoginResponse::class);
    }

    /**
     * @param LogoutRequest|RequestInterface $request
     *
     * @return null
     */
    public function logout(LogoutRequest $request)
    {
        return $this->process($request);
    }

    /**
     * @param RequestInterface $request
     * @param string $responseClassName
     *
     * @return ResponseInterface
     */
    protected function process(RequestInterface $request, string $responseClassName = null): ?ResponseInterface
    {
        $context = SerializationContext::create();
        if ($this->serializerFormat === 'get') {
            $context->setSerializeNull(true);
        }

        $request = $this->serializer->serialize($request, $this->serializerFormat, $context);

        if ($this->serializerFormat === 'get') {
            $response = $this->httpClient->get($request);
        } else {
            $response = $this->httpClient->post('', ['body' => $request]);
        }

        if (!$responseClassName) {
            return null;
        }

        return $this->serializer->deserialize($response->getBody(), $responseClassName, $this->deserializerFormat);
    }
}
