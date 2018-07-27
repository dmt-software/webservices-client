<?php

namespace DMT\Test\WebservicesNl\Client\Soap\Authorization;

use DMT\Test\WebservicesNl\Client\EntityValidatorTrait;
use DMT\Test\WebservicesNl\Client\Helper\EntityHelper;
use DMT\WebservicesNl\Client\Soap\Authorization\HeaderAuthenticate;
use PHPUnit\Framework\TestCase;

/**
 * Class HeaderAuthenticateTest
 *
 * @package DMT\WebservicesNl\Client
 */
class HeaderAuthenticateTest extends TestCase
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

        $this->entity = $this->getEntityHelper(HeaderAuthenticate::class);
    }
}
