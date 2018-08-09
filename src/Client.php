<?php

namespace DMT\WebservicesNl\Client;

use Composer\Autoload\ClassLoader;
use DMT\WebservicesNl\Client\Request\RequestInterface;
use DMT\WebservicesNl\Client\Response\ResponseInterface;
use JMS\Serializer\Naming\CamelCaseNamingStrategy;
use JMS\Serializer\Naming\SerializedNameAnnotationStrategy;
use JMS\Serializer\SerializerBuilder;
use League\Tactician\CommandBus;

/**
 * Class Client
 *
 * @package DMT\WebservicesNl\Client
 */
class Client
{
    /**
     * @var CommandBus
     */
    protected $commandBus;

    /**
     * Client constructor.
     *
     * @param CommandBus $commandBus
     */
    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @param RequestInterface $request
     *
     * @return ResponseInterface|null
     */
    public function execute(RequestInterface $request): ?ResponseInterface
    {
        return $this->commandBus->handle($request);
    }

    /**
     * Call a service method with arguments.
     *
     * @param string $method
     * @param array $arguments
     *
     * @return object|null
     * @throws \BadMethodCallException
     */
    public function __call(string $method, array $arguments = []): ?\stdClass
    {
        if (count($arguments) === 1) {
            $arguments = array_pop($arguments);
        }

        $serializer = SerializerBuilder::create()
            ->setPropertyNamingStrategy(new SerializedNameAnnotationStrategy(new CamelCaseNamingStrategy()))
            ->addDefaultHandlers()
            ->build();

        $response = $this->execute($serializer->fromArray($arguments, $this->getRequestClassForMethod($method)));

        return $response ? json_decode($serializer->serialize($response, 'json')) : null;
    }

    /**
     * Get all the installed (available) services.
     *
     * @return array
     */
    protected function getInstalledServices(): array
    {
        $reflection = new \ReflectionObject(new ClassLoader());
        $psr4File = dirname($reflection->getFileName()) . '/autoload_psr4.php';

        $psr4Prefixes = include $psr4File;
        $installedServices = preg_filter(
            '~^DMT\\\WebservicesNl\\\((?!Client).+)\\\~', '$1',
            array_keys((array) $psr4Prefixes)
        );

        return $installedServices;
    }

    /**
     * Get the request that corresponds to the service method.
     *
     * @param string $method
     *
     * @return string
     * @throws \BadMethodCallException
     */
    protected function getRequestClassForMethod(string $method): string
    {
        if (in_array($method, ['login', 'logout'])) {
            return sprintf('DMT\\WebservicesNl\\Client\\Request\\%sRequest', ucfirst($method));
        }

        foreach ($this->getInstalledServices() as $service) {
            if (stripos($method, $service) !== 0) {
                continue;
            }

            $requestClass = sprintf(
                'DMT\\WebservicesNl\\%s\\Request\\%sRequest',
                $service,
                substr($method, strlen($service))
            );

            if (class_exists($requestClass)) {
                return $requestClass;
            }
        }

        throw new \BadMethodCallException("Function `$method` is not a valid method for this service");
    }
}
