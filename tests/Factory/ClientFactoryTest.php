<?php

namespace DMT\Test\WebservicesNl\Client\Factory;

use DMT\WebservicesNl\Client\Factory\ClientFactory;
use PHPUnit\Framework\TestCase;

/**
 * Class ClientFactoryTest
 *
 * @package DMT\WebservicesNl\Client
 */
class ClientFactoryTest extends TestCase
{
    /**
     * @dataProvider provideClientType
     *
     * @param string $type
     *
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    public function testCreateClient(string $type)
    {
        $client = ClientFactory::createClient($type, ['username' => 'user', 'password' => '30C8D6F8CC2ABE90AD9794A3']);

        static::assertObjectHasAttribute('commandBus', $client);
    }

    /**
     * @return \Generator
     */
    public function provideClientType(): \Generator
    {
        foreach (array_keys(ClientFactory::ENDPOINTS) as $type) {
            yield [$type];
        }
    }

    /**
     * @expectedException \InvalidArgumentException
     *
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    public function testCreateClientMissingCredentials()
    {
        ClientFactory::createClient('soap', []);
    }

    /**
     * @expectedException \InvalidArgumentException
     *
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    public function testUnsupportedClientType()
    {
        ClientFactory::createClient('unknown-type', ['session_id' => '30C8D6F8CC2ABE90AD979437D3D955A3']);
    }
}
