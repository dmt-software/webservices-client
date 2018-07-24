<?php

namespace DMT\Test\WebservicesNl\Client\Factory;

use DMT\WebservicesNl\Client\Factory\AbstractClientBuilder;
use PHPUnit\Framework\TestCase;

/**
 * Class AbstractClientBuilderTest
 *
 * @package DMT\WebservicesNl\Client
 */
abstract class AbstractClientBuilderTest extends TestCase
{
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
