<?php

namespace DMT\WebservicesNl\Client;

use DMT\WebservicesNl\Client\Request\RequestInterface;
use DMT\WebservicesNl\Client\Response\ResponseInterface;
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
}
