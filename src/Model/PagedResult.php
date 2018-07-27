<?php

namespace DMT\WebservicesNl\Client\Model;

use JMS\Serializer\Annotation as JMS;

/**
 * Class PagedResult
 *
 * @JMS\AccessType("public_method")
 */
class PagedResult
{
    /**
     * @JMS\SerializedName("paging")
     * @JMS\Type("DMT\WebservicesNl\Client\Model\ResultInfo")
     * @JMS\XmlElement(cdata=false)
     *
     * @var ResultInfo
     */
    protected $paging;

    /**
     * The JMS annotation definitions will be set in concrete class.
     *
     * @var array
     */
    protected $results;

    /**
     * @return ResultInfo
     */
    public function getPaging(): ?ResultInfo
    {
        return $this->paging;
    }

    /**
     * @param ResultInfo $paging
     */
    public function setPaging(ResultInfo $paging): void
    {
        $this->paging = $paging;
    }

    /**
     * @return array
     */
    public function getResults(): ?array
    {
        return $this->results;
    }

    /**
     * @param array $results
     */
    public function setResults(array $results): void
    {
        $this->results = $results;
    }
}
