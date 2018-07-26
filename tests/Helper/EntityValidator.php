<?php

namespace DMT\Test\WebservicesNl\Client\Helper;

use PHPUnit\Framework\Constraint\Callback;
use PHPUnit\Framework\TestCase;

/**
 * Class EntityValidator
 *
 * @package DMT\WebservicesNl\Client
 */
class EntityValidator
{
    /**
     * @var string
     */
    protected $entity;

    /**
     * @var object
     */
    protected $instance;

    /**
     * @var \ReflectionMethod[]
     */
    protected $accessors;

    /**
     * @var array|[\ReflectionProperty[]]
     */
    protected $properties;

    /**
     * @var array
     */
    protected $propertyNames;

    /**
     * EntityValidator constructor.
     *
     * @param string $entity
     */
    public function __construct(string $entity)
    {
        TestCase::assertThat($entity, new Callback('class_exists'), "Entity $entity can not be found");

        $this->entity = $entity;
        $this->instance = unserialize(sprintf('O:%d:"%s":0:{}', strlen($entity), $entity));
        $this->properties = (new \ReflectionObject($this->instance))->getProperties();
        $this->propertyNames = array_map(
            function (\ReflectionProperty $property) {
                return $property->getName();
            },
            $this->properties
        );
        $this->setPropertyAccessors();

        $reflection = new \ReflectionObject($this->instance);
        TestCase::assertContains('AccessType("public_method")', $reflection->getDocComment());
    }

    public function validatePropertyAccessors()
    {
        foreach ($this->accessors as $accessors) {
            /** @var \ReflectionMethod[] $accessors */
            [$getter, $setter] = $accessors;

            $match = [];
            preg_match('~^(get|has|is|set)(.+)$~', $getter->getName(), $match);

            TestCase::assertThat(
                $property = lcfirst($match[2]),
                new Callback(function ($property) {
                    return $this->hasProperty($property);
                }),
                "property `$property` not found in $this->entity"
            );

            TestCase::assertRegExp(
                sprintf('~^(get|has|is)(%s)$~', $match[2]),
                $getter->getName(),
                "Missing accessor `[get|has|is]$match[2]` for $this->entity"
            );

            TestCase::assertRegExp(
                sprintf('~^(set)(%s)$~', $match[2]),
                $setter->getName(),
                "Missing accessor `set$match[2]` for $this->entity"
            );

            TestCase::assertCount(
                1,
                (array) $setter->getParameters(),
                "Accessor `set$match[2]` must have exactly 1 argument"
            );

            $parameter = $setter->getParameters()[0]->getName();
            TestCase::assertNotNull(
                $typeHint = $setter->getParameters()[0]->getType(),
                "Missing type hint in $this->entity::set$match[2](__unknown__ $$parameter)"
            );

            $value = $this->getRandomValueForType($typeHint->getName());
            $setter->invoke($this->instance, $value);
            TestCase::assertSame($value, $getter->invoke($this->instance));
        }
    }

    /**
     * Check if a property exists.
     *
     * @param string $property
     * @return bool
     */
    protected function hasProperty(string $property): bool
    {
        return in_array($property, $this->propertyNames);
    }

    /**
     * Get a random value.
     *
     * @param string $type
     *
     * @return array|bool|float|int|object|null
     */
    protected function getRandomValueForType(string $type)
    {
        switch ($type) {
            case 'int':
                return mt_rand(0, 9999);
            case 'bool':
                return boolval(mt_rand(0, 1));
            case 'string':
                return array_rand(array_flip(['lorem', 'ipsum', 'dolor', 'sit', 'amet', 'consectetur']));
            case 'float':
                return mt_rand(0, 999999) / 100;
            case 'array':
                return [];
            default:
                return class_exists($type) ? new $type() : null;
        }
    }

    protected function setPropertyAccessors(): void
    {
        $reflection = new \ReflectionObject($this->instance);

        $methods = array_filter(
            $reflection->getMethods(\ReflectionMethod::IS_PUBLIC),
            function (\ReflectionMethod $method) {
                return preg_match('~^(set|get|has|is)(.+)$~', $method->getName());
            }
        );

        usort(
            $methods,
            function (\ReflectionMethod $accessor, \ReflectionMethod $compare = null) {
                $match = [];
                if (!$compare || !preg_match('~^(set|get|has|is)(.+)$~', $accessor->getName(), $match)) {
                    return 1;
                }

                $comparison = [];
                if (!preg_match('~^(set|get|has|is)(.+)$~', $compare->getName(), $comparison)) {
                    return 1;
                }

                $c = $match[2] <=> $comparison[2];

                if ($c === 0) {
                    return $match[1] <=> $comparison[1];
                }

                return $c;
            }
        );

        $this->accessors = array_chunk($methods, 2);
    }
}
