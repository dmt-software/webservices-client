<?php

namespace DMT\Test\WebservicesNl\Client\Request;

use DMT\Test\WebservicesNl\Client\EntityValidatorTrait;
use DMT\Test\WebservicesNl\Client\Helper\EntityHelper;
use DMT\WebservicesNl\Client\Request\LoginRequest;
use JMS\Serializer\Annotation\XmlRoot;
use PHPUnit\Framework\TestCase;

/**
 * Class LoginRequestTest
 *
 * @package DMT\WebservicesNl\Client
 */
class LoginRequestTest extends TestCase
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

        $this->entity = $this->getEntityHelper(LoginRequest::class);
    }

    public function testLoginRequestAnnotations()
    {
        $xmlRoot = new XmlRoot();
        $xmlRoot->name = 'LoginRequest';
        $xmlRoot->namespace = 'http://www.webservices.nl/soap/';

        static::assertContainsAnnotation($xmlRoot, $this->entity->getClassAnnotations());
    }
}
