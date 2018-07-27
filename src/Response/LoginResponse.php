<?php

namespace DMT\WebservicesNl\Client\Response;

use JMS\Serializer\Annotation as JMS;

/**
 * Class LoginResponse
 *
 * @JMS\AccessType("public_method")
 */
class LoginResponse implements ResponseInterface
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
