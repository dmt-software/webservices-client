<?php

namespace DMT\Test\WebservicesNl\Client\Helper;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;

/**
 * Class EntityHelper
 *
 * @package DMT\WebservicesNl\Client
 */
class EntityHelper
{
    /**
     * @var \ReflectionObject
     */
    protected $entity;

    /**
     * @var object
     */
    protected $instance;

    /**
     * @var AnnotationReader
     */
    protected $reader;

    /**
     * EntityHelper constructor.
     *
     * @param string $entityName
     *
     * @throws \InvalidArgumentException
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    public function __construct(string $entityName)
    {
        try {
            $this->entity = new \ReflectionClass($entityName);

            if (!$this->entity->isUserDefined()) {
                throw new \InvalidArgumentException("Entity `$entityName` is not a valid class");
            }

            if ($this->entity->isInstantiable()) {
                $this->instance = unserialize(sprintf('O:%d:"%s":0:{}', strlen($entityName), $entityName));
            }

            AnnotationRegistry::registerUniqueLoader('class_exists');
            $this->reader = new AnnotationReader();
        } catch (\ReflectionException $exception) {
            throw new \InvalidArgumentException("Entity `$entityName` is not found");
        }
    }

    /**
     * @return bool
     */
    public function hasInstance(): bool
    {
        return is_a($this->instance, $this->entity->getName());
    }

    /**
     * @return array
     */
    public function getClassAnnotations(): array
    {
        return $this->reader->getClassAnnotations($this->entity);
    }

    /**
     * Get the properties from entity.
     *
     * @return \ReflectionProperty[]
     */
    public function getProperties(): array
    {
        return $this->entity->getProperties();
    }

    /**
     * Get the annotations for a property.
     *
     * @param \ReflectionProperty $property
     * @return array
     */
    public function getPropertyAnnotations(\ReflectionProperty $property): array
    {
        return $this->reader->getPropertyAnnotations($property);
    }

    /**
     * Get the accessors for property.
     *
     * @param \ReflectionProperty $property
     *
     * @return \ReflectionMethod[] First getter(s) than setter are returned.
     */
    public function getPropertyAccessors(\ReflectionProperty $property): array
    {
        $methods = array_filter(
            $this->entity->getMethods(\ReflectionMethod::IS_PUBLIC),
            function (\ReflectionMethod $method) use ($property) {
                return preg_match("~^(set|get|has|is)(" . $property->getName() . ")$~i", $method->getName());
            }
        );

        /** Order the methods */
        usort($methods, function (\ReflectionMethod $current, \ReflectionMethod $stored) {
            return $current->getName() <=> $stored->getName();
        });

        return $methods;
    }

    /**
     * @param \ReflectionMethod $setter
     * @param mixed $value
     */
    public function setValue(\ReflectionMethod $setter, $value): void
    {
        $setter->invoke($this->instance, $value);
    }

    /**
     * @param \ReflectionMethod $getter
     *
     * @return mixed
     */
    public function getValue(\ReflectionMethod $getter)
    {
        return $getter->invoke($this->instance);
    }
}
