<?php

namespace DMT\Test\WebservicesNl\Client\Response;

use DMT\Test\WebservicesNl\Client\EntityValidatorTrait;
use DMT\Test\WebservicesNl\Client\Helper\EntityHelper;
use DMT\WebservicesNl\Client\Response\LoginResponse;
use PHPUnit\Framework\TestCase;

class LoginResponseTest extends TestCase
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

        $this->entity = $this->getEntityHelper(LoginResponse::class);
    }
}
