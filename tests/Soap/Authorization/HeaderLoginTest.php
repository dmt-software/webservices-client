<?php

namespace DMT\Test\WebservicesNl\Client\Soap\Authorization;

use DMT\Test\WebservicesNl\Client\EntityValidatorTrait;
use DMT\Test\WebservicesNl\Client\Helper\EntityHelper;
use DMT\WebservicesNl\Client\Soap\Authorization\HeaderLogin;
use PHPUnit\Framework\TestCase;

/**
 * Class HeaderLoginTest
 *
 * @package DMT\WebservicesNl\Client
 */
class HeaderLoginTest extends TestCase
{
    use EntityValidatorTrait;

    /**
     * @var EntityHelper
     */
    protected $entity;

    /**
     * @throws \Doctrine\Common\Annotations\AnnotationException
     */
    public function setUp()
    {
        parent::setUp();

        $this->entity = $this->getEntityHelper(HeaderLogin::class);
    }

    public function testHeaderLoginUsesPublicMethods()
    {
        static::assertEntityUsesPublicMethods($this->entity);
    }

    public function testHeaderLoginAccessors()
    {
        static::assertEntityAccessors($this->entity);
    }
}
