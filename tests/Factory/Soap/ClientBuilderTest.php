<?php

namespace DMT\Test\WebservicesNl\Client\Factory\Soap;

use DMT\Soap\Serializer\SoapHeaderInterface;
use DMT\Test\WebservicesNl\Client\Factory\AbstractClientBuilderTest;
use DMT\Test\WebservicesNl\Client\ObjectAssertTrait;
use DMT\WebservicesNl\Client\Factory\AbstractClientBuilder;
use DMT\WebservicesNl\Client\Factory\Soap\ClientBuilder;
use DMT\WebservicesNl\Client\Soap\Authorization\HeaderAuthenticate;
use DMT\WebservicesNl\Client\Soap\Authorization\HeaderLogin;

/**
 * Class ClientBuilderTest
 *
 * @package DMT\WebservicesNl\Client
 */
class ClientBuilderTest extends AbstractClientBuilderTest
{
    use ObjectAssertTrait;

    /**
     * @dataProvider provideCredentials
     *
     * @param array $credentials
     * @param SoapHeaderInterface $header
     */
    public function testCredentials(array $credentials, SoapHeaderInterface $header)
    {
        $builder = ClientBuilder::create()->setAuthentication($credentials);

        static::assertObjectPropertyEquals($header, 'authentication', $builder);
    }

    /**
     * @return array
     */
    public function provideCredentials(): array
    {
        $login = ['username' => 'login_name', 'password' => 'secret'];
        $session = ['session_id' => '30C8D6F8CC2ABE90AD979437D3D955A3'];

        return [
            [$login, new HeaderLogin(...array_values($login))],
            [$session, new HeaderAuthenticate(...array_values($session))]
        ];
    }

    /**
     * @param string $endpoint
     */
    public function testServiceEndpoint($endpoint = 'https://ws.example.com/soap')
    {
        $builder = ClientBuilder::create()->setServiceEndpoint($endpoint);

        static::assertObjectPropertyEquals($endpoint, 'endpoint', $builder);
    }

    /**
     * @return AbstractClientBuilder
     */
    protected function getBuilder(): AbstractClientBuilder
    {
        return ClientBuilder::create();
    }
}
