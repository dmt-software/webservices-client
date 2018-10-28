<?php

namespace DMT\Test\WebservicesNl\Client\Factory;

use DMT\Test\WebservicesNl\Client\ObjectAssertTrait;
use DMT\WebservicesNl\Client\Factory\AbstractClientBuilder;
use DMT\WebservicesNl\Client\Factory\HttpRpcClientBuilder;
use DMT\WebservicesNl\Client\Serializer\EventSubscriber\UserCredentialsEventSubscriber;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;


class HttpRpcClientBuilderTest extends AbstractClientBuilderTest
{
    use ObjectAssertTrait;

    /**
     * @dataProvider provideCredentials
     *
     * @param array $credentials
     * @param EventSubscriberInterface $eventSubscriber
     */
    public function testCredentials(array $credentials, EventSubscriberInterface $eventSubscriber)
    {
        $builder = $this->getBuilder()->setAuthentication($credentials);

        static::assertObjectPropertyEquals($eventSubscriber, 'authentication', $builder);
    }

    /**
     * @return array
     */
    public function provideCredentials(): array
    {
        $login = ['username' => 'login_name', 'password' => 'secret'];

        return [
            [$login, new UserCredentialsEventSubscriber(...array_values($login))],
        ];
    }

    /**
     * @return HttpRpcClientBuilder
     */
    protected function getBuilder(): AbstractClientBuilder
    {
        return HttpRpcClientBuilder::create('get-simplexml');
    }
}
