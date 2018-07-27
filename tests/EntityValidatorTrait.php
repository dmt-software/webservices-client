<?php

namespace DMT\Test\WebservicesNl\Client;

use DMT\Test\WebservicesNl\Client\Helper\EntityHelper;
use JMS\Serializer\Annotation\AccessType;
use PHPUnit\Framework\Constraint\Callback;
use PHPUnit\Framework\Constraint\IsEqual;
use PHPUnit\Framework\Constraint\TraversableContains;

/**
 * Trait EntityValidatorTrait
 *
 * @package DMT\WebservicesNl\Client
 */
trait EntityValidatorTrait
{
    public function testEntityUsesPublicMethods()
    {
        static::assertEntityUsesPublicMethods($this->entity);
    }

    public function testEntityAccessors()
    {
        static::assertEntityAccessors($this->entity);
    }


    /**
     * @param string $entityName
     *
     * @return EntityHelper
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    protected function getEntityHelper(string $entityName)
    {
        return new EntityHelper($entityName);
    }

    /**
     * @param EntityHelper $entity
     */
    public static function assertEntityUsesPublicMethods(EntityHelper $entity)
    {
        $accessType = new AccessType();
        $accessType->type = 'public_method';

        static::assertContainsAnnotation(
            $accessType,
            $entity->getClassAnnotations(),
            'Missing or incorrect `AccessType`'
        );
    }

    /**
     * @param EntityHelper $entity
     */
    public static function assertEntityAccessors(EntityHelper $entity)
    {
        foreach ($entity->getProperties() as $property) {
            static::assertPropertyAccessors($entity, $property);
        }
    }

    /**
     * @param EntityHelper $entity
     * @param \ReflectionProperty $property
     */
    public static function assertPropertyAccessors(EntityHelper $entity, \ReflectionProperty $property)
    {
        $accessors = $entity->getPropertyAccessors($property);

        static::assertCount(2, $accessors);
        static::assertGetterParameter($accessors[0]);
        static::assertSetterParameter($accessors[1]);

        if ($entity->hasInstance()) {
            $value = static::getRandomValueForType(static::getTypeHint($accessors[1]->getParameters()[0]));
            $entity->setValue($accessors[1], $value);

            static::assertSame($value, $entity->getValue($accessors[0]));
        }
    }

    /**
     * @param object $expected The annotation that is expected to be present.
     * @param array $annotations
     * @param string $message
     */
    public static function assertContainsAnnotation($expected, array $annotations, string $message = '')
    {
        static::assertNotCount(0, $annotations, 'No annotations found');
        static::assertThat($annotations, static::hasAnnotation($expected, $message), $message);
    }

    /**
     * @param \ReflectionMethod $accessor
     */
    protected static function assertSetterParameter(\ReflectionMethod $accessor)
    {
        $method = $accessor->getName();
        $parameters = (array) $accessor->getParameters();

        static::assertCount(1, $parameters, "`$method` must have exactly 1 argument");
        static::assertNotNull(static::getTypeHint($parameters[0]));
    }

    /**
     * @param \ReflectionMethod $accessor
     */
    protected static function assertGetterParameter(\ReflectionMethod $accessor)
    {
        $method = $accessor->getName();

        static::assertEmpty($accessor->getParameters(), "`$method` should not have parameters");
    }

    /**
     * @param object|string $expected
     * @param string $message
     *
     * @return Callback
     */
    protected static function hasAnnotation($expected, string $message): Callback
    {
        return new Callback(
            function ($annotations) use ($expected, $message) {
                $equal = new IsEqual($expected);
                foreach ($annotations as &$annotation) {
                    if (is_string($expected)) {
                        $annotation = get_class($annotation);
                    }
                    if ($equal->evaluate($annotation, '', true)) {
                        return true;
                    }
                }
                $contains = new TraversableContains($expected);
                $contains->evaluate($annotations, $message);
            }
        );
    }

    /**
     * @param \ReflectionParameter $parameter
     *
     * @return string|null
     */
    protected static function getTypeHint(\ReflectionParameter $parameter): ?string
    {
        $type = $parameter->getType();

        return $type ? $type->getName(): null;
    }

    /**
     * @param string $type
     * @return array|bool|float|int|object|null
     */
    protected static function getRandomValueForType(string $type)
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
}
