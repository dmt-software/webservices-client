<?php

namespace DMT\WebservicesNl\Client\Factory;

use DMT\CommandBus\Validator\ValidationMiddleware;
use DMT\WebservicesNl\Client\Client;
use DMT\WebservicesNl\Client\Command\Handler\Locator\CommandHandlerResolver;
use DMT\WebservicesNl\Client\Command\Handler\MethodNameInflector\ClassNameWithoutSuffixInflector;
use DMT\WebservicesNl\Client\Command\Middleware\ExceptionMiddleware;
use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Annotations\AnnotationRegistry;
use GuzzleHttp\Client as HttpClient;
use JMS\Serializer\Naming\CamelCaseNamingStrategy;
use JMS\Serializer\Naming\PropertyNamingStrategyInterface;
use JMS\Serializer\Naming\SerializedNameAnnotationStrategy;
use JMS\Serializer\Serializer;
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
     * @var HttpClient;
     */
    protected $httpClient;

    /**
     * @var PropertyNamingStrategyInterface
     */
    protected $namingStrategy;

    /**
     * @var Serializer
     */
    protected $serializer;

    /**
     * @var string
     */
    protected $serializerFormat;

    /**
     * ClientBuilder constructor.
     */
    protected function __construct()
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
     * @param array $credentials
     * @return AbstractClientBuilder
     */
    abstract public function setAuthorization(array $credentials): AbstractClientBuilder;

    /**
     * @param string $endpoint
     * @return AbstractClientBuilder
     */
    abstract public function setServiceEndpoint(string $endpoint): AbstractClientBuilder;

    /**
     * @return Client
     * @throws AnnotationException
     */
    public function build(): Client
    {
        $resolver = new CommandHandlerResolver($this->httpClient, $this->serializer, $this->serializerFormat);
        $locator = new CallableLocator($resolver);

        return new Client(
            new CommandBus(
                [
                    new LockingMiddleware(),
                    new ExceptionMiddleware(),
                    new ValidationMiddleware(),
                    new CommandHandlerMiddleware(
                        new ClassNameExtractor(),
                        $locator,
                        new ClassNameWithoutSuffixInflector('Request')
                    )
                ]
            )
        );
    }
}
