<?php

namespace DMT\Test\WebservicesNl\Client\Serializer\EventSubscriber;

use DMT\WebservicesNl\Client\Request\LoginRequest;
use DMT\WebservicesNl\Client\Request\RequestInterface;
use DMT\WebservicesNl\Client\Serializer\EventSubscriber\UserCredentialsEventSubscriber;
use DMT\WebservicesNl\Client\Serializer\HttpGetSerializationVisitor;
use DMT\WebservicesNl\TestMock\Request\DummyRequest;
use Doctrine\Common\Annotations\AnnotationRegistry;
use JMS\Serializer\EventDispatcher\EventDispatcher;
use JMS\Serializer\Naming\IdenticalPropertyNamingStrategy;
use JMS\Serializer\Naming\SerializedNameAnnotationStrategy;
use JMS\Serializer\SerializerBuilder;
use PHPUnit\Framework\TestCase;

class UserCredentialsEventSubscriberTest extends TestCase
{
    /**
     * @dataProvider provideRequest
     *
     * @param RequestInterface $request
     * @param string $expected
     */
    public function testAddCredentials(RequestInterface $request, string $expected)
    {
        AnnotationRegistry::registerUniqueLoader('class_exists');

        $serializer = SerializerBuilder::create()
            ->configureListeners(
                function (EventDispatcher $dispatcher) {
                    $dispatcher->addSubscriber(
                        new UserCredentialsEventSubscriber('user', 'pass')
                    );
                }
            )
            ->setSerializationVisitor(
                'get',
                new HttpGetSerializationVisitor(
                    new SerializedNameAnnotationStrategy(
                        new IdenticalPropertyNamingStrategy()
                    )
                )
            )
            ->build();

        static::assertStringStartsWith($expected, $serializer->serialize($request, 'get'));
    }

    /**
     * @return array
     */
    public function provideRequest(): array
    {
        $login = new LoginRequest();
        $login->setUsername('user');
        $login->setPassword('secret');

        return [
            [$login, 'user/secret'],
            [new DummyRequest(), 'user/pass']
        ];
    }
}
