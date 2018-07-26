<?php

namespace DMT\Test\WebservicesNl\Client;

use DMT\Test\WebservicesNl\Client\Helper\EntityValidator;
use PHPUnit\Framework\Constraint\Callback;
use PHPUnit\Framework\TestCase;

/**
 * Trait EntityValidatorTrait
 *
 * @package DMT\WebservicesNl\Client
 */
trait EntityValidatorTrait
{
    /**
     * @param string $entity
     */
    public static function assertEntityValid(string $entity)
    {
        $helper = new EntityValidator($entity);
        $helper->validatePropertyAccessors();
        // $helper->validateProperties() // todo
    }
}
