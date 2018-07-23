<?php

namespace DMT\Test\WebservicesNl\Client\Command\Middleware;

use DMT\CommandBus\Validator\ValidationException;
use DMT\Soap\Serializer\SoapFaultException;
use DMT\WebservicesNl\Client\Command\Middleware\ExceptionMiddleware;
use DMT\WebservicesNl\Client\Exception\Client\AuthenticationException;
use DMT\WebservicesNl\Client\Exception\Client\Input\FormatIncorrectException;
use DMT\WebservicesNl\Client\Exception\Client\InputException;
use DMT\WebservicesNl\Client\Exception\ClientException;
use DMT\WebservicesNl\Client\Exception\ExceptionInterface;
use DMT\WebservicesNl\Client\Exception\Server\Data\NotFoundException;
use DMT\WebservicesNl\Client\Exception\Server\Unavailable\InternalErrorException;
use DMT\WebservicesNl\Client\Exception\Server\UnavailableException;
use DMT\WebservicesNl\Client\Exception\ServerException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Stream;
use GuzzleHttp\Psr7\Uri;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ExceptionMiddlewareTest extends TestCase
{
    /**
     * @dataProvider provideServiceException
     *
     * @param \Throwable $incoming
     */
    public function testExecuteServiceExceptionFallsThrough(\Throwable $incoming)
    {
        static::expectException(get_class($incoming));

        $middleware = new ExceptionMiddleware();
        $middleware->execute(new \stdClass(), function () use ($incoming) {
            throw $incoming;
        });
    }

    public function provideServiceException(): array
    {
        return [
            [new ClientException()],
            [new InputException()],
            [new ServerException()],
            [new InternalErrorException()]
        ];
    }

    public function testExecuteValidationException()
    {
        static::expectException(InputException::class);

        $middleware = new ExceptionMiddleware();
        $middleware->execute(new \stdClass(), function () {
            $constraints = static::getMockForAbstractClass(ConstraintViolationListInterface::class);
            $constraints->expects(static::once())->method('count')->willReturn(2);

            throw new ValidationException('', 0, null, $constraints);
        });
    }

    /**
     * @dataProvider provideFaultCode
     *
     * @param string $faultCode
     * @param string $expected
     */
    public function testExecuteSoapFaultException(string $faultCode, string $expected)
    {
        static::expectException($expected);

        $middleware = new ExceptionMiddleware();
        $middleware->execute(new \stdClass(), function () use ($faultCode) {
            throw new SoapFaultException($faultCode, '');
        });
    }

    /**
     * @dataProvider provideFaultCode
     *
     * @param string $faultCode
     *
     * @throws \ReflectionException
     */
    public function testExecuteHttpRequestException(string $faultCode)
    {
        static::expectException(ExceptionInterface::class);

        /** @var MockObject|RequestInterface $request */
        $request = static::getMockForAbstractClass(RequestInterface::class);
        $request->expects(static::any())->method('getUri')->willReturn(new Uri());

        /** @var MockObject|ResponseInterface $response */
        $response = static::getMockForAbstractClass(ResponseInterface::class);
        $response->expects(static::any())->method('getBody')->willReturn(new Stream(fopen('php://input', 'r')));
        $response->expects(static::any())->method('hasHeader')->willReturn(true);
        $response->expects(static::any())->method('getHeaderLine')->willReturn($faultCode);

        $middleware = new ExceptionMiddleware();
        $middleware->execute(new \stdClass(), function () use ($request, $response) {
            throw RequestException::create($request, $response);
        });
    }

    public function provideFaultCode(): array
    {
        return [
            ['Client', ClientException::class],
            ['Client.Authentication', AuthenticationException::class],
            ['Client.Input.FormatIncorrect', FormatIncorrectException::class],
            ['Server', ServerException::class],
            ['Server.Data.NotFound', NotFoundException::class],
            ['Server.Unavailable', UnavailableException::class],
        ];
    }

    public function testUnexpectedException()
    {
        static::expectException(ServerException::class);
        static::expectExceptionMessage('Unknown error occurred');

        $middleware = new ExceptionMiddleware();
        $middleware->execute(new \stdClass(), function () {
            throw new \RuntimeException('Bad things might happen');
        });
    }
}
