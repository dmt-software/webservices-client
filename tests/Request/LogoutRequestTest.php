<?php

namespace DMT\Test\WebservicesNl\Client\Request;

use DMT\Test\WebservicesNl\Client\EntityValidatorTrait;
use DMT\Test\WebservicesNl\Client\Helper\EntityHelper;
use DMT\WebservicesNl\Client\Request\LogoutRequest;
use JMS\Serializer\Annotation\XmlRoot;
use PHPUnit\Framework\TestCase;

/**
 * Class LogoutRequestTest
 *
 * @package DMT\WebservicesNl\Client
 */
class LogoutRequestTest extends TestCase
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

        $this->entity = $this->getEntityHelper(LogoutRequest::class);
    }

    /**
     * Overrides EntityValidatorTrait::testEntityAccessors
     */
    public function testEntityAccessors()
    {
        static::assertCount(0, $this->entity->getProperties());
    }

    public function testLogoutRequestAnnotations()
    {
        $xmlRoot = new XmlRoot();
        $xmlRoot->name = 'LogoutRequest';
        $xmlRoot->namespace = 'http://www.webservices.nl/soap/';

        static::assertContainsAnnotation($xmlRoot, $this->entity->getClassAnnotations());
    }
}
