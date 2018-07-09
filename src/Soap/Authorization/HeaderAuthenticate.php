<?php

namespace DMT\WebservicesNl\Client\Soap\Authorization;

use DMT\Soap\Serializer\SoapHeaderInterface;
use JMS\Serializer\Annotation as JMS;

/**
 * @JMS\AccessType("public_method")
 * @JMS\XmlNamespace(uri="http://www.webservices.nl/soap/")
 * @JMS\XmlRoot("HeaderAuthenticate", namespace="http://www.webservices.nl/soap/")
 */
class HeaderAuthenticate implements SoapHeaderInterface
{
    /**
     * @JMS\SerializedName("reactid")
     * @JMS\Type("string")
     * @JMS\XmlElement(cdata=false)
     *
     * @var string
     */
    protected $sessionId;

    /**
     * HeaderAuthenticate constructor.
     *
     * @param string $sessionId
     */
    public function __construct(string $sessionId)
    {
        $this->setSessionId($sessionId);
    }

    /**
     * @return string
     */
    public function getSessionId(): string
    {
        return $this->sessionId;
    }

    /**
     * @param string $sessionId
     */
    public function setSessionId(string $sessionId): void
    {
        $this->sessionId = $sessionId;
    }
}
