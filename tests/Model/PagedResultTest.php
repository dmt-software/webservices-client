<?php

namespace DMT\TEst\WebservicesNl\Client\Model;

use DMT\Test\WebservicesNl\Client\EntityValidatorTrait;
use DMT\Test\WebservicesNl\Client\Fixtures\DummyPagedResult;
use DMT\Test\WebservicesNl\Client\Helper\EntityHelper;
use DMT\Test\WebservicesNl\Client\Helper\EntityValidator;
use DMT\WebservicesNl\Client\Model\PagedResult;
use PHPUnit\Framework\TestCase;

class PagedResultTest extends TestCase
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

        $this->entity = $this->getEntityHelper(PagedResult::class);
    }

    public function testPagedResultUsesPublicMethods()
    {
        static::assertEntityUsesPublicMethods($this->entity);
    }

    public function testPagedResultAccessors()
    {
        static::assertEntityAccessors($this->entity);
    }
}
