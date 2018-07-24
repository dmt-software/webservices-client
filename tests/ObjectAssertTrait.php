<?php

namespace DMT\Test\WebservicesNl\Client;

use PHPUnit\Framework\TestCase;

/**
 * Trait ObjectAssertTrait
 *
 * @package DMT\WebservicesNl\Client
 */
trait ObjectAssertTrait
{
    /**
     * @param mixed $expectedValue
     * @param string $property
     * @param object $object
     * @param string $message
     */
    public static function assertObjectPropertyEquals($expectedValue, string $property, $object, string $message = null)
    {
        TestCase::assertObjectHasAttribute($property, $object);
        
        $reflectionObject = new \ReflectionObject($object);
        $reflectionProperty = $reflectionObject->getProperty($property);
        $reflectionProperty->setAccessible(true);

        $default = sprintf(
            "Property [%s] of %s is incorrect, expected:\n%s\nactual:\n%s",
            $reflectionProperty->getName(),
            $reflectionObject->getName(),
            var_export($expectedValue, true),
            var_export($reflectionProperty->getValue($object), true)
        );

        TestCase::assertEquals($expectedValue, $reflectionProperty->getValue($object), $message ?? $default);
    }
}
