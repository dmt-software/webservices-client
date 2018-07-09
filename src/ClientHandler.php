<?php

namespace DMT\WebservicesNl\Client;

use DMT\WebservicesNl\Client\Request\LoginRequest;
use DMT\WebservicesNl\Client\Request\LogoutRequest;
use DMT\WebservicesNl\Client\Request\RequestInterface;
use DMT\WebservicesNl\Client\Response\LoginResponse;
use DMT\WebservicesNl\Client\Response\ResponseInterface;
use GuzzleHttp\Client;
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
     * DutchBusinessHandler constructor.
     *
     * @param Client $httpClient
     * @param Serializer $serializer
     * @param string $format
     */
    public function __construct(Client $httpClient, Serializer $serializer, string $format)
    {
        $this->httpClient = $httpClient;
        $this->serializer = $serializer;
        $this->serializerFormat = $format;
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
     */
    public function logout(LogoutRequest $request): void
    {
        $this->process($request, null);
    }

    /**
     * @param RequestInterface $request
     * @param string $responseClassName
     *
     * @return ResponseInterface
     */
    public function process(RequestInterface $request, ?string $responseClassName): ?ResponseInterface
    {
        $method = stripos($this->serializerFormat, 'get') === 0 ? 'GET' : 'POST';
        $request = $this->serializer->serialize($request, $this->serializerFormat);

        if ($method === 'POST') {
            $response = $this->httpClient->post('', ['body' => $request]);
        } else {
            $response = $this->httpClient->get($request);
        }

        if ($responseClassName) {
            return $this->serializer->deserialize($response->getBody(), $responseClassName, $this->serializerFormat);
        }
    }
}
