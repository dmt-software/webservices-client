<?php

namespace DMT\Test\WebservicesNl\Client\Factory;

use DMT\Test\WebservicesNl\Client\ObjectAssertTrait;
use DMT\WebservicesNl\Client\Factory\AbstractClientBuilder;
use PHPUnit\Framework\TestCase;

/**
 * Class AbstractClientBuilderTest
 *
 * @package DMT\WebservicesNl\Client
 */
abstract class AbstractClientBuilderTest extends TestCase
{
    use ObjectAssertTrait;

    /**
     * @param string $endpoint
     */
    public function testServiceEndpoint($endpoint = 'https://ws.example.com/soap')
    {
        $builder = $this->getBuilder()->setServiceEndpoint($endpoint);

        static::assertObjectPropertyEquals($endpoint, 'endpoint', $builder);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidCredentials()
    {
        $this->getBuilder()->setAuthentication([]);
    }

    /**
     * @dataProvider provideInvalidServiceEndpoint
     * @expectedException \InvalidArgumentException
     *
     * @param string $endpoint
     */
    public function testInvalidServiceEndpoints(string $endpoint)
    {
        $this->getBuilder()->setServiceEndpoint($endpoint);
    }

    /**
     * @return array
     */
    public function provideInvalidServiceEndpoint(): array
    {
        return [
            ['localhost'],
            ['webservices.nl'],
            ['http://webservices.nl']
        ];
    }

    /**
     * @return AbstractClientBuilder
     */
    abstract protected function getBuilder(): AbstractClientBuilder;
}
