<?php

namespace DMT\TEst\WebservicesNl\Client\Model;

use DMT\Test\WebservicesNl\Client\EntityValidatorTrait;
use DMT\Test\WebservicesNl\Client\Fixtures\DummyPagedResult;
use PHPUnit\Framework\TestCase;

class PagedResultTest extends TestCase
{
    use EntityValidatorTrait;

    public function testPagedResult()
    {
        static::assertEntityValid(DummyPagedResult::class);
    }
}
