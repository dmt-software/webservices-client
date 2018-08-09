<?php

namespace DMT\Test\WebservicesNl\Client;

use DMT\WebservicesNl\Client\Client;
use DMT\WebservicesNl\Client\ClientHandler;
use DMT\WebservicesNl\Client\Command\Handler\MethodNameInflector\ClassNameWithoutSuffixInflector;
use DMT\WebservicesNl\Client\Request\LoginRequest;
use DMT\WebservicesNl\Client\Response\LoginResponse;
use DMT\WebservicesNl\Client\Response\ResponseInterface;
use League\Tactician\CommandBus;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\Locator\CallableLocator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class ClientTest
 *
 * @package DMT\WebservicesNl\Client
 */
class ClientTest extends TestCase
{
    use LoadCredentialsTrait;

    /**
     * @dataProvider provideIncorrectRequests
     *
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessageRegExp ~Function `.+` is not a valid method for this service~
     *
     * @param string $method
     *
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    public function testUnknownRequest(string $method)
    {
        $client = $this->getMockedClient('logout');
        $client->{$method}();
    }

    public function provideIncorrectRequests(): array
    {
        return [
            ['clientGetCLient'],
            ['testMockUnknownmethodCall']
        ];
    }

    /**
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    public function testLoginRequest()
    {
        $response = $this->getLoginResponse('C316ACE0D31D1917A2E0C47D0829B953');
        $client = $this->getMockedClient('login', $response);

        $request = new LoginRequest();
        $request->setUsername('user');
        $request->setPassword('secret123');

        static::assertSame($response, $client->execute($request));
    }

    /**
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    public function testLoginMethod()
    {
        $response = $this->getLoginResponse('5530B5FE3EB488609985F75689513C0F');
        $client = $this->getMockedClient('login', $response);

        static::assertEquals(
            (object) ['reactid' => '5530B5FE3EB488609985F75689513C0F'],
            $client->login(['username' => 'user', 'password' => 'secret123'])
        );
    }

    protected function getLoginResponse(string $sessionId): LoginResponse
    {
        $response = new LoginResponse();
        $response->setSessionId($sessionId);

        return $response;
    }

    /**
     * @param string $method
     * @param $response
     *
     * @return Client
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    protected function getMockedClient(string $method, ResponseInterface $response = null): Client
    {
        /** @var MockObject|ClientHandler $handler */
        $handler = static::createMock(ClientHandler::class);
        $handler->expects(static::any())->method($method)->willReturn($response);

        return new Client(
            new CommandBus(
                [
                    new CommandHandlerMiddleware(
                        new ClassNameExtractor(),
                        new CallableLocator(
                            function () use ($handler) {
                                return $handler;
                            }
                        ),
                        new ClassNameWithoutSuffixInflector('Request')
                    )
                ]
            )
        );
    }
}
