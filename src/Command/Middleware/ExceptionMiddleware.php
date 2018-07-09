<?php

namespace DMT\WebservicesNl\Client\Command\Middleware;

use DMT\CommandBus\Validator\ValidationException;
use DMT\WebservicesNl\Client\Exception\ExceptionInterface;
use DMT\WebservicesNl\Client\Exception\ExceptionHandler;
use GuzzleHttp\Exception\RequestException;
use League\Tactician\Middleware;

/**
 * Class ExceptionMiddleware
 *
 * @package DMT\WebservicesNl\Client
 */
class ExceptionMiddleware implements Middleware
{
    /**
     * @var ExceptionHandler
     */
    protected $exceptionHandler;

    /**
     * ExceptionMiddleware constructor.
     */
    public function __construct()
    {
        $this->exceptionHandler = new ExceptionHandler();
    }

    /**
     * @param object $command
     * @param callable $next
     *
     * @return mixed
     * @throws ExceptionInterface
     */
    public function execute($command, callable $next)
    {
        try {
            return $next($command);
        } catch (ExceptionInterface $exception) {
            throw $exception;
        } catch (ValidationException $exception) {
            $this->exceptionHandler->throwServiceExceptionFromViolationException($exception);
        } catch (RequestException $exception) {
            $this->exceptionHandler->throwServiceExceptionFromRequestException($exception);
        } catch (\Throwable $exception) {
            $this->exceptionHandler->throwServiceException($exception);
        }
    }
}
