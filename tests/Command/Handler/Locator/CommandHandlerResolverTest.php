<?php

namespace DMT\Test\WebservicesNl\Client\Command\Handler\Locator;

use DMT\WebservicesNl\Client\ClientHandler;
use DMT\WebservicesNl\Client\Command\Handler\Locator\CommandHandlerResolver;
use DMT\WebservicesNl\Client\Exception\UnknownRequestException;
use DMT\WebservicesNl\Client\Request\LoginRequest;
use GuzzleHttp\Client;
use JMS\Serializer\SerializerBuilder;
use PHPUnit\Framework\TestCase;

class CommandHandlerResolverTest extends TestCase
{
    /**
     * @var CommandHandlerResolver
     */
    protected static $resolver;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        static::$resolver = new CommandHandlerResolver(
            new Client(),
            SerializerBuilder::create()->build(),
            ''
        );
    }

    public function testResolveRequest()
    {
        static::assertInstanceOf(ClientHandler::class, call_user_func(static::$resolver, LoginRequest::class));
    }

    /**
     * @dataProvider provideIllegalCommand
     * @param string $command
     */
    public function testResolveUnknownRequest(string $command)
    {
        $this->expectException(UnknownRequestException::class);
        $this->expectExceptionMessage(sprintf('Could not process %s', $command));

        call_user_func(static::$resolver, $command);
    }

    public function provideIllegalCommand(): array
    {
        return [
            [\stdClass::class],
            ['DMT\\WebservicesNl\\Unknown_request_class']
        ];
    }
}
