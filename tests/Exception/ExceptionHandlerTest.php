<?php

namespace DMT\Test\WebservicesNl\Client\Exception;

use DMT\CommandBus\Validator\ValidationException;
use DMT\Soap\Serializer\SoapFaultException;
use DMT\WebservicesNl\Client\Exception\Client\InputException;
use DMT\WebservicesNl\Client\Exception\ExceptionHandler;
use DMT\WebservicesNl\Client\Exception\ServerException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

/**
 * Class ExceptionHandlerTest
 *
 * @package DMT\WebservicesNl\Client
 */
class ExceptionHandlerTest extends TestCase
{
    /**
     * @var array Complete list of webservices.nl errors that can occur.
     *
     * @codingStandardsIgnoreStart
     */
    protected $errors = [
        'Client' => 'General error, caused by the client.',
        'Client.Authentication' => 'Authentication of the client has failed, the client is not logged in.',
        'Client.Authentication.HostRestriction' => 'Authentication failed due to restrictions on hosts and/or ip addresses.',
        'Client.Authorization' => 'The client has been authenticated, but isn\'t allowed to use the requested functionality.',
        'Client.Input' => 'An error occurred due to a problem with the client\'s input.',
        'Client.Input.FormatIncorrect' => 'The input is invalid because one of the parameters contains a syntax error or is in an incorrect format.',
        'Client.Input.Incomplete' => 'The input is invalid because one of the required parameters is missing or is incomplete.',
        'Client.Input.Invalid' => 'The input is invalid because one of the parameters contains an invalid or disallowed value.',
        'Client.Payment' => 'The request can\'t be processed, because the user (or its account) doesn\'t have sufficient balance/credits.',
        'Server' => 'General error, caused by the server.',
        'Server.Data' => 'An error occurred while retrieving requested data.',
        'Server.Data.NotFound' => 'The requested data isn\'t available (for example, the requested address does not exist).',
        'Server.Data.NotFound.Nbwo.EstimateUnavailable' => 'An accurate NBWO value can not be estimated for the specified address.',
        'Server.Data.NotFound.Kadaster.NotDeliverable' => 'The requested result is not deliverable.  (kadaster can not deliver person information due to legal reasons)',
        'Server.Data.PageNotFound' => 'The requested result page doesn\'t exist.',
        'Server.Unavailable' => 'An error occurred that causes the service to be unavailable.',
        'Server.Unavailable.InternalError' => 'The service is unavailable due to an internal server error.',
        'Server.Unavailable.Temporary' => 'The service is unavailable due to a temporary technical problem.',
    ];
    /** @codingStandardsIgnoreEnd */

    /**
     * @dataProvider provideRequestException
     *
     * @param RequestException $incoming
     * @param \Exception $expected
     */
    public function testThrowServiceExceptionFromRequestException(RequestException $incoming, \Exception $expected)
    {
        static::expectExceptionObject($expected);

        $handler = new ExceptionHandler();
        $handler->throwServiceExceptionFromRequestException($incoming);
    }

    public function testThrowServiceExceptionFromRequestExceptionWithoutErrorHeader()
    {
        static::expectException(ServerException::class);
        static::expectExceptionMessage('Unknown error occurred');

        $expected = RequestException::create(new Request('GET', '/'), new Response());

        try {
            $handler = new ExceptionHandler();
            $handler->throwServiceExceptionFromRequestException($expected);
        } catch (ServerException $exception) {
            static::assertSame($exception->getPrevious(), $expected);
            throw $exception;
        }
    }

    public function testThrowServiceExceptionFromRequestExceptionWithEmptyErrorHeader()
    {
        static::expectException(ServerException::class);
        static::expectExceptionMessage('Unknown error occurred');

        $expected = RequestException::create(
            new Request('GET', '/'),
            (new Response())->withAddedHeader('X-WS-ErrorCode', '')
        );

        try {
            $handler = new ExceptionHandler();
            $handler->throwServiceExceptionFromRequestException($expected);
        } catch (ServerException $exception) {
            static::assertSame($exception->getPrevious(), $expected);
            throw $exception;
        }
    }

    public function testThrowServiceExceptionFromRequestExceptionWithUnknownErrorHeader()
    {
        static::expectException(ServerException::class);
        static::expectExceptionMessage('Unknown error occurred');

        $expected = RequestException::create(
            new Request('GET', '/'),
            (new Response())->withAddedHeader('X-WS-ErrorCode', 'Unknown.Code')
        );

        try {
            $handler = new ExceptionHandler();
            $handler->throwServiceExceptionFromRequestException($expected);
        } catch (ServerException $exception) {
            static::assertSame($exception->getPrevious(), $expected);
            throw $exception;
        }
    }

