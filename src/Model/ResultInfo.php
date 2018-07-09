<?php

namespace DMT\WebservicesNl\Client\Model;

use JMS\Serializer\Annotation as JMS;

/**
 * Class ResultInfo
 *
 * @JMS\AccessType("public_method")
 */
class ResultInfo
{
    /**
     * @JMS\SerializedName("curpage")
     * @JMS\Type("integer")
     * @JMS\XmlElement(cdata=false)
     *
     * @var int
     */
    protected $currentPage;

    /**
     * @JMS\SerializedName("perpage")
     * @JMS\Type("integer")
     * @JMS\XmlElement(cdata=false)
     *
     * @var int
     */
    protected $itemsPerPage;

    /**
     * @JMS\SerializedName("numpages")
     * @JMS\Type("integer")
     * @JMS\XmlElement(cdata=false)
     *
     * @var int
     */
    protected $numPages;

    /**
     * @JMS\SerializedName("numresults")
     * @JMS\Type("integer")
     * @JMS\XmlElement(cdata=false)
     *
     * @var int
     */
    protected $numResults;

    /**
     * @JMS\SerializedName("maxresults")
     * @JMS\Type("integer")
     * @JMS\XmlElement(cdata=false)
     *
     * @var int
     */
    protected $maxResults;

    /**
     * @return int
     */
    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    /**
     * @param int $currentPage
     */
    public function setCurrentPage(int $currentPage): void
    {
        $this->currentPage = $currentPage;
    }

    /**
     * @return int
     */
    public function getItemsPerPage(): int
    {
        return $this->itemsPerPage;
    }

    /**
     * @param int $itemsPerPage
     */
    public function setItemsPerPage(int $itemsPerPage): void
    {
        $this->itemsPerPage = $itemsPerPage;
    }

    /**
     * @return int
     */
    public function getNumPages(): int
    {
        return $this->numPages;
    }

    /**
     * @param int $numPages
     */
    public function setNumPages(int $numPages): void
    {
        $this->numPages = $numPages;
    }

    /**
     * @return int
     */
    public function getNumResults(): int
    {
        return $this->numResults;
    }

    /**
     * @param int $numResults
     */
    public function setNumResults(int $numResults): void
    {
        $this->numResults = $numResults;
    }

    /**
     * @return int
     */
    public function getMaxResults(): int
    {
        return $this->maxResults;
    }

    /**
     * @param int $maxResults
     */
    public function setMaxResults(int $maxResults): void
    {
        $this->maxResults = $maxResults;
    }
}
