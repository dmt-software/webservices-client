<?php

namespace DMT\WebservicesNl\Client\Factory;

use DMT\WebservicesNl\Client\Client;
use Doctrine\Common\Annotations\AnnotationException;

/**
 * Class ClientFactory
 *
 * @package DMT\WebservicesNl\Client
 */
class ClientFactory
{
    /**
     * @static array
     */
    const ENDPOINTS = [
        'soap' => 'https://ws1.webservices.nl/soap/',
        'soap_doclit' => 'https://ws1.webservices.nl/soap_doclit/',
    ];

    /**
     * @param string $type
     * @param array $credentials
     *
     * @return Client
     * @throws AnnotationException
     * @throws \InvalidArgumentException
     */
    public static function createClient(string $type, array $credentials)
    {
        switch ($type) {
            case 'soap': // fall through
            case 'soap_doclit':
                $builder = SoapClientBuilder::create();
                break;

            default:
                throw new \InvalidArgumentException('Unsupported client type');
        }

        return $builder
            ->setAuthentication($credentials)
            ->setServiceEndpoint(static::ENDPOINTS[$type])
            ->build();
    }
}
