<?php

namespace DMT\Test\WebservicesNl\Client\Command\Handler\MethodNameInflector;

use DMT\WebservicesNl\Client\Command\Handler\MethodNameInflector\ClassNameWithoutSuffixInflector;
use DMT\WebservicesNl\Client\Request\LogoutRequest;
use PHPUnit\Framework\TestCase;

class ClassNameWithoutSuffixInflectorTest extends TestCase
{
    /**
     * @dataProvider provideCommand
     *
     * @param object $object
     * @param string $expected
     * @param string $suffix
     */
    public function testInflector($object, string $expected, string $suffix = null)
    {
        if (!$suffix) {
            $inflector = new ClassNameWithoutSuffixInflector();
        } else {
            $inflector = new ClassNameWithoutSuffixInflector($suffix);
        }

        static::assertSame($expected, $inflector->inflect($object, ''));
    }

    public function provideCommand(): array
    {
        return [
            [new \stdClass(), 'stdClass'],
            [new \stdClass(), 'stdClass', 'Request'],
            [new LogoutRequest(), 'logoutRequest'],
            [new LogoutRequest(), 'logout', 'Request'],
        ];
    }
}
