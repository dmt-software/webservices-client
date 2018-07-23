<?php

namespace DMT\WebservicesNl\Client\Exception;

use DMT\CommandBus\Validator\ValidationException;
use DMT\WebservicesNl\Client\Exception\Client\InputException;
use DMT\WebservicesNl\Client\Exception\Server\Unavailable\InternalErrorException;
use GuzzleHttp\Exception\RequestException;

/**
 * Class ExceptionHandler
 *
 * @package DMT\WebservicesNl\Client
 */
class ExceptionHandler
{
    /**
     * @param \Throwable $exception
     *
     * @throws ServerException|ExceptionInterface
     */
    public function throwServiceException(\Throwable $exception)
    {
        $exceptionClass = ExceptionInterface::class;

        if (method_exists($exception, 'getFaultCode')) {
            $exceptionClass = $this->getServiceException($exception->getFaultCode());
        }
        if ($exceptionClass !== ExceptionInterface::class) {
            throw new $exceptionClass($exception->getMessage());
        }

        throw new ServerException('Unknown error occurred', 0, $exception);
    }

    /**
     * @param ValidationException $exception
     *
     * @throws InputException
     */
    public function throwServiceExceptionFromViolationException(ValidationException $exception)
    {
        $message = 'Invalid input given';

        if ($exception->getViolations()->count() === 1) {
            $message = $exception->getViolations()->get(0)->getMessage();
        }

        throw new InputException($message, 0, $exception);
    }

    /**
     * @param RequestException $exception
     *
     * @throws InternalErrorException|ExceptionInterface
     */
    public function throwServiceExceptionFromRequestException(RequestException $exception)
    {
        $exceptionClass = ExceptionInterface::class;

        if ($exception->hasResponse() && $exception->getResponse()->hasHeader('X-WS-ErrorCode')) {
            $exceptionClass = $this->getServiceException($exception->getResponse()->getHeaderLine('X-WS-ErrorCode'));
        }
        if ($exceptionClass !== ExceptionInterface::class) {
            throw new $exceptionClass();
        }

        throw new ServerException('Unknown error occurred', 0, $exception);
    }

    /**
     * @param string $faultCode
     * @return string
     */
    protected function getServiceException(string $faultCode = 'Server'): string
    {
        $codes = explode('.', $faultCode);
        $currentException = ExceptionInterface::class;

        foreach ($codes as $code) {
            $namespace = preg_replace('~\\\?Exception(Interface)?$~', '', $currentException);
            $childException = $namespace  . '\\' . $code . 'Exception';

            if (!class_exists($childException)) {
                break;
            }
            $currentException = $childException;
        }

        return $currentException;
    }
}