    /**
     * @return array|\Generator
     */
    public function provideRequestException(): \Generator
    {
        $request = new Request('GET', '/');
        $response = new Response();

        /** currently max three levels deep error codes are supported */
        $errors = array_filter(
            $this->errors,
            function ($code) {
                return substr_count($code, '.') < 3;
            },
            ARRAY_FILTER_USE_KEY
        );

        foreach ($errors as $code => $message) {
            $exception = RequestException::create($request, $response->withAddedHeader('X-WS-ErrorCode', $code));
            $class = $this->getExceptionClassFromCode($code);

            yield [$exception, new $class($message)];
        }
    }

    /**
     * @dataProvider provideSoapFault
     *
     * @param \Throwable $incoming
     * @param \Exception $expected
     */
    public function testThrowServiceException(\Throwable $incoming, \Exception $expected)
    {
        static::expectExceptionObject($expected);

        $handler = new ExceptionHandler();
        $handler->throwServiceException($incoming);
    }

    public function testThrowServiceExceptionWithoutErrorCode()
    {
        static::expectException(ServerException::class);
        static::expectExceptionMessage('Unknown error occurred');

        $expected = new \RuntimeException('Oops');

        try {
            $handler = new ExceptionHandler();
            $handler->throwServiceException($expected);
        } catch (ServerException $exception) {
            static::assertSame($exception->getPrevious(), $expected);
            throw $exception;
        }
    }

    public function testThrowServiceExceptionWithUnknownErrorCode()
    {
        static::expectException(ServerException::class);
        static::expectExceptionMessage('Unknown error occurred');

        $expected = new SoapFaultException('Unknown.Error.Code', 'Oops');

        try {
            $handler = new ExceptionHandler();
            $handler->throwServiceException($expected);
        } catch (ServerException $exception) {
            static::assertSame($exception->getPrevious(), $expected);
            throw $exception;
        }
    }

    public function testThrowServiceExceptionWithEmptyErrorCode()
    {
        static::expectException(ServerException::class);
        static::expectExceptionMessage('Unknown error occurred');

        /** @var MockObject|SoapFaultException $expected */
        $expected = $this->createMock(SoapFaultException::class);
        $expected->expects(static::once())->method('getFaultCode')->willReturn('');

        try {
            $handler = new ExceptionHandler();
            $handler->throwServiceException($expected);
        } catch (ServerException $exception) {
            static::assertSame($exception->getPrevious(), $expected);
            throw $exception;
        }
    }

    /**
     * @return array|\Generator
     */
    public function provideSoapFault(): \Generator
    {
        foreach ($this->errors as $code => $message) {
            $class = $this->getExceptionClassFromCode($code);

            yield [new SoapFaultException($code, $message), new $class($message)];
        }
    }

    /**
     * @dataProvider provideValidationException
     *
     * @param ValidationException $incoming
     * @param \Exception $expected
     */
    public function testThrowServiceExceptionFromViolationException(ValidationException $incoming, \Exception $expected)
    {
        static::expectExceptionObject($expected);

        $handler = new ExceptionHandler();
        $handler->throwServiceExceptionFromViolationException($incoming);
    }

    /**
     * @return \Generator
     */
    public function provideValidationException(): \Generator
    {
        $args = [null, [], '', null, ''];
        $formatIncorrect = new ConstraintViolation('Dossier number is incorrect', ...$args);
        $missingTradeName = new ConstraintViolation('Trade_name is missing', ...$args);

        $constraintViolationLists = [
            new ConstraintViolationList([$formatIncorrect]),
            new ConstraintViolationList([$missingTradeName]),
            new ConstraintViolationList([$formatIncorrect, $missingTradeName])
        ];

        /** @var ConstraintViolationList[] $constraintViolationLists */
        foreach ($constraintViolationLists as $list) {
            $message = ($list->count() > 1) ? 'Invalid input given' : $list->get(0)->getMessage();

            yield [new ValidationException('', 0, null, $list), new InputException($message)];
        }
    }

    /**
     * Get the exception class from error code.
     *
     * @param string $code
     * @return string
     */
    protected function getExceptionClassFromCode(string $code): string
    {
        $class = sprintf('DMT\\WebservicesNl\\Client\\Exception\\%sException', str_replace('.', '\\', $code));

        /** current implementation matches max three levels deep error codes */
        if (!class_exists($class) && substr_count($code, '.') > 2) {
            $class = $this->getExceptionClassFromCode(preg_replace('~^((\.?[a-z]+){3})(.*)$~i', '$1', $code));
        }

        if (!class_exists($class)) {
            $class = ServerException::class;
        }

        return $class;
    }
}
