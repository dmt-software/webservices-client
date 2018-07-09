<?php

namespace DMT\WebservicesNl\Client\Soap\Authorization;

use DMT\Soap\Serializer\SoapHeaderInterface;
use JMS\Serializer\Annotation as JMS;

/**
 * @JMS\AccessType("public_method")
 * @JMS\XmlNamespace(uri="http://www.webservices.nl/soap/")
 * @JMS\XmlRoot("HeaderLogin", namespace="http://www.webservices.nl/soap/")
 */
class HeaderLogin implements SoapHeaderInterface
{
    /**
     * @JMS\SerializedName("username")
     * @JMS\Type("string")
     * @JMS\XmlElement(cdata=false)
     *
     * @var string
     */
    protected $username;

    /**
     * @JMS\SerializedName("password")
     * @JMS\Type("string")
     * @JMS\XmlElement(cdata=false)
     *
     * @var string
     */
    protected $password;

    /**
     * HeaderLogin constructor.
     *
     * @param string $username
     * @param string $password
     */
    public function __construct(string $username, string $password)
    {
        $this->setUsername($username);
        $this->setPassword($password);
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
}
