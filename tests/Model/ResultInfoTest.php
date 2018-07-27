<?php

namespace DMT\Test\WebservicesNl\Client\Model;

use DMT\Test\WebservicesNl\Client\EntityValidatorTrait;
use DMT\Test\WebservicesNl\Client\Helper\EntityHelper;
use DMT\WebservicesNl\Client\Model\ResultInfo;
use PHPUnit\Framework\TestCase;

/**
 * Class ResultInfoTest
 *
 * @package DMT\WebservicesNl\Client
 */
class ResultInfoTest extends TestCase
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

        $this->entity = $this->getEntityHelper(ResultInfo::class);
    }

    public function testResultInfoUsesPublicMethods()
    {
        static::assertEntityUsesPublicMethods($this->entity);
    }

    public function testResultInfoAccessors()
    {
        static::assertEntityAccessors($this->entity);
    }
}
