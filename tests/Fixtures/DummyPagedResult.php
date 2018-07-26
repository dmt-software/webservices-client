<?php

namespace DMT\Test\WebservicesNl\Client\Fixtures;

use DMT\WebservicesNl\Client\Model\PagedResult;
use JMS\Serializer\Annotation as JMS;

/**
 * Class DummyPagedResult
 *
 * @JMS\AccessType("public_method")
 */
class DummyPagedResult extends PagedResult
{
    /**
     * @JMS\SerializedName("results")
     * @JMS\Type("array<DMT\Test\WebservicesNl\Client\Fixtures\Dummy>")
     * @JMS\XmlElement(cdata=false)
     * @JMS\XmlList(entry="item", inline=false)
     *
     * @var Dummy[]
     */
    protected $results;
}
