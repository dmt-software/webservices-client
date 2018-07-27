<?php

namespace DMT\Test\WebservicesNl\Client;

use DMT\WebservicesNl\Client\ClientHandler;
use DMT\WebservicesNl\Client\Request\LoginRequest;
use DMT\WebservicesNl\Client\Request\LogoutRequest;
use DMT\WebservicesNl\Client\Response\LoginResponse;
use Doctrine\Common\Annotations\AnnotationRegistry;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use JMS\Serializer\JsonDeserializationVisitor;
use JMS\Serializer\JsonSerializationVisitor;
use JMS\Serializer\Naming\CamelCaseNamingStrategy;
use JMS\Serializer\Naming\SerializedNameAnnotationStrategy;
use JMS\Serializer\SerializerBuilder;
use PHPUnit\Framework\TestCase;

/**
 * Class ClientHandlerTest
 *
 * @package DMT\WebservicesNl\Client
 */
class ClientHandlerTest extends TestCase
{
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        AnnotationRegistry::registerUniqueLoader('class_exists');
    }

    public function testLogin()
    {
        $request = new LoginRequest();
        $request->setUsername('admin');
        $request->setPassword('-secret-');

        $handler = $this->getClientHandler(json_encode(['reactid' => 'F43FBB9CC9FEC27B7B7F2B13957645C6']), 'get-json');

        static::assertInstanceOf(LoginResponse::class, $response = $handler->login($request));
        static::assertSame('F43FBB9CC9FEC27B7B7F2B13957645C6', $response->getSessionId());
    }

    public function testLogout()
    {
        static::assertNull($this->getClientHandler('', 'post-json')->logout(new LogoutRequest()));
    }

    /**
     * Get the ClientHandler with mocked response data.
     *
     * @param string $responseData The mocked response data
     * @param string $serializationFormat The serialization format for (de)serialization
     *
     * @return ClientHandler
     */
    protected function getClientHandler(string $responseData, string $serializationFormat): ClientHandler
    {
        $httpClient = new Client([
            'handler' => HandlerStack::create(
                new MockHandler([
                    new Response(200, [], $responseData)
                ])
            )
        ]);

        $namingStrategy = new SerializedNameAnnotationStrategy(new CamelCaseNamingStrategy());
        $serializer = SerializerBuilder::create()
            ->setSerializationVisitor($serializationFormat, new JsonSerializationVisitor($namingStrategy))
            ->setDeserializationVisitor($serializationFormat, new JsonDeserializationVisitor($namingStrategy))
            ->build();

        return new ClientHandler($httpClient, $serializer, $serializationFormat);
    }
}
