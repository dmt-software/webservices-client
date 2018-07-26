<?php

namespace DMT\Test\WebservicesNl\Client\Model;

use DMT\Test\WebservicesNl\Client\EntityValidatorTrait;
use DMT\WebservicesNl\Client\Model\ResultInfo;
use PHPUnit\Framework\TestCase;

class ResultInfoTest extends TestCase
{
    use EntityValidatorTrait;

    public function testResultInfo()
    {
        static::assertEntityValid(ResultInfo::class);
    }
}
