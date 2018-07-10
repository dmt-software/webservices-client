<?php

namespace DMT\WebservicesNl\Client\Factory;

use DMT\CommandBus\Validator\ValidationMiddleware;
use DMT\WebservicesNl\Client\Client;
use DMT\WebservicesNl\Client\Command\Handler\Locator\CommandHandlerResolver;
use DMT\WebservicesNl\Client\Command\Handler\MethodNameInflector\ClassNameWithoutSuffixInflector;
use DMT\WebservicesNl\Client\Command\Middleware\ExceptionMiddleware;
use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Annotations\AnnotationRegistry;
use JMS\Serializer\Naming\CamelCaseNamingStrategy;
use JMS\Serializer\Naming\PropertyNamingStrategyInterface;
use JMS\Serializer\Naming\SerializedNameAnnotationStrategy;
use League\Tactician\CommandBus;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\Locator\CallableLocator;
use League\Tactician\Plugins\LockingMiddleware;

/**
 * Class AbstractClientBuilder
 *
 * @package DMT\WebservicesNl\Client
 */
abstract class AbstractClientBuilder
{
    /**
     * @var string
     */
    protected $endpoint;

    /**
     * @var PropertyNamingStrategyInterface
     */
    protected $namingStrategy;

    /**
     * ClientBuilder constructor.
     */
    final public function __construct()
    {
        AnnotationRegistry::registerUniqueLoader('class_exists');

        $this->namingStrategy = new SerializedNameAnnotationStrategy(new CamelCaseNamingStrategy());
    }

    /**
     * @return AbstractClientBuilder
     */
    public static function create(): self
    {
        return new static();
    }

    /**
     * Build the webservices client.
     *
     * @return Client
     * @throws AnnotationException
     */
    public function build(): Client
    {
        return new Client(
            new CommandBus(
                [
                    new LockingMiddleware(),
                    new ExceptionMiddleware(),
                    new ValidationMiddleware(),
                    new CommandHandlerMiddleware(
                        new ClassNameExtractor(),
                        new CallableLocator($this->getCommandResolver()),
                        new ClassNameWithoutSuffixInflector('Request')
                    )
                ]
            )
        );
    }

    /**
     * Set endpoint for SOAP service.
     *
     * @param string $endpoint
     *
     * @return AbstractClientBuilder
     */
    public function setServiceEndpoint(string $endpoint): AbstractClientBuilder
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    /**
     * Set the authentication.
     *
     * @param array $credentials
     *
     * @return AbstractClientBuilder
     */
    abstract public function setAuthentication(array $credentials): AbstractClientBuilder;

    /**
     * Get a configured command resolver for the requested endpoint.
     *
     * @return CommandHandlerResolver
     */
    abstract protected function getCommandResolver(): CommandHandlerResolver;
}
